<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DealHelpfulController extends Controller
{
    public function mark(Post $deal)
    {
        if (Auth::id() !== $deal->post_by) {
            abort(403); // Only owner can mark as helpful
        }
    
        $deal->helpful_by_user = 1;
        $deal->save();
    
        return back()->with('success', 'Marked as helpful.');
    }
    
    public function unmark(Post $deal)
    {
        if (Auth::id() !== $deal->post_by) {
            abort(403); // Only owner can unmark
        }
    
        $deal->helpful_by_user = 0;
        $deal->save();
    
        return back()->with('success', 'Removed from helpful.');
    }
}
