<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CommunityEvent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(): View
    {
        $events = CommunityEvent::withCount(['registrations' => fn($q) => $q->where('status', 'registered')])
            ->orderBy('date', 'desc')
            ->paginate(15);

        return view('admin.events.index', compact('events'));
    }

    public function create(): View
    {
        return view('admin.events.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'location'     => ['required', 'string', 'max:255'],
            'date'         => ['required', 'date', 'after_or_equal:today'],
            'time'         => ['required', 'date_format:H:i'],
            'quota'        => ['required', 'integer', 'min:0'],
            'poster_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        $posterPath = null;
        if ($request->hasFile('poster_image')) {
            $posterPath = $request->file('poster_image')->store('events', 'public');
        }

        CommunityEvent::create([
            ...$validated,
            'poster_image' => $posterPath,
            'created_by'   => auth()->id(),
        ]);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil dibuat!');
    }

    public function edit(CommunityEvent $event): View
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, CommunityEvent $event): RedirectResponse
    {
        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'location'     => ['required', 'string', 'max:255'],
            'date'         => ['required', 'date'],
            'time'         => ['required', 'date_format:H:i'],
            'quota'        => ['required', 'integer', 'min:0'],
            'poster_image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
        ]);

        if ($request->hasFile('poster_image')) {
            // Delete old image
            if ($event->poster_image) {
                Storage::disk('public')->delete($event->poster_image);
            }
            $validated['poster_image'] = $request->file('poster_image')->store('events', 'public');
        } else {
            unset($validated['poster_image']);
        }

        $event->update($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil diperbarui!');
    }

    public function destroy(CommunityEvent $event): RedirectResponse
    {
        if ($event->poster_image) {
            Storage::disk('public')->delete($event->poster_image);
        }

        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event berhasil dihapus.');
    }
}
