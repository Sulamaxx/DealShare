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

        return view('backend.settings.popular_deals', compact('settings'));
    }

    public function highlyVotedDeals()
    {

        $settings = Setting::whereIn('key', [
            'highly_voted_deal_upvote_count',
        ])
            ->pluck('value', 'key')
            ->toArray();

        return view('backend.settings.highly_voted_deals', compact('settings'));
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

    public function highlyVotedUpdate(Request $request)
    {
        // Validate the form data
        $validated = $request->validate([
            'highly_voted_deal_upvote_count' => 'nullable|numeric',
        ]);

        // Update settings
        foreach ($request->all() as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        // Redirect to the popular deals settings page with a success message
        return redirect()->route('highlyVotedDeals')->with('success', 'Highly Voted Deals settings updated successfully!');
    }

    public function cmsSetting()
    {
        // Fetch all page content by type into a key-value array
        $data = \DB::table('page_contents')
            ->pluck('content', 'type')
            ->toArray();

        return view('backend.settings.company', compact('data'));
    }
    public function saveCMSSetting(Request $request)
    {
        $request->validate([
            'type' => 'required|in:about,guidelines,faq,terms,privacy,other',
            'content' => 'required|string',
        ]);

        // Save to DB
        \DB::table('page_contents')->updateOrInsert(
            ['type' => $request->type],
            ['content' => $request->content, 'updated_at' => now()]
        );

        return redirect()->route('cmsSetting')->with('success', ucfirst($request->type) . ' content saved.');
    }
}
