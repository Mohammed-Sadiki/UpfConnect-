<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\ImageUploadService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function show(User $user)
    {
        $user->load([
            'profile',
            'posts' => fn($q) => $q->withCount('comments')->latest(),
        ]);
        $isConnected = false;

        if (auth()->check() && auth()->id() !== $user->id) {
            $isConnected = \App\Models\Connection::where(function ($q) use ($user) {
                $q->where('sender_id', auth()->id())->where('receiver_id', $user->id);
            })->orWhere(function ($q) use ($user) {
                $q->where('sender_id', $user->id)->where('receiver_id', auth()->id());
            })->first();
        }

        return view('profile.show', compact('user', 'isConnected'));
    }

    public function edit()
    {
        $user = auth()->user()->load('profile');
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'name'         => 'required|string|max:255',
            'email'        => 'nullable|email|max:255|unique:users,email,' . $user->id,
            'bio'          => 'nullable|string|max:500',
            'avatar'       => 'nullable|image|max:5120',
            'linkedin_url' => 'nullable|url',
            'github_url'   => 'nullable|url',
            'cv'           => 'nullable|file|mimes:pdf|max:5120',
            'skills'       => 'nullable|string',
            'interests'    => 'nullable|string',
        ]);

        // Update User
        $user->name = $request->name;
        $user->bio  = $request->bio;

        if ($request->filled('email') && $request->email !== $user->email) {
            $user->email            = $request->email;
            $user->email_verified_at = null;
        }

        if ($request->hasFile('avatar')) {
            try {
                ImageUploadService::delete($user->avatar);
                $user->avatar = ImageUploadService::uploadAvatar($request->file('avatar'), 300);
            } catch (\Exception $e) {
                return back()->with('error', 'Erreur lors de l\'upload de l\'avatar : ' . $e->getMessage());
            }
        }
        $user->save();

        // Update Profile
        $profile = $user->profile ?? new \App\Models\Profile(['user_id' => $user->id]);
        $profile->linkedin_url = $request->linkedin_url;
        $profile->github_url   = $request->github_url;

        if ($request->hasFile('cv')) {
            if ($profile->cv_path) {
                Storage::disk('public')->delete($profile->cv_path);
            }
            $profile->cv_path = $request->file('cv')->store('cvs', 'public');
        }

        $profile->skills    = $request->skills    ? explode(',', $request->skills)    : [];
        $profile->interests = $request->interests ? explode(',', $request->interests) : [];
        $profile->save();

        return redirect()->route('profile.show', $user)->with('success', 'Profil mis à jour avec succès');
    }

    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = auth()->user();
        Auth::logout();
        $user->delete();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
