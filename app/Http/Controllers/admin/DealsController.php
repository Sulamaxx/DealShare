<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Report;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class DealsController extends Controller
{
    public function codeGenerator()
    {
        return view('backend.aiapplication/codeGenerator');
    }

    public function addDeal()
    {
        return view('backend.deals.addDeal');
    }

    public function dealsList(Request $request)
    {
        $query = Post::query();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $deals = $query->orderBy('created_at', 'desc')->paginate(8);
        return view('backend.deals.dealsList', compact('deals'));
    }


    public function reportsList(Request $request)
    {
        $query = Report::query();

        $query->where('reportable_type', Post::class);

        if ($request->filled('search')) {
            $query->where('reason', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->with(['user', 'reportable'])->orderBy('created_at', 'desc')->paginate(10);
        return view('backend.deals.reportsList', compact('reports'));
    }

    public function commentReportsList(Request $request)
    {
        $query = Report::query();

        $query->where('reportable_type', Comment::class);

        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';

            // Group the OR conditions for search
            $query->where(function ($q) use ($searchTerm) {
                // Condition 1: Search by the report reason (on the 'reports' table)
                $q->where('reason', 'like', $searchTerm);

                // Condition 2: Directly join the 'comments' table and search its 'comment_text'
                // This is safe because we've already filtered reportable_type to Comment::class.
                $q->orWhereExists(function ($subQuery) use ($searchTerm) {
                    $subQuery->select(DB::raw(1)) // Select 1 for existence check
                        ->from('comments')
                        ->whereRaw('reports.reportable_id = comments.id') // Link to the reportable_id
                        ->where('comment_text', 'like', $searchTerm);
                });
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reports = $query->with(['user', 'reportable'])->orderBy('created_at', 'desc')->paginate(10);
        return view('backend.deals.commentReportList', compact('reports'));
    }

    public function viewDeal()
    {
        $post = (object) [
            'id' => 1,
            'title' => 'Amazing Deal on Laptops!',
            'description' => 'Check out this amazing deal on laptops with discounts up to 40%! Grab yours now!',
            'link' => 'https://example.com/laptop-deal',
            'upvotes' => 200,
            'downvotes' => 5,
            'posted_at' => Carbon::now(),
            'comment_count' => 4,
            'category' => 'Electronics',
            'verified_member' => 1,
            'image' => 'laptop-deal.jpg',
            'discount_text' => '40% off',
            'price_saving' => '$300',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
            'status' => 1,
            'user' => (object) [
                'name' => 'John Doe'
            ],
            'comments' => [
                (object) [
                    'user' => (object) ['name' => 'Alice'],
                    'body' => 'I just bought this laptop! The deal is amazing!',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'comments' => [
                        (object) [
                            'user' => (object) ['name' => 'Bob'],
                            'body' => 'I agree, the specs are great for the price!',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                            'comments' => [
                                (object) [
                                    'user' => (object) ['name' => 'Charlie'],
                                    'body' => 'I was skeptical, but after reading reviews, I’m going to get it!',
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                                    'comments' => [
                                        (object) [
                                            'user' => (object) ['name' => 'Dave'],
                                            'body' => 'I have been using it for a month now. Totally worth it!',
                                            'created_at' => Carbon::now(),
                                            'updated_at' => Carbon::now(),
                                        ]
                                    ]
                                ]
                            ]
                        ],
                        (object) [
                            'user' => (object) ['name' => 'Emma'],
                            'body' => 'Is the battery life really that long?',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                            'comments' => [
                                (object) [
                                    'user' => (object) ['name' => 'Frank'],
                                    'body' => 'Yes, it lasts for about 12 hours with regular use.',
                                    'created_at' => Carbon::now(),
                                    'updated_at' => Carbon::now(),
                                ]
                            ]
                        ]
                    ]
                ],
                (object) [
                    'user' => (object) ['name' => 'Daniel'],
                    'body' => 'I’m thinking of buying this. Is the delivery fast?',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'comments' => []
                ],
                (object) [
                    'user' => (object) ['name' => 'Sophia'],
                    'body' => 'Anyone knows if there is an additional discount on students?',
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                    'comments' => [
                        (object) [
                            'user' => (object) ['name' => 'Grace'],
                            'body' => 'You can get an additional 10% off with a student ID!',
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ]
                    ]
                ]
            ]
        ];


        return view('backend.deals.viewDeals', compact('post'));
    }

    public function updateStatus($id)
    {
        $deal = Post::findOrFail($id);
        $originalStatus = $deal->status;
        if ($deal->status === 0 || $deal->status === 2) {
            $deal->status = 1;
        } else {
            $deal->status = 0;
        }

        $deal->save();

        $appName = config('app.name');
        $newStatus = $deal->status;
        $subject = 'Deal Status Updated on ' . $appName;

        $dealUrl = '/view-deal/' . $deal->id . '?title=' . str_replace(' ', '-', $deal->title);

        $body = "# Hello,\\n\\n";
        $body .= "The status of a deal has been updated.\\n\\n";
        $body .= "Deal ID: {$deal->id}\\n\\n"; // Include the deal ID in the email
        if ($originalStatus != $newStatus) {
            $body .= "The status changed from " . ($originalStatus == 0 ? 'Inactive' : ($originalStatus == 1 ? 'Active' : 'Pending')) . " to " . ($newStatus == 0 ? 'Inactive' : ($newStatus == 1 ? 'Active' : 'Pending')) . ".\\n\\n";
        } else {
            $body .= "The status remains " . ($newStatus == 0 ? 'Inactive' : ($newStatus == 1 ? 'Active' : 'Pending')) . ".\\n\\n";
        }
        $body .= "You can view the deal here: " . url($dealUrl) . "\\n\\n";
        $body .= "Thank you,\\nThe Team at {$appName}";

        if ($deal->user && $deal->user->email) {
            send_generic_email($deal->user->email, $subject, $body, null, null);
        }

        return back()->with('success', 'Deal status updated successfully.');
    }

    public function updateRejectStatus($id)
    {
        $deal = Post::findOrFail($id);
        $deal->status = 2;
        $deal->save();

        $appName = config('app.name');
        $subject = 'Important: Your Deal Submission was Rejected on ' . $appName;

        $body = "# Hello **{$deal->user->name}**,\n\n"; // Personalize with author's name
        $body .= "We regret to inform you that your deal submission titled **\"{$deal->title}\"** (ID: {$deal->id}) has been **rejected** on **{$appName}**.\n\n";
        $body .= "This means your deal will not be published on our platform. This could be due to various reasons, including (but not limited to):\n\n";
        $body .= "* Violation of our community guidelines.\n";
        $body .= "* Incomplete or inaccurate information.\n";
        $body .= "* Not meeting our quality standards.\n";
        $body .= "* Duplication of an existing deal.\n\n";
        $body .= "Please review our [Community Guidelines](" . url('/pages?tab=guidelines') . ") for more information. You may consider revising your deal if applicable and submitting it again, or submitting new deals that adhere to our guidelines.\n\n";
        $body .= "If you have any questions or believe this was a mistake, please contact our support team at [info@buyme.lk].\n\n";
        $body .= "Thank you for your understanding,\nThe Team at {$appName}";

        // No button typically needed for a rejected deal notification
        $buttonUrl = null;
        $buttonText = null;

        if ($deal->user && $deal->user->email) {
            send_generic_email($deal->user->email, $subject, $body, $buttonUrl, $buttonText);
        }

        return back()->with('success', 'Deal rejected successfully.');
    }

    public function updateReportStatus($id)
    {
        $report = Report::with([
            'user', // The user who submitted the report
            'reportable',
            'reportable.user', // The user who created the reported post/comment
            'reportable.post.user' // If reportable is a comment, get its parent post's user
        ])->findOrFail($id);

        $report->status = 'reviewed';
        $report->save();

        $appName = config('app.name');

        $reportedItemTitle = '';
        $reportedItemUrl = '';
        $itemType = '';
        $reportedItemCreator = null; // Initialize reported item's creator

        // Determine the type of the reported item and get its details
        if ($report->reportable_type === Post::class) {
            $itemType = 'post';
            $reportedItemTitle = $report->reportable->title ?? 'Untitled Post';
            $reportedItemUrl = url('view-deal/' . $report->reportable->id . '?title=' . str_replace(' ', '-', $reportedItemTitle));
            $reportedItemCreator = $report->reportable->user; // Post creator
        } elseif ($report->reportable_type === Comment::class) {
            $itemType = 'comment';
            // Get the comment text snippet
            $commentTextSnippet = substr($report->reportable->comment_text ?? 'N/A', 0, 50) . (strlen($report->reportable->comment_text ?? '') > 50 ? '...' : '');

            // For comments, link to the post the comment belongs to
            if ($report->reportable->post) {
                $reportedItemUrl = url('view-deal/' . $report->reportable->post->id . '?title=' . str_replace(' ', '-', $report->reportable->post->title ?? ''));
                $reportedItemTitle = "comment on \"" . ($report->reportable->post->title ?? 'Untitled Post') . "\" (Text: \"{$commentTextSnippet}\")";
                $reportedItemCreator = $report->reportable->user; // Comment creator
            } else {
                $reportedItemUrl = url('/'); // Fallback to homepage if parent post not found
                $reportedItemTitle = "comment (Text: \"{$commentTextSnippet}\")";
                $reportedItemCreator = $report->reportable->user; // Still try to get comment creator
            }
        } else {
            // Fallback for unexpected reportable types
            $itemType = 'item';
            $reportedItemTitle = 'Unknown';
            $reportedItemUrl = url('/'); // Link to homepage or a generic reports page
            $reportedItemCreator = null; // No creator identifiable
        }

        // --- Email to the User who submitted the Report (Reporter) ---
        if ($report->user && $report->user->email) {
            $subjectToReporter = 'Update on Your Report - ' . $appName;
            $bodyToReporter = "# Hello **{$report->user->name}**,\n\n";
            $bodyToReporter .= "This is an update regarding a report you submitted on **{$appName}**.\n\n";
            $bodyToReporter .= "Your report concerning a **{$itemType}** has been **reviewed** (Report ID: {$report->id}).\n\n";
            if ($itemType !== 'item') {
                $bodyToReporter .= "Reported content: \"{$reportedItemTitle}\"\n\n";
                $bodyToReporter .= "Reason for report: `{$report->reason}`\n\n";
                $bodyToReporter .= "You can view the item here: [View Item]({$reportedItemUrl})\n\n";
            } else {
                $bodyToReporter .= "Reason for report: `{$report->reason}`\n\n";
            }
            $bodyToReporter .= "Thank you for helping us maintain a safe and positive community. We appreciate your vigilance.\n\n";
            $bodyToReporter .= "If you have any further questions, please contact our support team at [info@buyme.lk].\n\n";
            $bodyToReporter .= "The Team at {$appName}";

            if (send_generic_email($report->user->email, $subjectToReporter, $bodyToReporter, $reportedItemUrl, 'View Item')) {
                Log::info("Report status update email dispatched to reporter {$report->user->email} for report ID: {$report->id}");
            } else {
                Log::error("Failed to send report status update email to reporter {$report->user->email} for report ID: {$report->id}");
            }
        }

        // --- Email to the User who created the Reported Post/Comment (Creator) ---
        // Only send if a creator is identified and they are not the same as the reporter
        // This avoids sending redundant emails if a user reports their own content and it gets reviewed.
        if ($reportedItemCreator && $reportedItemCreator->email && $reportedItemCreator->id !== $report->user->id) {
            $subjectToCreator = 'Regarding Your Content - ' . $appName;
            $bodyToCreator = "# Hello **{$reportedItemCreator->name}**,\n\n";
            $bodyToCreator .= "This email is to inform you about a recent review concerning your **{$itemType}** on **{$appName}**.\n\n";
            $bodyToCreator .= "Your **{$itemType}** (content: \"{$reportedItemTitle}\") has been reviewed due to a user report (Report ID: {$report->id}).\n\n";
            $bodyToCreator .= "The report stated the reason: `{$report->reason}`\n\n";
            $bodyToCreator .= "While this particular report has been **reviewed**, we encourage you to ensure your content always complies with our community guidelines to maintain a positive environment. You can view your content here: [View Item]({$reportedItemUrl})\n\n";
            $bodyToCreator .= "If you have any questions or would like to discuss this further, please contact our support team at [info@buyme.lk].\n\n";
            $bodyToCreator .= "The Team at {$appName}";

            if (send_generic_email($reportedItemCreator->email, $subjectToCreator, $bodyToCreator, $reportedItemUrl, 'View Your Content')) {
                Log::info("Report review notification email dispatched to reported item creator {$reportedItemCreator->email} for report ID: {$report->id}");
            } else {
                Log::error("Failed to send report review notification email to reported item creator {$reportedItemCreator->email} for report ID: {$report->id}");
            }
        } else {
            Log::info("Report ID {$report->id} reviewed. No email sent to reported item creator (not found, no email, or creator is reporter).");
        }

        return back()->with('success', 'Report status updated successfully.');
    }

    public function show($id)
    {
        $post = Post::findOrFail($id);
        return view('backend.deals.viewDeals', compact('post'));
    }

    public function edit($id)
    {
        $deal = Post::findOrFail($id);
        return view('backend.deals.edit', compact('deal'));
    }

    public function destroy($id)
    {
        $deal = Post::with('user')->findOrFail($id);

        $dealTitle = $deal->title; // Get title before deletion for email content
        $dealId = $deal->id;
        $dealCreator = $deal->user; // Get the user who created the deal

        $deal->delete(); // Delete the deal

        Log::info("Deal {$dealId} ('{$dealTitle}') deleted.");

        // --- Send Email to the Post Creator ---
        if ($dealCreator && $dealCreator->email) { // Ensure the post creator exists and has an email
            $appName = config('app.name');
            $subject = 'Your Deal Has Been Deleted on ' . $appName;

            $dashboardUrl = url('/my-deals');

            $body = "# Hello **{$dealCreator->name}**,\n\n";
            $body .= "We are writing to inform you that your deal titled **\"{$dealTitle}\"** (ID: {$dealId}) has been deleted from **{$appName}**.\n\n";
            $body .= "It is no longer available on our platform.\n\n";
            $body .= "This deletion may have been due to moderation, expiration, or another reason. If you have questions, please contact our support team.\n\n";
            $body .= "If you have any questions, feel free to reach out to us at [info@buyme.lk].\n\n";
            $body .= "Thank you,\nThe Team at {$appName}";

            // Pass the dashboard URL as the button link
            if (send_generic_email($dealCreator->email, $subject, $body, null, null)) {
                Log::info("Deletion notification email dispatched to post creator {$dealCreator->email} for deleted deal ID: {$dealId}");
            } else {
                Log::error("Failed to send deletion notification email to post creator {$dealCreator->email} for deleted deal ID: {$dealId}");
            }
        } else {
            Log::warning("Deal {$dealId} deleted, but no creator or creator email found to send notification.");
        }

        return back()->with('success', 'Deal deleted successfully.');
    }

    public function destroyReport($id)
    {
        $report = Report::with(['user', 'reportable', 'reportable.user', 'reportable.post.user'])->findOrFail($id);

        $reporter = $report->user; // The user who submitted the report
        $reportedItemCreator = null; // Initialize reported item's creator
        $reportId = $report->id;
        $reportReason = $report->reason;

        // Determine the type of the reported item and get its details for the email
        $reportedItemTitle = '';
        $itemType = '';
        if ($report->reportable_type === Post::class) {
            $itemType = 'post';
            $reportedItemTitle = $report->reportable->title ?? 'Untitled Post';
            $reportedItemCreator = $report->reportable->user; // Post creator
        } elseif ($report->reportable_type === Comment::class) {
            $itemType = 'comment';
            // Get the comment text snippet
            $commentTextSnippet = substr($report->reportable->comment_text ?? 'N/A', 0, 50) . (strlen($report->reportable->comment_text ?? '') > 50 ? '...' : '');
            $reportedItemTitle = "comment (Text: \"{$commentTextSnippet}\")";
            // For comments, link to the post the comment belongs to
            if ($report->reportable->post) {
                $reportedItemCreator = $report->reportable->user; // Comment creator
            } else {
                $reportedItemCreator = $report->reportable->user; // Still try to get comment creator
            }
        } else {
            $itemType = 'item';
            $reportedItemTitle = 'Unknown Content';
            $reportedItemCreator = null; // No creator identifiable
        }

        $report->delete(); // Delete the report

        Log::info("Report {$reportId} deleted.");

        // --- Send Email to the User who submitted the Report (Reporter) ---
        if ($reporter && $reporter->email) {
            $appName = config('app.name');
            $subjectToReporter = 'Report Deleted - ' . $appName;

            $bodyToReporter = "# Hello **{$reporter->name}**,\n\n";
            $bodyToReporter .= "This email is to confirm that the report you submitted on **{$appName}** has been deleted.\n\n";
            $bodyToReporter .= "Report Details:\n";
            $bodyToReporter .= "- **Report ID:** {$reportId}\n";
            $bodyToReporter .= "- **Item Type:** {$itemType}\n";
            $bodyToReporter .= "- **Reported Content:** \"{$reportedItemTitle}\"\n";
            $bodyToReporter .= "- **Your Reason:** `{$reportReason}`\n\n";
            $bodyToReporter .= "This deletion may have occurred because the reported content is no longer available, the report was deemed invalid, or due to a system cleanup.\n\n";
            $bodyToReporter .= "If you have any questions, please contact our support team at [info@buyme.lk].\n\n";
            $bodyToReporter .= "Thank you,\nThe Team at {$appName}";

            if (send_generic_email($reporter->email, $subjectToReporter, $bodyToReporter, null, null)) {
                Log::info("Report deletion notification email dispatched to reporter {$reporter->email} for report ID: {$reportId}");
            } else {
                Log::error("Failed to send report deletion notification email to reporter {$reporter->email} for report ID: {$reportId}");
            }
        } else {
            Log::warning("Report {$reportId} deleted, but no reporter or reporter email found to send notification.");
        }

        // --- Send Email to the User who created the Reported Post/Comment (Creator) ---
        // Only send if a creator is identified and they are not the same as the reporter
        if ($reportedItemCreator && $reportedItemCreator->email && $reportedItemCreator->id !== $reporter->id) {
            $subjectToCreator = 'Notification: Report Deleted - ' . $appName;
            $bodyToCreator = "# Hello **{$reportedItemCreator->name}**,\n\n";
            $bodyToCreator .= "This email is to inform you that a report concerning your **{$itemType}** has been deleted.\n\n";
            $bodyToCreator .= "Report Details:\n";
            $bodyToCreator .= "- **Report ID:** {$reportId}\n";
            $bodyToCreator .= "- **Reported Content:** \"{$reportedItemTitle}\"\n";
            $bodyToCreator .= "- **Reason for report:** `{$reportReason}`\n\n";
            $bodyToCreator .= "This deletion may indicate that the report was deemed invalid, the reported content has been modified or removed, or due to a system cleanup.\n\n";
            $bodyToCreator .= "We encourage you to review our community guidelines to ensure your content aligns with our policies.\n\n";
            $bodyToCreator .= "If you have any questions, please contact our support team at [info@buyme.lk].\n\n";
            $bodyToCreator .= "Thank you,\nThe Team at {$appName}";

            if (send_generic_email($reportedItemCreator->email, $subjectToCreator, $bodyToCreator, null, null)) {
                Log::info("Report deletion notification email dispatched to reported item creator {$reportedItemCreator->email} for report ID: {$reportId}");
            } else {
                Log::error("Failed to send report deletion notification email to reported item creator {$reportedItemCreator->email} for report ID: {$reportId}");
            }
        } else {
            Log::info("Report ID {$reportId} deleted. No email sent to reported item creator (not found, no email, or creator is reporter).");
        }

        return back()->with('success', 'Report deleted successfully.');
    }

    public function deactivateDeal($report, $deal)
    {

        try {

            $deal = Post::with('user')->findOrFail($deal);
            $deal->status = 0; // Deactivate the deal
            $deal->save();

            // Find and update the report status
            $report = Report::findOrFail($report);
            $report->status = 'resolved';
            $report->save();

            $appName = config('app.name');

            // --- Send Email to Deal Creator Only ---
            if ($deal->user && $deal->user->email) {
                $subjectToCreator = 'Your Deal Has Been Deactivated - ' . $appName;
                $bodyToCreator = "# Hello **{$deal->user->name}**,\n\n";
                $bodyToCreator .= "We are writing to inform you that your deal titled **\"{$deal->title}\"** (ID: {$deal->id}) has been deactivated on **{$appName}**.\n\n";
                $bodyToCreator .= "This action was taken following a user report (Report ID: {$report->id}).\n\n";
                $bodyToCreator .= "The reported reason was: `{$report->reason}`\n\n";
                $bodyToCreator .= "We encourage you to review our community guidelines to ensure your deals comply with our policies for future posts.\n\n";
                $bodyToCreator .= "If you have any questions, please contact our support team at [info@buyme.lk].\n\n";
                $bodyToCreator .= "Thank you,\nThe Team at {$appName}";

                if (send_generic_email($deal->user->email, $subjectToCreator, $bodyToCreator, null, null)) {
                    Log::info("Deal deactivation notification email dispatched to deal creator {$deal->user->email} for deal ID: {$deal->id}");
                } else {
                    Log::error("Failed to send deal deactivation notification email to deal creator {$deal->user->email} for deal ID: {$deal->id}");
                }
            } else {
                Log::warning("Deal deactivated, but no creator or creator email found for deal ID: {$deal->id}. No notification sent.");
            }
            return back()->with('success', 'Deal deactivated successfully.');
        } catch (Exception $ex) {
            Log::error("Failed to deactivate deal ID {$deal} or update report ID {$report}. Error: " . $ex->getMessage());

            return back()->with('error', 'Deal deactivation failed.');
        }
    }

    public function deactivateComment($report, $comment)
    {

        try {

            $comment = Comment::with('user')->findOrFail($comment);
            $comment->status = 0; // Deactivate the comment
            $comment->save();

            // Find and update the report status
            $report = Report::findOrFail($report); 
            $report->status = 'resolved';
            $report->save();

            $appName = config('app.name');

            // --- Send Email to Comment Creator Only ---
            if ($comment->user && $comment->user->email) {
                $subjectToCreator = 'Your Comment Has Been Deactivated - ' . $appName;
                $commentTextSnippet = substr($comment->comment_text ?? 'N/A', 0, 50) . (strlen($comment->comment_text ?? '') > 50 ? '...' : '');

                $bodyToCreator = "# Hello **{$comment->user->name}**,\n\n";
                $bodyToCreator .= "We are writing to inform you that your comment **\"{$commentTextSnippet}\"** (ID: {$comment->id}) has been deactivated on **{$appName}**.\n\n";
                $bodyToCreator .= "This action was taken following a user report (Report ID: {$report->id}).\n\n";
                $bodyToCreator .= "The reported reason was: `{$report->reason}`\n\n";
                $bodyToCreator .= "We encourage you to review our community guidelines to ensure your comments comply with our policies for future interactions.\n\n";
                $bodyToCreator .= "If you have any questions, please contact our support team at [info@buyme.lk].\n\n";
                $bodyToCreator .= "Thank you,\nThe Team at {$appName}";

                if (send_generic_email($comment->user->email, $subjectToCreator, $bodyToCreator, null, null)) {
                    Log::info("Comment deactivation notification email dispatched to comment creator {$comment->user->email} for comment ID: {$comment->id}");
                } else {
                    Log::error("Failed to send comment deactivation notification email to comment creator {$comment->user->email} for comment ID: {$comment->id}");
                }
            } else {
                Log::warning("Comment deactivated, but no creator or creator email found for comment ID: {$comment->id}. No notification sent.");
            }

            return back()->with('success', 'Comment deactivated successfully.');
        } catch (Exception $ex) {
            Log::error("Failed to deactivate deal ID {$comment} or update report ID {$report}. Error: " . $ex->getMessage());

            return back()->with('error', 'Comment deactivation failed.');
        }
    }

    public function updateText(Request $request, Comment $comment)
    {
        // --- Validate the incoming request data ---
        $request->validate([
            'comment_text' => ['required', 'string', 'max:5000'], // Validate the updated text
        ]);

        try {
            $oldCommentText = $comment->comment_text;

            $comment->comment_text = $request->input('comment_text');
            $comment->save();

            $comment->load('user', 'post');

            $commentAuthor = $comment->user;
            $updatedByUser = Auth::user(); // The currently authenticated user who performed the action

            // Only send email if the comment author exists, has an email,
            // AND the update was NOT made by the comment author themselves (to avoid self-notification).
            if ($commentAuthor && $commentAuthor->email && $commentAuthor->id !== $updatedByUser->id) {
                $appName = config('app.name');
                $subject = 'Your Comment Was Updated on ' . ($comment->post->title ?? 'a Deal') . ' - ' . $appName;

                $viewDealUrl = url('view-deal/' . ($comment->post->id ?? 'N/A') . '?title=' . str_replace(' ', '-', $comment->post->title ?? ''));

                $body = "# Hello **{$commentAuthor->name}**,\n\n";
                $body .= "Your comment on the deal **\"{$comment->post->title}\"** has been updated.\n\n";
                $body .= "The comment was updated by: **Buyme Bargians Team**\n\n";
                $body .= "### Original Comment:\n";
                $body .= "> *\"{$oldCommentText}\"* \n\n";
                $body .= "### Updated Comment:\n";
                $body .= "> *\"{$request->input('comment_text')}\"* \n\n";
                $body .= "You can view the updated comment and the full conversation here:\n\n";
                $body .= "If you have any questions, feel free to reach out to us at [info@buyme.lk].\n\n";
                $body .= "Thank you,\nThe Team at {$appName}";

                if (send_generic_email($commentAuthor->email, $subject, $body, null, null)) {
                    Log::info("Comment update notification email dispatched to comment author {$commentAuthor->email} for comment ID: {$comment->id}");
                } else {
                    Log::error("Failed to send comment update notification email to comment author {$commentAuthor->email} for comment ID: {$comment->id}");
                }
            } else {
                Log::info("Comment ID {$comment->id} updated. No email sent to comment author (author not found, no email, or self-update).");
            }
            // --- Return a success JSON response ---
            return response()->json(['success' => true, 'message' => 'Comment updated successfully.']);
        } catch (Exception $e) {
            // --- Log the error and return an error JSON response ---
            Log::error("Error updating comment text via AJAX for comment {$comment->id}: " . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Failed to update comment.'], 500);
        }
    }

    public function update(Request $request, $id) // Using Route Model Binding
    {
        $post = Post::with('user')->findOrFail($id);

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
        //$post = Post::find($id);
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

        $body .= "\n<x-mail::button :url=\"" . $buttonFullUrl . "\">\n";
        $body .= "View Your Deal\n";
        $body .= "</x-mail::button>\n\n";

        $body .= "These changes have been made by the **Buyme Bargains Team**. If you have any questions, please contact our support team immediately at [info@buyme.lk].\n\n";
        $body .= "Thank you,\nThe Team at {$appName}";

        if ($post->user && $post->user->email) {
            send_generic_email($post->user->email, $subject, $body, null, null);
        }

        return redirect()->route('dealsList')->with('success', 'Post updated successfully!');
    }
}
