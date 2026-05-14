<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class EventController extends Controller
{
    protected function canOrganize(): bool
    {
        return in_array(auth()->user()->role, ['admin', 'teacher'], true);
    }

    protected function authorizeOrganize(): void
    {
        if (! $this->canOrganize()) {
            abort(403);
        }
    }

    protected function authorizeModify(Event $event): void
    {
        $user = auth()->user();
        if ($user->role === 'student') {
            abort(403);
        }
        if ($user->role === 'admin') {
            return;
        }
        if ((int) $event->user_id === (int) $user->id) {
            return;
        }
        abort(403, 'Vous ne pouvez modifier que vos propres événements.');
    }

    protected function deleteStoredImage(?string $path): void
    {
        if ($path && ! Str::startsWith($path, 'http')) {
            Storage::disk('public')->delete($path);
        }
    }

    public function index()
    {
        $events = Event::with('creator', 'registrations')
            ->where('event_date', '>=', now()->startOfDay())
            ->orderBy('event_date')
            ->paginate(12);

        return view('events.index', compact('events'));
    }

    public function create()
    {
        $this->authorizeOrganize();

        return view('events.create');
    }

    public function store(Request $request)
    {
        $this->authorizeOrganize();

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'event_date' => 'required|date',
            'image' => 'nullable|image|max:2048',
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

        return redirect()->route('events.index')->with('success', 'Événement créé avec succès');
    }

    public function show(Event $event)
    {
        $event->load('creator', 'registrations.user');
        $isRegistered = $event->registrations()->where('user_id', auth()->id())->exists();
        $canManage = $this->canOrganize();
        $canEdit = $canManage && (auth()->user()->role === 'admin' || (int) $event->user_id === (int) auth()->id());

        return view('events.show', compact('event', 'isRegistered', 'canEdit'));
    }

    public function edit(Event $event)
    {
        $this->authorizeModify($event);

        return view('events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $this->authorizeModify($event);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'location' => 'required|string|max:255',
            'event_date' => 'required|date',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $this->deleteStoredImage($event->image);
            $event->image = $request->file('image')->store('events', 'public');
        }

        $event->update([
            'title' => $request->title,
            'description' => $request->description,
            'location' => $request->location,
            'event_date' => $request->event_date,
            'image' => $event->image,
        ]);

        return redirect()->route('events.show', $event)->with('success', 'Événement mis à jour avec succès');
    }

    public function destroy(Event $event)
    {
        $this->authorizeModify($event);

        $this->deleteStoredImage($event->image);
        $event->delete();

        return redirect()->route('events.index')->with('success', 'Événement supprimé.');
    }

    public function register(Event $event)
    {
        \App\Models\EventRegistration::firstOrCreate([
            'event_id' => $event->id,
            'user_id' => auth()->id(),
        ]);

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
