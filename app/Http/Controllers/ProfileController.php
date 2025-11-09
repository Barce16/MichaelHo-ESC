<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
/**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Handle avatar removal
        if ($request->boolean('remove_avatar') && $user->profile_photo_path) {
            if (Storage::disk('public')->exists($user->profile_photo_path)) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $user->profile_photo_path = null;
        }

        // Handle avatar upload
        if ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');

            if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }

            $user->profile_photo_path = $path;
        }

        // Handle signature removal (admin only)
        if ($user->user_type === 'admin' && $request->boolean('remove_signature') && $user->signature_path) {
            if (Storage::disk('public')->exists($user->signature_path)) {
                Storage::disk('public')->delete($user->signature_path);
            }
            $user->signature_path = null;
        }

        // Handle signature upload (admin only)
        if ($user->user_type === 'admin' && $request->hasFile('signature')) {
            $path = $request->file('signature')->store('signatures', 'public');

            if ($user->signature_path && Storage::disk('public')->exists($user->signature_path)) {
                Storage::disk('public')->delete($user->signature_path);
            }

            $user->signature_path = $path;
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }
    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
