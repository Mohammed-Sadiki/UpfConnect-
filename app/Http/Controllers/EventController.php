<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('creator', 'registrations')
            ->where('event_date', '>=', now()->startOfDay())
            ->orderBy('event_date')
            ->paginate(12);
            
        return view('events.index', compact('events'));
    }

    public function store(Request $request)
    {
        if (auth()->user()->role === 'student') abort(403);
        
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'event_date' => 'required|date',
            'image' => 'nullable|image|max:2048'
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('events', 'public');
        }

        Event::create([
            'user_id' => auth()->id(),
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'event_date' => $request->event_date,
            'image' => $imagePath,
        ]);

        return back()->with('success', 'Événement créé avec succès');
    }

    public function show(Event $event)
    {
        $event->load('creator', 'registrations.user');
        $isRegistered = $event->registrations()->where('user_id', auth()->id())->exists();
        return view('events.show', compact('event', 'isRegistered'));
    }

    public function register(Event $event)
    {
        \App\Models\EventRegistration::firstOrCreate([
            'event_id' => $event->id,
            'user_id' => auth()->id(),
        ]);
        
        // Example: Mail sending could be here
        // \Illuminate\Support\Facades\Mail::to(auth()->user())->send(new \App\Mail\EventRegistered($event));

        return back()->with('success', 'Inscription confirmée');
    }

    public function unregister(Event $event)
    {
        \App\Models\EventRegistration::where('event_id', $event->id)
            ->where('user_id', auth()->id())
            ->delete();

        return back()->with('success', 'Désinscription confirmée');
    }
}
