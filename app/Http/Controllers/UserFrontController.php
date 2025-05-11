<?php

namespace App\Http\Controllers;

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
            ->select('posts.*', 'users.is_verified AS user_is_verified')
            ->orderBy('posts.created_at', 'desc')
            ->limit(6)->get();

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
            ->limit(6)->get();

        $highly_voted_deals = Post::query()
            ->join('users', 'posts.post_by', '=', 'users.id')
            ->where('posts.status', 1)
            ->select('posts.*', 'users.is_verified AS user_is_verified')
            ->orderBy('posts.upvotes', 'desc')
            ->limit(6)->get();

        return view('index', compact('new_deals', 'popular_deals', 'highly_voted_deals'));
    }
}
