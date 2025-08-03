<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Illuminate\Validation\Rules\File;

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
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        // Fill the user with validated data
        $user->fill($request->validated());

        // If email changed, reset email verification
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // Save the user
        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Update the user's profile picture.
     */
    public function updatePicture(Request $request): RedirectResponse
    {
        // Validate the uploaded file
        $request->validate([
            'profile_picture' => ['required', 'image', 'max:2048'], // Max 2MB
        ]);

        $user = $request->user();

        // Delete old profile picture if it exists and is not a default
        if ($user->profile_picture && !str_contains($user->profile_picture, 'ui-avatars.com')) {
            // This would be where you delete from Cloudinary
            // For now, we'll store locally in public/uploads/profiles
        }

        // Handle file upload
        if ($request->hasFile('profile_picture')) {
            $file = $request->file('profile_picture');
            
            // Generate a unique filename
            $filename = $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            
            // Store the file in public/uploads/profiles directory
            $path = $file->storeAs('uploads/profiles', $filename, 'public');
            
            // Update user's profile picture path
            $user->update([
                'profile_picture' => '/storage/' . $path
            ]);
        }

        return Redirect::route('profile.edit')->with('picture-updated', true);
    }

    /**
     * Update additional profile information.
     */
    public function updateAdditional(Request $request): RedirectResponse
    {
        // Validate additional profile fields
        $validated = $request->validate([
            'bio' => ['nullable', 'string', 'max:500'],
            'location' => ['nullable', 'string', 'max:100'],
            'website' => ['nullable', 'url', 'max:255'],
        ]);

        // Update the user's additional information
        $request->user()->update($validated);

        return Redirect::route('profile.edit')->with('additional-updated', true);
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

        // Delete profile picture if it exists
        if ($user->profile_picture && !str_contains($user->profile_picture, 'ui-avatars.com')) {
            // Delete the profile picture file
            $picturePath = str_replace('/storage/', '', $user->profile_picture);
            Storage::disk('public')->delete($picturePath);
        }

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

/**
     * Show a user's public profile
     */
    public function show(Request $request, $username): View
    {
        // Find user by username or ID
        $user = \App\Models\User::where('username', $username)
                    ->orWhere('id', $username)
                    ->firstOrFail();

        // Load the user's published posts relationship
        $user->load(['publishedPosts' => function($query) {
            $query->latest()->take(5);
        }]);

        return view('profile.show', [
            'user' => $user,
            'isOwnProfile' => $request->user() && $request->user()->id === $user->id
        ]);
    }
}