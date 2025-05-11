<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ThreadController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'comment' => 'required|string|max:1000',
        ]);
        $post = Post::find($request->post_id);
        if ($post) {
            $thread = new Comment();
            $thread->post_id = $request->post_id;
            $thread->user_id = Auth::user()->id;
            $thread->comment_text = $request->comment;
            $thread->save();

            return response()->json(['success' => true, 'message' => "Comment added successfully"]);
        } else {
            return response()->json(data: ['success' => false, 'message' => "Something went wrong try again later"]);
        }
    }

    public function index($postId)
    {
        $comments = Comment::with('user', 'repliesRecursive')
            ->where('post_id', $postId)
            ->whereNull('parent_id')
            ->get();

        return response()->json($comments);
    }

    public function storeChild(Request $request)
    {
        $request->validate([
            'post_id' => 'required|exists:posts,id',
            'comment_text' => 'required|string',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = Comment::create([
            'post_id' => $request->post_id,
            'user_id' => auth()->id(),
            'comment_text' => $request->comment_text,
            'parent_id' => $request->parent_id
        ]);

        return response()->json(['message' => 'Comment added', 'id' => $comment->id]);
    }


}
