<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use Exception;

class UserController extends Controller
{
    public function updateSettings(Request $request)
    {
        $user = Auth::user(); // Get the authenticated user instance

        // --- Validate the incoming request data ---
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            // Validate email for uniqueness, ignoring the current user's email
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            // Validate the uploaded image file
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:2048'], // Max 2MB
            // Validate checkbox inputs (assuming they map to boolean columns)
            /* 'is_private' => ['nullable', 'boolean'], // 'boolean' validates 0, 1, true, false, "0", "1"
            'email_thread_reply' => ['nullable', 'boolean'],
            'email_thread_reply_like' => ['nullable', 'boolean'],
            'email_mention' => ['nullable', 'boolean'], */
        ]);

        try {
            $data = $request->only(['name', 'email']);

            // --- Handle Profile Photo Upload ---
            if ($request->hasFile('image')) {
                // Delete the old profile photo if it exists
                if ($user->profile_photo_path && Storage::disk('public')->exists($user->profile_photo_path)) {
                    Storage::disk('public')->delete($user->profile_photo_path);
                }
                $path = $request->file('image')->store('profile-photos', 'public');
                $data['profile_photo_path'] = $path; // Add the new path to the data array
            }

            /* $data['is_private'] = $request->has('is_private');
            $data['email_thread_reply'] = $request->has('email_thread_reply');
            $data['email_thread_reply_like'] = $request->has('email_thread_reply_like');
            $data['email_mention'] = $request->has('email_mention'); */

            $user->update($data);

            return redirect()->route('my-deals')->with('success', 'Profile settings updated successfully!');
        } catch (Exception $e) {
            // --- Log the error and set an error flash message ---
            Log::error("Error updating user settings for user {$user->id}: " . $e->getMessage());
            return redirect()->route('my-deals')->with('error', 'Failed to update profile settings. Please try again.');
        }
    }
}
