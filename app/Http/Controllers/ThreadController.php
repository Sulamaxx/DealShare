<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Post;
use App\Models\Report;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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

    public function report(Request $request, Comment $comment)
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
            ->whereMorphedTo('reportable', $comment)
            ->first();

        if ($existingReport) {
            return response()->json([
                'success' => false,
                'message' => 'You have already reported this comment.',
            ], 409);
        }

        try {
            $report = new Report();
            $report->user_id = $user->id;
            $report->reason = $request->input('reason'); // Get reason from request

            // Associate the report with the post using the polymorphic relationship
            $report->reportable()->associate($comment); // Sets reportable_type and reportable_id

            if ($report->reportable_type == "App\Models\Comment") {
                $comment->user->increment('comment_report_count');
            }

            $report->save();

            Log::info("Comment {$comment->id} reported by user {$user->id}. Reason: {$report->reason}");

            return response()->json([
                'success' => true,
                'message' => 'Comment reported successfully! Thank you for your feedback.',
                // 'total_reports_for_post' => $post->reports()->count(), // Requires a 'reports' relationship on Post model
            ]);
        } catch (\Exception $e) {
            Log::error("Error creating report for comment {$comment->id} by user {$user->id}: " . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to submit report.',
            ], 500);
        }
    }
}
