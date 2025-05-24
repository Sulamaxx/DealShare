<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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

    public function bottomBanner()
    {

        $banner = Setting::whereIn('key', [
            'bottom_banner',
        ])
            ->pluck('value', 'key')
            ->toArray();

        return view('backend.banners.bottomBanner', compact('banner'));
    }

    public function topBanner()
    {

        $banner = Setting::whereIn('key', [
            'top_banner',
        ])
            ->pluck('value', 'key')
            ->toArray();

        return view('backend.banners.topBanner', compact('banner'));
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

    public function updateBottomBanner(Request $request) // Using Route Model Binding
    {

        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        try {
            // Find the 'bottom_banner' setting. If it doesn't exist, create it.
            $bannerSetting = Setting::firstOrCreate(
                ['key' => 'bottom_banner'],
                ['value' => null] // Default value if it's newly created
            );

            // Handle image upload
            if ($request->hasFile('image')) {
                Log::info("New image uploaded for bottom banner.");

                // Get the old image path from the setting's value
                $oldImagePath = $bannerSetting->value;

                // Check if an old image exists and delete it from public storage
                if ($oldImagePath && Storage::disk('public')->exists($oldImagePath)) {
                    Storage::disk('public')->delete($oldImagePath);
                    Log::info("Old bottom banner image deleted: {$oldImagePath}");
                }

                // Store the new image in the 'images' folder under the 'public' disk
                // The store method returns the path relative to the disk's root (e.g., 'images/new_filename.jpg').
                $path = $request->file('image')->store('images', 'public');

                // Update the setting's value with the new relative path
                $bannerSetting->value = $path;
                $bannerSetting->save();

                Log::info("Bottom banner updated to: {$path}");
            }
            // If no new image is uploaded, and the banner setting exists, its value remains unchanged.
            // If the banner setting was newly created and no image was uploaded, its value remains null.

            return redirect()->route('bottomBanner')->with('success', 'Banner updated successfully!');
        } catch (\Exception $e) {
            // Catch any exceptions, log them, and return an error response
            Log::error("Error updating bottom banner: " . $e->getMessage());
            return redirect()->route('bottomBanner')->with('error', 'Failed to update banner. Please try again.');
        }
    }

    public function updateTopBanner(Request $request) // Using Route Model Binding
    {

        $validated = $request->validate([
            'image' => 'nullable|image|mimes:jpg,jpeg,png,gif|max:2048',
        ]);

        try {
            // Find the 'top_banner' setting. If it doesn't exist, create it.
            $bannerSetting = Setting::firstOrCreate(
                ['key' => 'top_banner'],
                ['value' => null] // Default value if it's newly created
            );

            // Handle image upload
            if ($request->hasFile('image')) {
                Log::info("New image uploaded for top banner.");

                // Get the old image path from the setting's value
                $oldImagePath = $bannerSetting->value;

                // Check if an old image exists and delete it from public storage
                if ($oldImagePath && Storage::disk('public')->exists($oldImagePath)) {
                    Storage::disk('public')->delete($oldImagePath);
                    Log::info("Old top banner image deleted: {$oldImagePath}");
                }

                // Store the new image in the 'images' folder under the 'public' disk
                // The store method returns the path relative to the disk's root (e.g., 'images/new_filename.jpg').
                $path = $request->file('image')->store('images', 'public');

                // Update the setting's value with the new relative path
                $bannerSetting->value = $path;
                $bannerSetting->save();

                Log::info("Top banner updated to: {$path}");
            }
            // If no new image is uploaded, and the banner setting exists, its value remains unchanged.
            // If the banner setting was newly created and no image was uploaded, its value remains null.

            return redirect()->route('topBanner')->with('success', 'Banner updated successfully!');
        } catch (\Exception $e) {
            // Catch any exceptions, log them, and return an error response
            Log::error("Error updating bottom banner: " . $e->getMessage());
            return redirect()->route('topBanner')->with('error', 'Failed to update banner. Please try again.');
        }
    }
}
