<?php

namespace App\Http\Controllers;

use App\Models\Badge;
use App\Models\Post;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class UserFrontController extends Controller
{
    public function index()
    {
        $new_deals = Post::query()
            ->join('users', 'posts.post_by', '=', 'users.id')
            ->where('posts.status', 1)
            ->select('posts.*', 'posts.created_at AS post_created_at')
            ->orderBy('posts.created_at', 'desc')
            ->limit(6)->get();

        $settings = Setting::whereIn('key', [
            'popular_deal_upvote_weight',
            'popular_deal_downvote_weight',
            'popular_deal_comment_weight',
            'highly_voted_deal_upvote_count',

        ])
            ->pluck('value', 'key')
            ->toArray();

        $upvoteWeight = (float) ($settings['popular_deal_upvote_weight'] ?? 1.0);
        $downvoteWeight = (float) ($settings['popular_deal_downvote_weight'] ?? -0.5);
        $commentWeight = (float) ($settings['popular_deal_comment_weight'] ?? 0.8);
        $upvoteCount = (float) ($settings['highly_voted_deal_upvote_count'] ?? 50);

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
            ->select('posts.*', 'posts.created_at AS post_created_at')
            ->select('posts.*', $popularityFormula)
            ->orderByDesc('popularity_score')
            ->limit(6)->get();

        $highly_voted_deals = Post::query()
            ->join('users', 'posts.post_by', '=', 'users.id')
            ->where('posts.status', 1)
            ->where('posts.upvotes', '>=', $upvoteCount)
            ->select('posts.*', 'posts.created_at AS post_created_at')
            ->orderBy('posts.upvotes', 'desc')
            ->limit(6)->get();

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
        $popular_deals = $attachBadges($popular_deals);
        $highly_voted_deals = $attachBadges($highly_voted_deals);

        return view('index', compact('new_deals', 'popular_deals', 'highly_voted_deals'));
    }

    public function pages()
    {
        return view('pages');
    }
}
