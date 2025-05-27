<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\WarningEmail;
use App\Models\Badge;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;
use Exception;
use Illuminate\Support\Facades\Mail;

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
            'is_private' => ['nullable', 'boolean'], // 'boolean' validates 0, 1, true, false, "0", "1"
            /*'email_thread_reply' => ['nullable', 'boolean'],
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

            $data['is_private'] = $request->has('is_private');
            /*  $data['email_thread_reply'] = $request->has('email_thread_reply');
            $data['email_thread_reply_like'] = $request->has('email_thread_reply_like');
            $data['email_mention'] = $request->has('email_mention'); */

            $user->update($data);

            $appName = config('app.name');
            $subject = 'Your Profile Settings Have Been Updated on ' . $appName;

            $body = "# Hello **{$user->name}**,\n\n";
            $body .= "This is a confirmation that your profile settings on **{$appName}** have been successfully updated.\n\n";

            $changesMade = false;
            // Check for email change
            if ($user->email !== $originalEmail) {
                $body .= "* **Email Address:** Changed from `{$originalEmail}` to `{$user->email}`\n";
                $body .= "If you did not initiate this change, please contact us immediately.\n\n";
                $changesMade = true;
            }

            // Check for name change
            if ($user->name !== $request->input('name')) { // Compare with validated request data, as $user->name is already updated
                $body .= "* **Name:** Updated to `{$user->name}`\n";
                $changesMade = true;
            }

            // Check for profile photo change
            if ($request->hasFile('image')) {
                $body .= "* **Profile Photo:** Updated\n";
                $changesMade = true;
            }

            if (isset($data['is_private']) && $data['is_private'] !== $user->getOriginal('is_private')) {
                $body .= "* **Profile Privacy:** Changed to " . ($data['is_private'] ? 'Private' : 'Public') . "\n";
                $changesMade = true;
            }

            if (!$changesMade) {
                $body .= "No significant content changes were detected that would warrant listing.\n";
            }

            $body .= "\nIf you have any questions or did not make these changes, please contact our support team immediately at [info@buyme.lk].\n\n";
            //$body .= "Thank you,\nThe Team at {$appName}";

            if ($user->email) {
                send_generic_email($user->email, $subject, $body, url('/my-deals'), 'View Your Profile Settings');
            }

            return redirect()->route('my-deals')->with('success', 'Profile settings updated successfully!');
        } catch (Exception $e) {
            // --- Log the error and set an error flash message ---
            Log::error("Error updating user settings for user {$user->id}: " . $e->getMessage());
            return redirect()->route('my-deals')->with('error', 'Failed to update profile settings. Please try again.');
        }
    }

    public function getProfileData($id)
    {
        try {
            $user = User::findOrFail($id);

            if ($user->is_private && (!Auth::check() || Auth::id() !== $user->id)) {
                return response()->json(['error' => 'This profile is private.'], 403); // 403 Forbidden
            }

            $totalUpvotes = $user->posts()->sum('upvotes');
            $totalDownvotes = $user->posts()->sum('downvotes');
            $netVoteCount = $totalUpvotes - $totalDownvotes;

            $totalDealsSubmitted = $user->posts()->count();
            $totalCommentsMade = $user->comments()->count();

            $highestQualifyingBadge = Badge::where('vote-count', '<=', $netVoteCount)
                ->orderBy('vote-count', 'desc')
                ->first();

            $responseData = [
                'id' => $user->id,
                'name' => $user->name,
                // 'email' => $user->email,
                // Provide the full URL for the profile photo
                'profile_photo_url' => $user->profile_photo_path ? asset($user->profile_photo_path) : asset('assets/images/user.png'),
                // Format dates for display
                'joined_date' => $user->created_at->format('F d, Y'),
                // Assuming 'last_seen' column exists and is a Carbon instance
                'last_seen' => $user->last_seen?->format('F d, Y') ?? 'N/A',
                // Assuming 'location' column exists
                'location' => $user->location ?? 'Not specified',
                'total_deals_submitted' => $totalDealsSubmitted,
                'total_upvotes_received' => $totalUpvotes,
                'total_downvotes_received' => $totalDownvotes,
                'total_comments_made' => $totalCommentsMade,
                // Include badge data if a badge was found
                'highest_qualifying_badge' => $highestQualifyingBadge ? [
                    'id' => $highestQualifyingBadge->id,
                    'name' => $highestQualifyingBadge->name,
                    'description' => $highestQualifyingBadge->description,
                    // Provide the full URL for the badge icon
                    'icon_url' => $highestQualifyingBadge->icon ? asset($highestQualifyingBadge->icon) : null, // The threshold for this badge
                ] : null,
            ];

            // Return the data as a JSON response
            return response()->json($responseData);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            // If findOrFail did not find the user, return a 404 response
            return response()->json(['error' => 'User not found'], 404);
        } catch (Exception $e) {
            // Catch any other exceptions, log them, and return a 500 error response
            Log::error("Error fetching profile data for user {$id}: " . $e->getMessage());
            return response()->json(['error' => 'Could not fetch profile data'], 500);
        }
    }
}
