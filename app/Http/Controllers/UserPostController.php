<?php

namespace App\Http\Controllers;

use App\Events\PostVoted;
use App\Models\Post;
use App\Models\Report;
use App\Models\Setting;
use App\Models\Vote;
use App\Models\Badge;
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
        return view('my_deals', compact('posts','badge'));
    }

    public function newDeals()
    {
        $new_deals = Post::query()
            ->join('users', 'posts.post_by', '=', 'users.id')
            ->where('posts.status', 1)
            ->select('posts.*', 'users.is_verified AS user_is_verified')
            ->orderBy('posts.created_at', 'desc')
            ->paginate(10);

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
            ->select('posts.*', 'users.is_verified AS user_is_verified')
            ->select('posts.*', $popularityFormula)
            ->orderByDesc('popularity_score')
            ->paginate(10);

        return view('popular_deals', compact('popular_deals'));
    }

    public function highlyVotedDeals()
    {
        $highly_voted_deals = Post::query()
            ->join('users', 'posts.post_by', '=', 'users.id')
            ->where('posts.status', 1)
            ->select('posts.*', 'users.is_verified AS user_is_verified')
            ->orderBy('posts.upvotes', 'desc')
            ->paginate(10);
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


        Post::create([
            'title' => $validated['title'],
            'description' => $validated['description'],
            'link' => $validated['link'] ?? null,
            'discount_text' => $validated['discount_text'] ?? null,
            'price_saving' => $validated['price_saving'] ?? null,
            'category' => $validated['category'],
            'image' => $imagePath,
            'posted_at' => now(),
            'post_by' => auth()->id(), // link to current logged-in user
        ]);

        return redirect()->route('create-deals')->with('success', 'Post created successfully!');
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'link' => 'nullable|url',
            'discount_text' => 'nullable|string|max:255',
            'price_saving' => 'nullable|string|max:255',
            'category' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);
        $post = Post::find($request->id);
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
        $post->title = $validated['title'];
        $post->description = $validated['description'];
        $post->link = $validated['link'] ?? null;
        $post->discount_text = $validated['discount_text'] ?? null;
        $post->price_saving = $validated['price_saving'] ?? null;
        $post->category = $validated['category'];
        $post->image = $imagePath;
        $post->posted_at = now();
        $post->save();

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

        return view('search_deals', compact('search_deals'));
    }
}
