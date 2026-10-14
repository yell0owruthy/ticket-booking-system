<?php

namespace App\Http\Controllers;

use App\Models\events;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class EventsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = events::latest()->paginate(10);
        return view('admin.events.index', compact('events'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $availableImages = $this->availableEventImages();

        return view('admin.events.create', compact('availableImages'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:Musik,Festival,Seminar',
            'ticket_price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'existing_image' => ['nullable', 'string', Rule::in([...$this->availableEventImages(), '__remove'])],
        ]);

        $imagePath = $request->hasFile('image')
            ? $request->file('image')->store('events', 'public')
            : ($request->input('existing_image') === '__remove' ? null : $request->input('existing_image'));

        events::create([
            'title' => $request->title,
            'category' => $request->category,
            'ticket_price' => $request->ticket_price,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return redirect()->route('events.index')->with('success', 'Event created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(events $events)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(events $event)
    {
        $availableImages = $this->availableEventImages();

        return view('admin.events.edit', compact('event', 'availableImages'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, events $event)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'required|in:Musik,Festival,Seminar',
            'ticket_price' => 'required|numeric|min:0',
            'description' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'existing_image' => ['nullable', 'string', Rule::in([...$this->availableEventImages(), '__remove'])],
        ]);

        $imagePath = $event->image;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('events', 'public');
        } elseif ($request->input('existing_image') === '__remove') {
            $imagePath = null;
        } elseif ($request->filled('existing_image')) {
            $imagePath = $request->input('existing_image');
        }

        $event->update([
            'title' => $request->title,
            'category' => $request->category,
            'ticket_price' => $request->ticket_price,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return redirect()->route('events.index')->with('success', 'Event updated successfully.');
    }

    private function availableEventImages(): array
    {
        $imageExtensions = ['gif', 'jpeg', 'jpg', 'png', 'svg', 'webp'];

        return array_values(array_filter(
            Storage::disk('public')->files('events'),
            fn (string $path): bool => in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), $imageExtensions, true)
        ));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(events $events)
    {
        //
    }
}
