<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    //

    public function popularDeals()
    {

        $settings = Setting::whereIn('key', [
            'popular_deal_upvote_weight',
            'popular_deal_downvote_weight',
            'popular_deal_comment_weight',

        ])
            ->pluck('value', 'key')
            ->toArray();

        return view('backend.settings/popular_deals', compact('settings'));
    }

    public function update(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'popular_deal_upvote_weight' => 'nullable|numeric',
            'popular_deal_downvote_weight' => 'nullable|numeric',
            'popular_deal_comment_weight' => 'nullable|numeric',
        ]);



        // Update settings
        foreach ($request->all() as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        // Redirect to the popular deals settings page with a success message
        return redirect()->route('popularDeals')->with('success', 'Popular Deals settings updated successfully!');
    }
}
