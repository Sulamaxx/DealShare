<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Subscription;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'post_id' => "required|exists:posts,id"
        ]);
        $user = Auth::user();
        if (Auth::user()) {

            $post = Post::with('user')->findOrFail($request->post_id);

            // Check if the user is already subscribed to avoid duplicates
            $existingSubscription = Subscription::where('user_id', $user->id)
                ->where('post_id', $request->post_id)
                ->first();

            if ($existingSubscription) {
                return redirect()->back()->with('error', 'You are already subscribed to this post.');
            }

            Subscription::create([
                'user_id' => Auth::user()->id,
                'post_id' => $request->post_id
            ]);

            if ($post->user && $post->user->email && $post->user->id !== $user->id) { // Ensure post has author, email, and not sending to the subscriber
                $appName = config('app.name');
                $subject = 'New Subscription to Your Deal on ' . $appName;

                $viewDealUrl = url('/deals/' . $post->id . '?title=' . str_replace(' ', '-', $post->title));

                $body = "# Hello **{$post->user->name}**,\n\n";
                $body .= "We're letting you know that **{$user->name}** has just subscribed to your deal: **\"{$post->title}\"** on **{$appName}**.\n\n";
                $body .= "You may want to keep an eye on the deal and engage with subscribers.\n\n";
                $body .= "You can view the deal at any time by clicking the button below:\n\n";
                $body .= "If you have any questions, feel free to reach out to us at [info@buyme.lk].\n\n";
                $body .= "Thank you,\nThe Team at {$appName}";

                if (send_generic_email($post->user->email, $subject, $body, null, null)) {
                    Log::info("Subscription notification email dispatched to post creator {$post->user->email} for post ID: {$post->id}");
                } else {
                    Log::error("Failed to send subscription notification email to post creator {$post->user->email} for post ID: {$post->id}");
                }
            } else {
                Log::info("Post subscribed to, but no email sent to creator (no creator, no email, or creator is subscriber).");
            }

            return redirect()->back()->with('success', 'Post subscribed successfully!');
        } else {
            return redirect()->route('login')->with('error', "Please login to enable subscription");
        }
    }
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => "required|exists:subscriptions,id"
        ]);
        $user = Auth::user();
        if (Auth::user()) {
            $subscription = Subscription::with('post.user')->findOrFail($request->id);
            if ($subscription) {


                // Ensure the subscription belongs to the authenticated user for security
                if ($subscription->user_id !== $user->id) {
                    return redirect()->back()->with('error', 'You are not authorized to unsubscribe from this post.');
                }

                $postTitle = $subscription->post->title ?? 'a deal';
                $postId = $subscription->post->id ?? 'N/A';
                $postCreator = $subscription->post->user;

                $subscription->delete();

                // --- Send Email to the Post Creator ---
                if ($postCreator && $postCreator->email && $postCreator->id !== $user->id) { // Ensure post has author, email, and not sending to the unsubscriber
                    $appName = config('app.name');
                    $subject = 'A User Unsubscribed from Your Deal on ' . $appName;

                    $viewDealUrl = url('/deals/' . $postId . '?title=' . str_replace(' ', '-', $postTitle));

                    $body = "# Hello **{$postCreator->name}**,\n\n";
                    $body .= "We're letting you know that **{$user->name}** has unsubscribed from your deal: **\"{$postTitle}\"** on **{$appName}**.\n\n";
                    $body .= "You may want to review the deal and see if any adjustments are needed to re-engage users.\n\n";
                    $body .= "You can view the deal at any time by clicking the button below:\n\n";
                    $body .= "If you have any questions, feel free to reach out to us at [info@buyme.lk].\n\n";
                    $body .= "Thank you,\nThe Team at {$appName}";

                    if (send_generic_email($postCreator->email, $subject, $body, null, null)) {
                        Log::info("Unsubscription notification email dispatched to post creator {$postCreator->email} for post ID: {$postId}");
                    } else {
                        Log::error("Failed to send unsubscription notification email to post creator {$postCreator->email} for post ID: {$postId}");
                    }
                } else {
                    Log::info("Post unsubscribed from, but no email sent to creator (no creator, no email, or creator is unsubscriber).");
                }
                return redirect()->back()->with('success', 'Unsubscribed successfully!');
            } else {
                return redirect()->back()->with('error', 'Subscription not found.');
            }
        } else {
            return redirect()->route('login')->with('error', "Please login to enable subscription");
        }
    }
}
