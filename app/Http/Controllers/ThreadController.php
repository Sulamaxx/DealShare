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
        $post = Post::with('user')->find($request->post_id);
        if ($post) {
            $thread = new Comment();
            $thread->post_id = $request->post_id;
            $thread->user_id = Auth::user()->id;
            $thread->comment_text = $request->comment;
            $thread->save();

            $commenterName = Auth::user()->name; // Get the name of the user who commented
            $postAuthor = $post->user;

            if ($postAuthor && $postAuthor->email && $postAuthor->id !== Auth::user()->id) { // Don't send to self
                $appName = config('app.name');
                $subject = 'New Comment on Your Deal on ' . $appName;

                $viewDealUrl = url('/deals/' . $post->id . '?title=' . str_replace(' ', '-', $post->title));

                $body = "# Hello **{$postAuthor->name}**,\n\n";
                $body .= "Great news! **{$commenterName}** has just left a new comment on your deal: **\"{$post->title}\"** on **{$appName}**.\n\n";
                $body .= "Here's the comment:\n\n";
                $body .= "> *\"{$request->comment}\"* \n\n"; // Display the actual comment
                $body .= "You can view the comment and reply directly by clicking the button below:\n\n";
                $body .= "If you have any questions, feel free to reach out to us at [info@buyme.lk].\n\n";
                $body .= "Thank you,\nThe Team at {$appName}";

                if (send_generic_email($postAuthor->email, $subject, $body, null, null)) {
                    Log::info("New comment notification email dispatched to post author {$postAuthor->email} for post ID: {$post->id}");
                } else {
                    Log::error("Failed to send new comment notification email to post author {$postAuthor->email} for post ID: {$post->id}");
                }
            }

            return response()->json(['success' => true, 'message' => "Comment added successfully"]);
        } else {
            return response()->json(data: ['success' => false, 'message' => "Something went wrong try again later"]);
        }
    }

    public function index($postId)
    {
        $comments = Comment::with('user', 'repliesRecursive')
            ->where('post_id', $postId)
            ->where('status', 1)
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

        $parentComment = null;
        if ($request->parent_id) {
            // Eager load the user who wrote the parent comment and the post itself
            $parentComment = Comment::with(['user', 'post.user'])->find($request->parent_id);
        }

        $commenterName = Auth::user()->name; // The person who just replied

        // Only send if there's a parent comment and its author exists and has an email,
        // AND the reply isn't from the parent comment author themselves.
        if ($parentComment && $parentComment->user && $parentComment->user->email && $parentComment->user->id !== Auth::user()->id) {
            $appName = config('app.name');
            $subject = 'New Reply to Your Comment on ' . $appName;

            // URL to the post where the comment/reply is located
            $viewDealUrl = url('deals/' . $comment->post_id . '?title=' . str_replace(' ', '-', $comment->post->title));

            $body = "# Hello **{$parentComment->user->name}**,\n\n";
            $body .= "Someone has replied to your comment on the deal: **\"{$comment->post->title}\"** on **{$appName}**.\n\n";
            $body .= "The original comment was: `\"{$parentComment->comment_text}\"`\n\n"; // Show original comment
            $body .= "Here's the new reply from **{$commenterName}**:\n\n";
            $body .= "> *\"{$request->comment_text}\"* \n\n"; // Show the new reply text
            $body .= "You can view the reply and join the conversation by clicking the button below:\n\n";
            $body .= "If you have any questions, feel free to reach out to us at [info@buyme.lk].\n\n";
            $body .= "Thank you,\nThe Team at {$appName}";

            if (send_generic_email($parentComment->user->email, $subject, $body, null, null)) {
                Log::info("New reply notification email dispatched to parent comment author {$parentComment->user->email} for comment ID: {$comment->id}");
            } else {
                Log::error("Failed to send new reply notification email to parent comment author {$parentComment->user->email} for comment ID: {$comment->id}");
            }
        } else {
            Log::info("Comment ID: {$comment->id} replied to. No email sent (no parent, no parent author email, or self-reply).");
        }

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
