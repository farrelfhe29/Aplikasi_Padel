<?php

namespace App\Http\Controllers;

use App\Models\CommunityEvent;
use App\Models\EventRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CommunityEventController extends Controller
{
    public function index(): View
    {
        $events = CommunityEvent::orderBy('date', 'asc')
            ->where('date', '>=', now()->toDateString())
            ->paginate(9);

        $pastEvents = CommunityEvent::orderBy('date', 'desc')
            ->where('date', '<', now()->toDateString())
            ->limit(3)
            ->get();

        return view('community.events.index', compact('events', 'pastEvents'));
    }

    public function show(CommunityEvent $event): View
    {
        $event->load('creator', 'registrations.user');
        $userRegistration = auth()->check()
            ? $event->registrations()->where('user_id', auth()->id())->first()
            : null;

        return view('community.events.show', compact('event', 'userRegistration'));
    }

    public function register(Request $request, CommunityEvent $event): RedirectResponse
    {
        $user = auth()->user();

        // Check if already registered
        $existing = EventRegistration::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existing) {
            if ($existing->status === 'cancelled') {
                $existing->update(['status' => 'registered']);
                return back()->with('success', 'Pendaftaran Anda berhasil diaktifkan kembali!');
            }
            return back()->with('error', 'Anda sudah terdaftar di event ini.');
        }

        if ($event->isFull()) {
            return back()->with('error', 'Kuota event ini sudah penuh.');
        }

        EventRegistration::create([
            'event_id' => $event->id,
            'user_id'  => $user->id,
            'status'   => 'registered',
        ]);

        return back()->with('success', 'Berhasil mendaftar! Sampai jumpa di ' . $event->title . '!');
    }

    public function cancelRegistration(CommunityEvent $event): RedirectResponse
    {
        EventRegistration::where('event_id', $event->id)
            ->where('user_id', auth()->id())
            ->update(['status' => 'cancelled']);

        return back()->with('success', 'Pendaftaran Anda telah dibatalkan.');
    }
}
