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
    public function myDeals()
    {
        $posts = Post::where('post_by', Auth::user()->id)->paginate(10);
        return view('my_deals', compact('posts'));
    }

    public function newDeals()
    {
        $new_deals = Post::where('status', 1)->orderBy('created_at', 'desc')->paginate(10);
        return view('new_deals', compact('new_deals'));
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
        $post = Post::find($id);
        $user = Auth::user();

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

            $path = $request->file('image')->store('posts', 'public');
            $imagePath = Storage::url($path); // update image path
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
