<?php

namespace App\Http\Controllers;

use App\Models\orders;
use Illuminate\Http\Request;
use App\Models\events;
use App\Models\order_details;
use Illuminate\Support\Facades\DB;

class OrdersController extends Controller
{
    /**
     * Menampilkan katalog event yang tersedia untuk dipesan pelanggan.
     */
    public function index()
    {
        $events = events::all();
        return view('customers.index', compact('events'));
    }

    /**
     * Memvalidasi pesanan, menghitung total harga, lalu menyimpan pesanan
     * beserta detail tiket secara atomik di dalam transaksi database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email',
            'items' => 'required|array',
            'items.*' => 'nullable|integer|min:0',
        ]);

        $orderedItems = array_filter($request->items ?? [], fn ($qty) => (int) $qty > 0);

        if (empty($orderedItems)) {
            return back()->with('error', 'Pilih minimal 1 tiket.');
        }

        DB::beginTransaction();

        try {
            $order = orders::create([
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'total_price' => 0,
                'status' => 'pending',
            ]);

            $totalPrice = 0;

            foreach ($orderedItems as $eventId => $quantity) {
                $event = events::findOrFail($eventId);
                $subtotal = $event->price * $quantity;
                $totalPrice += $subtotal;

                order_details::create([
                    'order_id' => $order->id,
                    'event_id' => $event->id,
                    'quantity' => $quantity,
                    'subtotal' => $subtotal,
                ]);
            }

            $order->update(['total_price' => $totalPrice]);

            DB::commit();

            return redirect()->route('customers.index')->with('success', 'Pemesanan berhasil dibuat! Email customer: ' . $order->customer_email);
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->with('error', 'Gagal memproses pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan dashboard admin beserta pesanan dan detail event-nya,
     * dimulai dari pesanan terbaru.
     */
    public function adminDashboard()
    {
        $orders = orders::with('orders_details.events')->latest()->get();

        return view('dashboard', compact('orders'));
    }


    /**
     * Memvalidasi dan memperbarui status pesanan berdasarkan ID,
     * lalu mengembalikan admin ke halaman sebelumnya dengan notifikasi.
     */
    public function updateStatus(Request $request, int $id)
    {
        $request->validate([
            'status' => 'required|string',
        ]);

        // Memperbaiki huruf kapital Orders menjadi orders menyesuaikan nama Model kamu
        $order = orders::findOrFail($id);
        $order->update(['status' => $request->status]);

        return back()->with('success', 'Status pesanan #' . $order->id . ' berhasil diperbarui!');
    }
}