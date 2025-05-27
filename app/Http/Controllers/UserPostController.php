<?php

namespace App\Http\Controllers;

use App\Events\PostCreated;
use App\Events\PostVoted;
use App\Models\Post;
use App\Models\Report;
use App\Models\Setting;
use App\Models\Vote;
use App\Models\Badge;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

use function Laravel\Prompts\error;

class UserPostController extends Controller
{
    public function myDeals()
    {
        $user = Auth::user();

        $totalUpvotes = $user->posts()->sum('upvotes');
        $totalDownvotes = $user->posts()->sum('downvotes');
        $netVoteCount = $totalUpvotes - $totalDownvotes;

        $badge = Badge::where('vote-count', '<=', $netVoteCount)
            ->orderBy('vote-count', 'desc') // Order by vote_count descending
            ->first();

        $posts = Post::where('post_by', Auth::user()->id)->paginate(10);
        return view('my_deals', compact('posts', 'badge'));
    }

    public function myActivities()
    {
        $user = Auth::user();

        $totalUpvotes = $user->posts()->sum('upvotes');
        $totalDownvotes = $user->posts()->sum('downvotes');
        $netVoteCount = $totalUpvotes - $totalDownvotes;

        $badge = Badge::where('vote-count', '<=', $netVoteCount)
            ->orderBy('vote-count', 'desc') // Order by vote_count descending
            ->first();

        //$posts = Post::where('post_by', Auth::user()->id)->paginate(10);

        $commentedPostIds = $user->comments() // Access the user's comments relationship
            ->distinct('post_id') // Get only unique post IDs
            ->pluck('post_id'); // Get a collection of just the 'post_id' values

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
                    $authorTotalUpvotes = $author->posts()->sum('upvotes');
                    $authorTotalDownvotes = $author->posts()->sum('downvotes');
                    $authorNetVoteCount = $authorTotalUpvotes - $authorTotalDownvotes;

                    // Find the highest qualifying badge for this author
                    $authorBadge = Badge::where('vote-count', '<=', $authorNetVoteCount)
                        ->orderBy('vote-count', 'desc')
                        ->first();

                    // Attach the badge object to the deal for easy access in the view
                    $deal->author_badge = $authorBadge;
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

        $upvoteWeight = (float) ($settings['popular_deal_upvote_weight'] ?? 1.0);
        $downvoteWeight = (float) ($settings['popular_deal_downvote_weight'] ?? -0.5);
        $commentWeight = (float) ($settings['popular_deal_comment_weight'] ?? 0.8);


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
                    $authorTotalUpvotes = $author->posts()->sum('upvotes');
                    $authorTotalDownvotes = $author->posts()->sum('downvotes');
                    $authorNetVoteCount = $authorTotalUpvotes - $authorTotalDownvotes;

                    // Find the highest qualifying badge for this author
                    $authorBadge = Badge::where('vote-count', '<=', $authorNetVoteCount)
                        ->orderBy('vote-count', 'desc')
                        ->first();

                    // Attach the badge object to the deal for easy access in the view
                    $deal->author_badge = $authorBadge;
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
                    $authorTotalUpvotes = $author->posts()->sum('upvotes');
                    $authorTotalDownvotes = $author->posts()->sum('downvotes');
                    $authorNetVoteCount = $authorTotalUpvotes - $authorTotalDownvotes;

                    // Find the highest qualifying badge for this author
                    $authorBadge = Badge::where('vote-count', '<=', $authorNetVoteCount)
                        ->orderBy('vote-count', 'desc')
                        ->first();

                    // Attach the badge object to the deal for easy access in the view
                    $deal->author_badge = $authorBadge;
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
        if ($user != null) {
            $vote = Vote::where("post_id", $id)
                ->where("user_id", $user->id)
                ->first();

            if ($vote) {
                $vote_type = $vote->vote_type;
            }
        }

        return view('single-deal', compact('post', "vote_type"));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'link' => 'nullable|url',
            'discount_text' => 'nullable|string|max:255',
            'price_saving' => 'nullable|string|max:255',
            'category' => 'required|string|max:255',
            'expiration_date' => 'nullable|date|after_or_equal:today', // New validation for expiration_date
            'store' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
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
            'title' => $validated['title'],
            'description' => $validated['description'],
            'link' => $validated['link'] ?? null,
            'discount_text' => $validated['discount_text'] ?? null,
            'price_saving' => $validated['price_saving'] ?? null,
            'category' => $validated['category'],
            'image' => $imagePath,
            'posted_at' => now(),
            'expiration_date' => $expiration_date,
            'store' => $validated['store'],
            'post_by' => auth()->id(), // link to current logged-in user
        ]);

        event(new PostCreated($post));

        $appName = config('app.name');
        $subject = 'Your New Deal Has Been Created on ' . $appName;

        // Access the user's name via the relationship
        $user = auth()->user(); // Get the currently logged-in user
        $userName = $user ? $user->name : 'User'; // Default to "User" if no user is logged in.

        $body = "# Hello **{$userName}**,\n\n"; // Personalize with author's name
        $body .= "Congratulations! Your new deal titled **\"{$post->title}\"** (ID: {$post->id}) has been successfully created on **{$appName}**.\n\n";
        $body .= "You can view your deal here: " . url('view-deal/' . $post->id . '?title=' . str_replace(' ', '-', $post->title)) . "\n\n";
        $body .= "Thank you for contributing to our community!\n\n";
        $body .= "The Team at {$appName}";

        $dealUrl = '/view-deal/' . $post->id . '?title=' . str_replace(' ', '-', $post->title);
        $buttonFullUrl = url($dealUrl);
        if (auth()->user() && auth()->user()->email) {
            send_generic_email(auth()->user()->email, $subject, $body, null, null);
        }
        return redirect()->route('create-deals')->with('success', 'Post created successfully!');
    }

    public function update(Request $request)
    {
        $post = Post::with('user')->findOrFail($request->id);

        $originalTitle = $post->title;
        $originalDescription = $post->description;
        $originalLink = $post->link;
        $originalDiscountText = $post->discount_text;
        $originalPriceSaving = $post->price_saving;
        $originalCategory = $post->category;
        $originalExpirationDate = $post->expiration_date;
        $originalStore = $post->store;
        $originalImage = $post->image;

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'link' => 'nullable|url',
            'discount_text' => 'nullable|string|max:255',
            'price_saving' => 'nullable|string|max:255',
            'category' => 'required|string|max:255',
            'expiration_date' => 'nullable|date|after_or_equal:today', // New validation for expiration_date
            'store' => 'nullable|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
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

        $post->store = $validated['store'];
        $post->title = $validated['title'];
        $post->description = $validated['description'];
        $post->link = $validated['link'] ?? null;
        $post->discount_text = $validated['discount_text'] ?? null;
        $post->price_saving = $validated['price_saving'] ?? null;
        $post->category = $validated['category'];
        $post->image = $imagePath;
        $post->posted_at = now();
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
        if ($post->discount_text !== $originalDiscountText) {
            $body .= "* **Discount Text:** From `" . ($originalDiscountText ?? 'N/A') . "` to `" . ($post->discount_text ?? 'N/A') . "`\n";
            $changesMade = true;
        }
        if ($post->price_saving !== $originalPriceSaving) {
            $body .= "* **Price Saving:** From `" . ($originalPriceSaving ?? 'N/A') . "` to `" . ($post->price_saving ?? 'N/A') . "`\n";
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

        if (!$changesMade) {
            $body .= "No specific content changes detected that would warrant listing.\n";
        }

        // Construct the dynamic deal URL for the button
        $dealUrl = '/view-deal/' . $post->id . '?title=' . str_replace(' ', '-', $post->title);

        // Using the full URL for the button
        $buttonFullUrl = url($dealUrl);

        $body .= "\n<x-mail::button :url=\"" . $buttonFullUrl . "\">\n"; // Use url() helper for full URL
        $body .= "View Your Deal\n";
        $body .= "</x-mail::button>\n\n";

        $body .= "If you did not make these changes, please contact our support team immediately at [info@buyme.lk].\n\n";
        $body .= "Thank you,\nThe Team at {$appName}";

        if ($post->user && $post->user->email) {
            send_generic_email($post->user->email, $subject, $body, null, null);
        }

        return redirect()->route('my-deals')->with('success', 'Post updated successfully!');
    }
    public function vote(Request $request)
    {
        $user = Auth::user();
        $voteType = $request->input('vote_type');
        $postId = $request->input('post_id');

        if ($voteType && $postId) {
            $post = Post::find($postId);

            if ($post) {
                $existingVote = Vote::where('user_id', $user->id)
                    ->where('post_id', $postId)
                    ->first();

                if (!$existingVote) {
                    if ($voteType === 'up') {
                        $post->increment('upvotes');
                    } elseif ($voteType === 'down') {
                        $post->increment('downvotes');
                    }

                    Vote::create([
                        'user_id' => $user->id,
                        'post_id' => $postId,
                        'vote_type' => $voteType,
                    ]);

                    event(new PostVoted($post));

                    $response = [
                        "error" => false,
                        "message" => "Vote recorded successfully.",
                        "upvotes" => $post->upvotes,
                        "downvotes" => $post->downvotes,
                    ];
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
                            "error" => false,
                            "message" => "Vote changed successfully!",
                            "upvotes" => $post->upvotes,
                            "downvotes" => $post->downvotes,
                        ];
                    } else {
                        $response = [
                            "error" => false,
                            "message" => "You have already voted.",
                            "upvotes" => $post->upvotes,
                            "downvotes" => $post->downvotes,
                        ];
                    }
                }
            } else {
                $response = [
                    "error" => true,
                    "message" => "Post not found.",
                ];
            }
            return response()->json($response);
        } else {
            $response = [
                "error" => true,
                "message" => "Missing vote type or post ID.",
            ];
            return response()->json($response);
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
                'errors' => $validator->errors(),
            ], 422); // Unprocessable Entity
        }

        $user = Auth::user();

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
            $report = new Report();
            $report->user_id = $user->id;
            $report->reason = $request->input('reason'); // Get reason from request

            // Associate the report with the post using the polymorphic relationship
            $report->reportable()->associate($post); // Sets reportable_type and reportable_id

            $report->save();

            if ($report->reportable_type == "App\Models\Post") {
                $post->user->increment('post_report_count');
            }

            $post->increment('reported_count');

            // Optional: Log the report
            Log::info("Post {$post->id} reported by user {$user->id}. Reason: {$report->reason}");

            // 4. Return a success JSON response
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
        $search = $request->input('search');
        $category = $request->input('category');
        // --- Build the query for the main deals list
        $query = Post::where('status', 1);

        // --- Apply search filter if a search term is present ---
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('title', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhere('link', 'like', '%' . $search . '%')
                    ->orWhere('discount_text', 'like', '%' . $search . '%');
            });
        }

        if ($category && $category !== '') {
            $query->where('category', $category);
        }

        $query->orderBy('created_at', 'desc');

        $search_deals = $query->paginate(10)->withQueryString();

        $attachBadges = function ($deals) {
            foreach ($deals as $deal) {

                $author = $deal->user; // Access the user object from the joined data

                if ($author) {
                    // Calculate total upvotes/downvotes for THIS author across ALL their posts
                    $authorTotalUpvotes = $author->posts()->sum('upvotes');
                    $authorTotalDownvotes = $author->posts()->sum('downvotes');
                    $authorNetVoteCount = $authorTotalUpvotes - $authorTotalDownvotes;

                    // Find the highest qualifying badge for this author
                    $authorBadge = Badge::where('vote-count', '<=', $authorNetVoteCount)
                        ->orderBy('vote-count', 'desc')
                        ->first();

                    // Attach the badge object to the deal for easy access in the view
                    $deal->author_badge = $authorBadge;
                } else {
                    $deal->author_badge = null; // No badge if author not found
                }
            }
            return $deals;
        };

        $search_deals = $attachBadges($search_deals);

        return view('search_deals', compact('search_deals'));
    }
}
