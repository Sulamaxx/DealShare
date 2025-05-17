<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'post_id' => "required|exists:posts,id"
        ]);

        if (Auth::user()) {
            Subscription::create([
                'user_id' => Auth::user()->id,
                'post_id' => $request->post_id
            ]);
            return redirect()->back()->with('success', 'Post subscribed successfully!');
        } else {
            return redirect()->route('login')->with('error', "Please login to enable subscription");
        }

    }
    public function destroy(Request $request)
    {
        $request->validate([
            'id' => "required|exists:subscriptions,id"
        ]);

        if (Auth::user()) {
            $subscription = Subscription::find($request->id);
            if ($subscription) {
                $subscription->delete();
                return redirect()->back()->with('success', 'Unsubscribed successfully!');
            } else {
                return redirect()->back()->with('error', 'Subscription not found.');
            }

        } else {
            return redirect()->route('login')->with('error', "Please login to enable subscription");
        }

    }
}
