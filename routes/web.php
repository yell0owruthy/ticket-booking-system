<?php

use App\Http\Controllers\EventsController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

// 1. Rute Sisi Pelanggan (Customer Publik)
// CONTOH: Saat pelanggan mengetik http://127.0.0.1:8000 di browser, rute ini memanggil OrdersController@index untuk menampilkan daftar menu.
Route::get('/', [OrdersController::class, 'index'])->name('customers.index');

// CONTOH: Saat pelanggan mengklik tombol 'Pesan Sekarang' di modal, data nama, meja, dan porsi dikirim lewat POST ke rute ini untuk diproses simpan.
Route::post('/checkout', [OrdersController::class, 'store'])->name('customers.checkout');

// 2. Rute Dashboard Breeze
// CONTOH: Mengarahkan URL /dashboard langsung ke rekap pesanan admin. Middleware 'auth' memastikan hanya yang sudah login yang bisa melihat.
Route::get('/dashboard', [OrdersController::class, 'adminDashboard'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// 3. Rute Grup Terproteksi Admin (Wajib Login)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // CONTOH: Saat admin mengganti dropdown status pesanan #5 dari 'Pending' ke 'Selesai', form mengirim request PATCH ke rute ini.
    Route::patch('/admin/orders/{id}/status', [OrdersController::class, 'updateStatus'])->name('admin.orders.updateStatus');
    
    // Route Resource:
    Route::resource('/admin/events', EventsController::class);
});

// Memanggil rute otentikasi login & logout bawaan Laravel Breeze (/login, /logout, /register)
require __DIR__.'/auth.php';