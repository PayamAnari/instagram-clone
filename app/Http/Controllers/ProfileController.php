<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Like;
use App\Models\Post;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        // Retrieve the authenticated user
        $user = $request->user();
    
        // Fill the user model with validated data
        $user->fill($request->validated());
    
        // Set the bio field
        $user->bio = $request->input('bio');
    
        // If the email is changed, mark it as unverified
        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }
    
        // Save the changes
        $user->save();
    
        return Redirect::route('profile.edit');
    }
    

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        Comment::whereHas('post', function ($query) use ($user) {
            $query->where('user_id', $user->id);
        })->delete();

        Like::where('user_id', $user->id)->delete();

        Post::where('user_id', $user->id)->delete();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

