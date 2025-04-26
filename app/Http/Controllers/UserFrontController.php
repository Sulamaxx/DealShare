<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class UserFrontController extends Controller
{
    public function index()
    {
        $new_deals = Post::where('status', 1)->orderBy('created_at', 'desc')->limit(6)->get();
        return view('index', compact('new_deals'));
    }
}
