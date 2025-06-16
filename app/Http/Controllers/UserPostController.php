<?php
namespace App\Http\Controllers;

use App\Events\PostCreated;
use App\Events\PostVoted;
use App\Models\Badge;
use App\Models\Post;
use App\Models\Report;
use App\Models\Setting;
use App\Models\Vote;
use Carbon\Carbon;
use function Laravel\Prompts\error;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class UserPostController extends Controller
{
    public function myDeals()
    {
        $user = Auth::user();

        $totalUpvotes   = $user->posts()->sum('upvotes');
        $totalDownvotes = $user->posts()->sum('downvotes');
        $netVoteCount   = $totalUpvotes - $totalDownvotes;

        $badge = Badge::where('vote-count', '<=', $netVoteCount)
            ->orderBy('vote-count', 'desc') // Order by vote_count descending
            ->first();

        if ($badge && (! $user->badge_id || $user->badge_id !== $badge->id)) {
            $oldBadgeName = $user->badge ? $user->badge->name : 'No Badge';
            $newBadgeName = $badge->name;

            // Update the user's badge
            $user->badge_id = $badge->id;
            $user->save();

            // --- Send Email to the User About Badge Change ---
            $appName = config('app.name');
            $subject = 'Your Badge Has Changed on ' . $appName;

            $body = "# Hello **{$user->name}**,\n\n";
            $body .= "Congratulations! Your badge has been updated on **{$appName}**.\n\n";
            $body .= "Your previous badge was: **{$oldBadgeName}**\n\n";
            $body .= "Your new badge is: **{$newBadgeName}**\n\n";
            $body .= "This change reflects your increased activity and positive contributions to our community.\n\n";
            $body .= "Keep up the great work!\n\n";
            $body .= "Thank you,\nThe Team at {$appName}";

            if (send_generic_email($user->email, $subject, $body, null, null)) { // No specific URL needed here
                Log::info("Badge change notification email dispatched to user {$user->email}");
            } else {
                Log::error("Failed to send badge change notification email to user {$user->email}");
            }
        }

        $posts = Post::where('post_by', Auth::user()->id)->paginate(10);
        return view('my_deals', compact('posts', 'badge'));
    }

    public function myActivities()
    {
        $user = Auth::user();

        $totalUpvotes   = $user->posts()->sum('upvotes');
        $totalDownvotes = $user->posts()->sum('downvotes');
        $netVoteCount   = $totalUpvotes - $totalDownvotes;

        $badge = Badge::where('vote-count', '<=', $netVoteCount)
            ->orderBy('vote-count', 'desc') // Order by vote_count descending
            ->first();

        //$posts = Post::where('post_by', Auth::user()->id)->paginate(10);

        $commentedPostIds = $user->comments() // Access the user's comments relationship
            ->distinct('post_id')                 // Get only unique post IDs
            ->pluck('post_id');                   // Get a collection of just the 'post_id' values

        $subscribedPostIds = $user->subscriptions()
            ->distinct('post_id')
            ->pluck('post_id');

        $votedPostIds = $user->votes()
            ->distinct('post_id')
            ->pluck('post_id');

        $commentedPosts = Post::whereIn('id', $commentedPostIds)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $subscribedPosts = Post::whereIn('id', $subscribedPostIds)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $votedPosts = Post::whereIn('id', $votedPostIds)
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        if ($badge && (! $user->badge_id || $user->badge_id !== $badge->id)) {
            $oldBadgeName = $user->badge ? $user->badge->name : 'No Badge';
            $newBadgeName = $badge->name;

            // Update the user's badge
            $user->badge_id = $badge->id;
            $user->save();

            // --- Send Email to the User About Badge Change ---
            $appName = config('app.name');
            $subject = 'Your Badge Has Changed on ' . $appName;

            $body = "# Hello **{$user->name}**,\n\n";
            $body .= "Congratulations! Your badge has been updated on **{$appName}**.\n\n";
            $body .= "Your previous badge was: **{$oldBadgeName}**\n\n";
            $body .= "Your new badge is: **{$newBadgeName}**\n\n";
            $body .= "This change reflects your increased activity and positive contributions to our community.\n\n";
            $body .= "Keep up the great work!\n\n";
            $body .= "Thank you,\nThe Team at {$appName}";

            if (send_generic_email($user->email, $subject, $body, null, null)) { // No specific URL needed here
                Log::info("Badge change notification email dispatched to user {$user->email}");
            } else {
                Log::error("Failed to send badge change notification email to user {$user->email}");
            }
        }

        return view('activities', compact('commentedPosts', 'subscribedPosts', 'votedPosts', 'badge'));
    }

    public function newDeals()
    {
        $new_deals = Post::query()
            ->join('users', 'posts.post_by', '=', 'users.id')
            ->where('posts.status', 1)
            ->orderBy('posts.created_at', 'desc')
            ->paginate(10);

        $attachBadges = function ($deals) {
            foreach ($deals as $deal) {

                $author = $deal->user; // Access the user object from the joined data

                if ($author) {
                    // Calculate total upvotes/downvotes for THIS author across ALL their posts
                    $authorTotalUpvotes   = $author->posts()->sum('upvotes');
                    $authorTotalDownvotes = $author->posts()->sum('downvotes');
                    $authorNetVoteCount   = $authorTotalUpvotes - $authorTotalDownvotes;

                    // Find the highest qualifying badge for this author
                    $authorBadge = Badge::where('vote-count', '<=', $authorNetVoteCount)
                        ->orderBy('vote-count', 'desc')
                        ->first();

                    // Attach the badge object to the deal for easy access in the view
                    $deal->author_badge = $authorBadge;

                    if ($authorBadge && (! $author->badge_id || $author->badge_id !== $authorBadge->id)) {
                        $oldBadgeName = $author->badge ? $author->badge->name : 'No Badge';
                        $newBadgeName = $authorBadge->name;

                        // Update the user's badge
                        $author->badge_id = $authorBadge->id;
                        $author->save();

                        // --- Send Email to the User About Badge Change ---
                        $appName = config('app.name');
                        $subject = 'Your Badge Has Changed on ' . $appName;

                        $body = "# Hello **{$author->name}**,\n\n";
                        $body .= "Congratulations! Your badge has been updated on **{$appName}**.\n\n";
                        $body .= "Your previous badge was: **{$oldBadgeName}**\n\n";
                        $body .= "Your new badge is: **{$newBadgeName}**\n\n";
                        $body .= "This change reflects your increased activity and positive contributions to our community.\n\n";
                        $body .= "Keep up the great work!\n\n";
                        $body .= "Thank you,\nThe Team at {$appName}";

                        if (send_generic_email($author->email, $subject, $body, null, null)) { // No specific URL needed here
                            Log::info("Badge change notification email dispatched to user {$author->email}");
                        } else {
                            Log::error("Failed to send badge change notification email to user {$author->email}");
                        }
                    }
                } else {
                    $deal->author_badge = null; // No badge if author not found
                }
            }
            return $deals;
        };

        $new_deals = $attachBadges($new_deals);

        return view('new_deals', compact('new_deals'));
    }

    public function popularDeals()
    {

        $settings = Setting::whereIn('key', [
            'popular_deal_upvote_weight',
            'popular_deal_downvote_weight',
            'popular_deal_comment_weight',

        ])
            ->pluck('value', 'key')
            ->toArray();

        $upvoteWeight   = (float) ($settings['popular_deal_upvote_weight'] ?? 1.0);
        $downvoteWeight = (float) ($settings['popular_deal_downvote_weight'] ?? -0.5);
        $commentWeight  = (float) ($settings['popular_deal_comment_weight'] ?? 0.8);

        $popularityFormula = DB::raw(
            // Calculate score: (upvotes * upvote weight) + (downvotes * downvote weight) + (comment count * comment weight)
            "(COALESCE(upvotes, 0) * {$upvoteWeight}) + " .
            "(COALESCE(downvotes, 0) * {$downvoteWeight}) + " .
            "(COALESCE(comment_count, 0) * {$commentWeight}) " .
            "AS popularity_score"
        );

        /* $popular_deals = Post::where('status', 1)->select('posts.*', $popularityFormula)->orderByDesc('popularity_score')->paginate(10);
         */

        $popular_deals = Post::query()
            ->join('users', 'posts.post_by', '=', 'users.id')
            ->where('posts.status', 1)
            ->select('posts.*', $popularityFormula)
            ->orderByDesc('popularity_score')
            ->paginate(10);

        $attachBadges = function ($deals) {
            foreach ($deals as $deal) {

                $author = $deal->user; // Access the user object from the joined data

                if ($author) {
                    // Calculate total upvotes/downvotes for THIS author across ALL their posts
                    $authorTotalUpvotes   = $author->posts()->sum('upvotes');
                    $authorTotalDownvotes = $author->posts()->sum('downvotes');
                    $authorNetVoteCount   = $authorTotalUpvotes - $authorTotalDownvotes;

                    // Find the highest qualifying badge for this author
                    $authorBadge = Badge::where('vote-count', '<=', $authorNetVoteCount)
                        ->orderBy('vote-count', 'desc')
                        ->first();

                    // Attach the badge object to the deal for easy access in the view
                    $deal->author_badge = $authorBadge;

                    if ($authorBadge && (! $author->badge_id || $author->badge_id !== $authorBadge->id)) {
                        $oldBadgeName = $author->badge ? $author->badge->name : 'No Badge';
                        $newBadgeName = $authorBadge->name;

                        // Update the user's badge
                        $author->badge_id = $authorBadge->id;
                        $author->save();

                        // --- Send Email to the User About Badge Change ---
                        $appName = config('app.name');
                        $subject = 'Your Badge Has Changed on ' . $appName;

                        $body = "# Hello **{$author->name}**,\n\n";
                        $body .= "Congratulations! Your badge has been updated on **{$appName}**.\n\n";
                        $body .= "Your previous badge was: **{$oldBadgeName}**\n\n";
                        $body .= "Your new badge is: **{$newBadgeName}**\n\n";
                        $body .= "This change reflects your increased activity and positive contributions to our community.\n\n";
                        $body .= "Keep up the great work!\n\n";
                        $body .= "Thank you,\nThe Team at {$appName}";

                        if (send_generic_email($author->email, $subject, $body, null, null)) { // No specific URL needed here
                            Log::info("Badge change notification email dispatched to user {$author->email}");
                        } else {
                            Log::error("Failed to send badge change notification email to user {$author->email}");
                        }
                    }
                } else {
                    $deal->author_badge = null; // No badge if author not found
                }
            }
            return $deals;
        };

        $popular_deals = $attachBadges($popular_deals);

        return view('popular_deals', compact('popular_deals'));
    }

    public function highlyVotedDeals()
    {
        $settings = Setting::whereIn('key', [
            'highly_voted_deal_upvote_count',

        ])
            ->pluck('value', 'key')
            ->toArray();

        $upvoteCount = (float) ($settings['highly_voted_deal_upvote_count'] ?? 50);

        $highly_voted_deals = Post::query()
            ->join('users', 'posts.post_by', '=', 'users.id')
            ->where('posts.status', 1)
            ->where('posts.upvotes', '>=', $upvoteCount)
            ->select('posts.*', 'users.is_verified AS user_is_verified')
            ->orderBy('posts.upvotes', 'desc')
            ->paginate(10);

        $attachBadges = function ($deals) {
            foreach ($deals as $deal) {

                $author = $deal->user; // Access the user object from the joined data

                if ($author) {
                    // Calculate total upvotes/downvotes for THIS author across ALL their posts
                    $authorTotalUpvotes   = $author->posts()->sum('upvotes');
                    $authorTotalDownvotes = $author->posts()->sum('downvotes');
                    $authorNetVoteCount   = $authorTotalUpvotes - $authorTotalDownvotes;

                    // Find the highest qualifying badge for this author
                    $authorBadge = Badge::where('vote-count', '<=', $authorNetVoteCount)
                        ->orderBy('vote-count', 'desc')
                        ->first();

                    // Attach the badge object to the deal for easy access in the view
                    $deal->author_badge = $authorBadge;

                    if ($authorBadge && (! $author->badge_id || $author->badge_id !== $authorBadge->id)) {
                        $oldBadgeName = $author->badge ? $author->badge->name : 'No Badge';
                        $newBadgeName = $authorBadge->name;

                        // Update the user's badge
                        $author->badge_id = $authorBadge->id;
                        $author->save();

                        // --- Send Email to the User About Badge Change ---
                        $appName = config('app.name');
                        $subject = 'Your Badge Has Changed on ' . $appName;

                        $body = "# Hello **{$author->name}**,\n\n";
                        $body .= "Congratulations! Your badge has been updated on **{$appName}**.\n\n";
                        $body .= "Your previous badge was: **{$oldBadgeName}**\n\n";
                        $body .= "Your new badge is: **{$newBadgeName}**\n\n";
                        $body .= "This change reflects your increased activity and positive contributions to our community.\n\n";
                        $body .= "Keep up the great work!\n\n";
                        $body .= "Thank you,\nThe Team at {$appName}";

                        if (send_generic_email($author->email, $subject, $body, null, null)) { // No specific URL needed here
                            Log::info("Badge change notification email dispatched to user {$author->email}");
                        } else {
                            Log::error("Failed to send badge change notification email to user {$author->email}");
                        }
                    }
                } else {
                    $deal->author_badge = null; // No badge if author not found
                }
            }
            return $deals;
        };

        $highly_voted_deals = $attachBadges($highly_voted_deals);

        return view('highly_voted_deals', compact('highly_voted_deals'));
    }

    public function index()
    {
        return view('create_deal');
    }

    public function edit($id)
    {
        $post = Post::find($id);
        return view('edit_deal', compact('post'));
    }

    public function view_deal($id)
    {
        $post = Post::with('subscriptions')->find($id);
        $user = Auth::user();
        // Log::info($post);
        $vote_type = "";
        $reported  = false;
        if ($user != null) {
            $vote = Vote::where("post_id", $id)
                ->where("user_id", $user->id)
                ->first();

            if ($vote) {
                $vote_type = $vote->vote_type;
            }

            $existingReport = Report::where('user_id', $user->id)
                ->where('reportable_type', Post::class)
                ->where('reportable_id', $post->id)
                ->where('status', 'pending')
                ->first();

            if ($existingReport) {
                $reported = true;
            }
        }

        return view('single-deal', compact('post', "vote_type", 'reported'));
    }

    public function view_deal_title(Post $post) // Laravel automatically injects the Post model
    {
        // $post is already loaded by its slug, no need for findOrFail()
        $post->load('subscriptions');
        $user      = Auth::user();
        $vote_type = "";
        $reported  = false;

        if ($user) {
            $vote = Vote::where("post_id", $post->id) // Use $post->id for vote lookup
                ->where("user_id", $user->id)
                ->first();

            if ($vote) {
                $vote_type = $vote->vote_type;
            }

            $existingReport = Report::where('user_id', $user->id)
                ->where('reportable_type', Post::class)
                ->where('reportable_id', $post->id)
                ->where('status', 'pending')
                ->first();

            if ($existingReport) {
                $reported = true;
            }

        }

        return view('single-deal', compact('post', "vote_type", 'reported'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'description'     => 'required',
            'link'            => 'nullable|url',
            'category'        => 'required|string|max:255',
            'expiration_date' => 'nullable|date|after_or_equal:today',
            'store'           => 'nullable|string|max:255',
            'image'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $imagePath = null;
        // if ($request->hasFile('image')) {
        //     $path = $request->file('image')->store('posts', 'public');
        //     $imagePath = Storage::url($path);
        // }
        if ($request->hasFile('image')) {
            // Store the image in the 'posts' folder under the 'public' disk
            $path = $request->file('image')->store('posts', 'public');

            // Get the URL to the stored file
            $imagePath = Storage::url($path);

            // Get the full path to the file on the local filesystem
            $fullPath = storage_path('app/public/' . $path);

            // Set the permissions of the file to 0755
            chmod($fullPath, 0755);
        }

        if (isset($validated['expiration_date'])) {
            $expiration_date = Carbon::parse($validated['expiration_date']);
        } else {
            $expiration_date = null; // Set to null if not provided
        }

        $post = Post::create([
            'title'           => $validated['title'],
            'description'     => $validated['description'],
            'link'            => $validated['link'] ?? null,
            'category'        => $validated['category'],
            'image'           => $imagePath,
            'posted_at'       => now(),
            'expiration_date' => $expiration_date,
            'store'           => $validated['store'],
            'post_by'         => auth()->id(), // link to current logged-in user
        ]);

        event(new PostCreated($post));

        $appName = config('app.name');
        $subject = 'Your New Deal Has Been Created on ' . $appName;

                                                  // Access the user's name via the relationship
        $user     = auth()->user();               // Get the currently logged-in user
        $userName = $user ? $user->name : 'User'; // Default to "User" if no user is logged in.

        $body = "# Hello **{$userName}**,\n\n"; // Personalize with author's name
        $body .= "Congratulations! Your new deal titled **\"{$post->title}\"** (ID: {$post->id}) has been successfully created on **{$appName}**.\n\n";
        $body .= "You can view your deal here: " . url('/deals/' . $post->id . '?title=' . str_replace(' ', '-', $post->title)) . "\n\n";
        $body .= "Thank you for contributing to our community!\n\n";
        $body .= "The Team at {$appName}";

        $dealUrl       = '/deals/' . $post->id . '?title=' . str_replace(' ', '-', $post->title);
        $buttonFullUrl = url($dealUrl);
        if (auth()->user() && auth()->user()->email) {
            send_generic_email(auth()->user()->email, $subject, $body, null, null);
        }
        return redirect()->route('create-deals')->with('success', 'Post created successfully!');
    }

    public function update(Request $request)
    {
        $post = Post::with('user')->findOrFail($request->id);

        $originalTitle          = $post->title;
        $originalDescription    = $post->description;
        $originalLink           = $post->link;
        $originalCategory       = $post->category;
        $originalExpirationDate = $post->expiration_date;
        $originalStore          = $post->store;
        $originalImage          = $post->image;

        $validated = $request->validate([
            'title'           => 'required|string|max:255',
            'description'     => 'required',
            'link'            => 'nullable|url',
            'category'        => 'required|string|max:255',
            'expiration_date' => 'nullable|date|after_or_equal:today',
            'store'           => 'nullable|string|max:255',
            'image'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        //$post = Post::find($request->id);
        $imagePath = null;
        // Delete old image if new one is uploaded
        if ($request->hasFile('image')) {
            Log::info("image");

            if ($post->image && Storage::disk('public')->exists(str_replace('/storage/', '', $post->image))) {
                Storage::disk('public')->delete(str_replace('/storage/', '', $post->image));
            }

            // $path = $request->file('image')->store('posts', 'public');
            // $imagePath = Storage::url($path); // update image path

            // Store the image in the 'posts' folder under the 'public' disk
            $path = $request->file('image')->store('posts', 'public');

            // Get the URL to the stored file
            $imagePath = Storage::url($path);

            // Get the full path to the file on the local filesystem
            $fullPath = storage_path('app/public/' . $path);

            // Set the permissions of the file to 0755
            chmod($fullPath, 0755);
        }

        if (isset($validated['expiration_date'])) {
            $post->expiration_date = Carbon::parse($validated['expiration_date']);
        } else {
            $post->expiration_date = null; // Set to null if not provided
        }

        $post->store       = $validated['store'];
        $post->title       = $validated['title'];
        $post->description = $validated['description'];
        $post->link        = $validated['link'] ?? null;
        $post->category    = $validated['category'];
        $post->image       = $imagePath == null && $originalImage != null ? $originalImage : $imagePath;
        $post->posted_at   = now();
        $post->save();

        $appName = config('app.name');
        $subject = 'Your Deal Has Been Updated on ' . $appName;

        $body = "# Hello **{$post->user->name}**,\n\n";
        $body .= "This is a notification to confirm that your deal titled **\"{$post->title}\"** (ID: {$post->id}) has been updated successfully on **{$appName}**.\n\n";
        $body .= "Here are some of the details that may have changed:\n\n";

        $changesMade = false;
        if ($post->title !== $originalTitle) {
            $body .= "* **Title:** From `{$originalTitle}` to `{$post->title}`\n";
            $changesMade = true;
        }
        if ($post->description !== $originalDescription) {
            $body .= "* **Description:** Updated\n";
            $changesMade = true;
        }
        if ($post->link !== $originalLink) {
            $body .= "* **Link:** Updated\n";
            $changesMade = true;
        }
        if ($post->category !== $originalCategory) {
            $body .= "* **Category:** From `{$originalCategory}` to `{$post->category}`\n";
            $changesMade = true;
        }
        if (($post->expiration_date ? $post->expiration_date->format('Y-m-d') : null) !== ($originalExpirationDate ? $originalExpirationDate->format('Y-m-d') : null)) {
            $body .= "* **Expiration Date:** From `" . ($originalExpirationDate ? $originalExpirationDate->format('Y-m-d') : 'N/A') . "` to `" . ($post->expiration_date ? $post->expiration_date->format('Y-m-d') : 'N/A') . "`\n";
            $changesMade = true;
        }
        if ($post->store !== $originalStore) {
            $body .= "* **Store:** From `" . ($originalStore ?? 'N/A') . "` to `" . ($post->store ?? 'N/A') . "`\n";
            $changesMade = true;
        }
        if ($post->image !== $originalImage) {
            $body .= "* **Image:** Updated\n";
            $changesMade = true;
        }

        if (! $changesMade) {
            $body .= "No specific content changes detected that would warrant listing.\n";
        }

        // Construct the dynamic deal URL for the button
        $dealUrl = '/deals/' . $post->id . '?title=' . str_replace(' ', '-', $post->title);

        // Using the full URL for the button
        $buttonFullUrl = url($dealUrl);

        $body .= "If you did not make these changes, please contact our support team immediately at [info@buyme.lk].\n\n";

        if ($post->user && $post->user->email) {
            send_generic_email($post->user->email, $subject, $body, $buttonFullUrl, "View Your Deal");
        }

        return redirect()->route('my-deals')->with('success', 'Post updated successfully!');
    }
    public function vote(Request $request)
    {
        $user     = Auth::user();
        $voteType = $request->input('vote_type');
        $postId   = $request->input('post_id');

        if ($voteType && $postId) {
            $post = Post::with('user')->find($postId);

            if ($post) {
                $existingVote = Vote::where('user_id', $user->id)
                    ->where('post_id', $postId)
                    ->first();

                if (! $existingVote) {
                    if ($voteType === 'up') {
                        $post->increment('upvotes');
                    } elseif ($voteType === 'down') {
                        $post->increment('downvotes');
                    }

                    Vote::create([
                        'user_id'   => $user->id,
                        'post_id'   => $postId,
                        'vote_type' => $voteType,
                    ]);

                    event(new PostVoted($post));

                    $response = [
                        "error"     => false,
                        "message"   => "Vote recorded successfully.",
                        "upvotes"   => $post->upvotes,
                        "downvotes" => $post->downvotes,
                    ];

                    $this->sendVoteNotificationEmail($post, $user, $voteType, 'new');
                } else {
                    if ($existingVote->vote_type !== $voteType) {

                        if ($existingVote->vote_type === 'up') {
                            $post->decrement('upvotes');
                            $post->increment('downvotes');
                        } elseif ($existingVote->vote_type === 'down') {
                            $post->decrement('downvotes');
                            $post->increment('upvotes');
                        }

                        $existingVote->vote_type = $voteType;
                        $existingVote->save();

                        $response = [
                            "error"     => false,
                            "message"   => "Vote changed successfully!",
                            "upvotes"   => $post->upvotes,
                            "downvotes" => $post->downvotes,
                        ];

                        $this->sendVoteNotificationEmail($post, $user, $voteType, 'changed');
                    } else {
                        $response = [
                            "error"     => false,
                            "message"   => "You have already voted.",
                            "upvotes"   => $post->upvotes,
                            "downvotes" => $post->downvotes,
                        ];
                    }
                }
            } else {
                $response = [
                    "error"   => true,
                    "message" => "Post not found.",
                ];
            }
            return response()->json($response);
        } else {
            $response = [
                "error"   => true,
                "message" => "Missing vote type or post ID.",
            ];
            return response()->json($response);
        }
    }

    private function sendVoteNotificationEmail(Post $post, $voter, string $voteType, string $actionType): void
    {
        $postCreator = $post->user; // Get the post creator (eager loaded)

        // Don't send email if the voter is the post creator themselves,
        // or if the creator doesn't have an email.
        if (! $postCreator || ! $postCreator->email || $postCreator->id === $voter->id) {
            Log::info("Skipping vote notification email for post ID: {$post->id}. Creator not found, no email, or creator is voter.");
            return;
        }

        $appName = config('app.name');
        $itemUrl = url('deals/' . $post->id . '?title=' . str_replace(' ', '-', $post->title ?? ''));

        $subject   = 'Your Deal Received a ' . ucfirst($voteType) . 'vote on ' . $appName;
        $voterName = $voter->name; // Name of the user who voted

        $body = "# Hello **{$postCreator->name}**,\n\n";

        if ($actionType === 'new') {
            $body .= "Great news! Your deal, **\"{$post->title}\"**, has received a new **{$voteType}vote** from **{$voterName}**.\n\n";
        } else { // actionType is 'changed'
            $body .= "An update on your deal, **\"{$post->title}\"**: it has received a **{$voteType}vote** from **{$voterName}**, changing their previous vote.\n\n";
        }

        $body .= "This indicates active engagement with your content on **{$appName}**.\n\n";
        $body .= "Current Upvotes: {$post->upvotes}\n";
        $body .= "Current Downvotes: {$post->downvotes}\n\n";
        $body .= "View your deal: [{$post->title}]({$itemUrl})\n\n";
        $body .= "Keep up the great work!\n\n";
        $body .= "Thank you,\nThe Team at {$appName}";

        if (send_generic_email($postCreator->email, $subject, $body, null, null)) {
            Log::info("Vote notification email dispatched to post creator {$postCreator->email} for post ID: {$post->id}");
        } else {
            Log::error("Failed to send vote notification email to post creator {$postCreator->email} for post ID: {$post->id}");
        }
    }

    public function report(Request $request, Post $post)
    {
        $validator = Validator::make($request->all(), [
            'reason' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed.',
                'errors'  => $validator->errors(),
            ], 422); // Unprocessable Entity
        }

        $user = Auth::user();

        $post->load('user');

        $existingReport = Report::where('user_id', $user->id)
            ->whereMorphedTo('reportable', $post)
            ->first();

        if ($existingReport) {
            return response()->json([
                'success' => false,
                'message' => 'You have already reported this deal.',
            ], 409);
        }

        try {
            $report          = new Report();
            $report->user_id = $user->id;
            $report->reason  = $request->input('reason'); // Get reason from request

                                                     // Associate the report with the post using the polymorphic relationship
            $report->reportable()->associate($post); // Sets reportable_type and reportable_id

            $report->save();

            if ($report->reportable_type == "App\Models\Post") {
                $post->user->increment('post_report_count');
            }

            $post->increment('reported_count');

            // Optional: Log the report
            Log::info("Post {$post->id} reported by user {$user->id}. Reason: {$report->reason}");

            $appName     = config('app.name');
            $viewDealUrl = url('deals/' . $post->id . '?title=' . str_replace(' ', '-', $post->title));
            $reasonText  = $report->reason ?? 'No reason provided';

            // --- Send Email to the User Who Created the Post ---
            $subjectPostCreator = 'Your Deal Has Been Reported on ' . $appName;
            $bodyPostCreator    = "# Hello **" . ($post->user->name ?? 'User') . "**,\n\n";
            $bodyPostCreator .= "We are writing to inform you that your deal titled **\"{$post->title}\"** (ID: {$post->id}) has been reported by a user on **{$appName}**.\n\n";
            $bodyPostCreator .= "The reason provided for the report is: `{$reasonText}`\n\n";
            $bodyPostCreator .= "Please be aware that our moderation team will review this report and take appropriate action if necessary.\n\n";
            $bodyPostCreator .= "If you have any questions, please contact our support team at [info@buyme.lk].\n\n";
            $bodyPostCreator .= "Thank you,\nThe Team at {$appName}";

            if ($post->user && $post->user->email) {
                if (send_generic_email($post->user->email, $subjectPostCreator, $bodyPostCreator, null, null)) {
                    Log::info("Report notification email dispatched to post creator {$post->user->email} for report ID: {$report->id}");
                } else {
                    Log::error("Failed to send report notification email to post creator {$post->user->email} for report ID: {$report->id}");
                }
            } else {
                Log::warning("Post {$post->id} reported, but no author email found to send notification.");
            }

            // Return a success JSON response
            return response()->json([
                'success' => true,
                'message' => 'Deal reported successfully! Thank you for your feedback.',
                // 'total_reports_for_post' => $post->reports()->count(), // Requires a 'reports' relationship on Post model
            ]);
        } catch (\Exception $e) {
            Log::error("Error creating report for post {$post->id} by user {$user->id}: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to submit report.',
            ], 500);
        }
    }

    public function search(Request $request)
    {
        // --- Get filters from the request ---
        $search   = $request->input('search');
        $category = $request->input('category');
        // --- Build the query for the main deals list
        $query = Post::where('status', 1);

        // --- Apply search filter if a search term is present ---
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('link', 'like', '%' . $search . '%');
            });
        }

        if ($category && $category !== '') {
            $query->where('category', $category);
        }

        $query->orderBy('created_at', 'desc');

        $search_deals = $query->paginate(10)->withQueryString();

        foreach ($search_deals as $deal) {

            $author = $deal->user; // Access the user object from the joined data

            if ($author) {
                // Calculate total upvotes/downvotes for THIS author across ALL their posts
                $authorTotalUpvotes   = $author->posts()->sum('upvotes');
                $authorTotalDownvotes = $author->posts()->sum('downvotes');
                $authorNetVoteCount   = $authorTotalUpvotes - $authorTotalDownvotes;

                // Find the highest qualifying badge for this author
                $authorBadge = Badge::where('vote-count', '<=', $authorNetVoteCount)
                    ->orderBy('vote-count', 'desc')
                    ->first();

                // Attach the badge object to the deal for easy access in the view
                $deal->author_badge = $authorBadge;

                if ($authorBadge && (! $author->badge_id || $author->badge_id !== $authorBadge->id)) {
                    $oldBadgeName = $author->badge ? $author->badge->name : 'No Badge';
                    $newBadgeName = $authorBadge->name;

                    // Update the user's badge
                    $author->badge_id = $authorBadge->id;
                    $author->save();

                    // --- Send Email to the User About Badge Change ---
                    $appName = config('app.name');
                    $subject = 'Your Badge Has Changed on ' . $appName;

                    $body = "# Hello **{$author->name}**,\n\n";
                    $body .= "Congratulations! Your badge has been updated on **{$appName}**.\n\n";
                    $body .= "Your previous badge was: **{$oldBadgeName}**\n\n";
                    $body .= "Your new badge is: **{$newBadgeName}**\n\n";
                    $body .= "This change reflects your increased activity and positive contributions to our community.\n\n";
                    $body .= "Keep up the great work!\n\n";
                    $body .= "Thank you,\nThe Team at {$appName}";

                    if (send_generic_email($author->email, $subject, $body, null, null)) { // No specific URL needed here
                        Log::info("Badge change notification email dispatched to user {$author->email}");
                    } else {
                        Log::error("Failed to send badge change notification email to user {$author->email}");
                    }
                }
            } else {
                $deal->author_badge = null; // No badge if author not found
            }
        }

        //$search_deals = $attachBadges($search_deals);

        $banner = Setting::whereIn('key', [
            'top_banner',
        ])
            ->pluck('value', 'key')
            ->toArray();

        return view('search_deals', compact('search_deals', 'banner'));
    }
}
