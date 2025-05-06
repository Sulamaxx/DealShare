<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\Vote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

use function Laravel\Prompts\error;

class UserPostController extends Controller
{

    public function index()
    {
        return view('create_deal');
    }
    public function view_deal($id)
    {
        $post = Post::find($id);
        return view('single-deal', compact('post'));
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
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('posts', 'public');
            $imagePath = Storage::url($path);
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

    public function vote(Request $request)
    {
        if (!Auth::check()) {
            $response = [
                "error" => true,
                "message" => "You need to be logged in to vote.",
                'redirect' => "/login"
            ];
            return response()->json($response);
        }

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
}
