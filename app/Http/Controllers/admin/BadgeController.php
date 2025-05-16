<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Badge;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;

class BadgeController extends Controller
{


    /**
     * Show the form for creating a new badge.
     *
     * @return \Illuminate\View\View
     */
    public function addBadge()
    {
        return view('backend.badges.addBadge');
    }

    /**
     * Store a newly created badge in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|unique:badges,name|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'vote-count' => 'nullable|string',
        ]);

        // Handle image upload if any
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('badges', 'public');
            $imagePath = Storage::url($path);
        } else {
            $imagePath = null;
        }

        try {
            Badge::create([
                'name' => $validated['name'],
                'description' => $validated['description'],
                'icon' => $imagePath,
                'vote-count' => $validated['vote-count'],
            ]);
            return redirect()->route('getBadges')->with('success', 'Badge created successfully!');
        } catch (\Exception $e) {
            Log::error('Error creating badge: ' . $e->getMessage());
            return redirect()->route('addBadge')->with('error', 'Failed to create badge.');
        }
    }

    public function update(Request $request, $id) // Using Route Model Binding
    {

        $badge = Badge::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255', // Name must be unique, except for the current badge
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Validate image file
            'vote-count' => 'nullable|string|max:255', // Validation for the vote-count input field
        ]);

        try {
            $data = $request->only(['name', 'description']); // Data for update

            // --- Handle Image Upload / Replacement ---
            if ($request->hasFile('image')) {
                // Delete the old image if it exists
                if ($badge->icon && Storage::disk('public')->exists($badge->icon)) {
                    Storage::disk('public')->delete($badge->icon);
                }

                // Store the new image
                $path = $request->file('image')->store('badges', 'public'); // Store in storage/app/public/badges
                $data['icon'] = 'storage/'.$path; // Update the icon path in the data to be saved
            }

             $data['vote-count'] = $validated['vote-count']; // Map input to model attribute/column

            // --- Update the Badge model with validated data ---
            $badge->update($data);

            //Session::flash('success', 'Badge updated successfully!');

            return redirect()->route('getBadges')->with('success', 'Badge created successfully!');

        } catch (Exception $e) {
             Log::error("Error updating badge {$badge->id}: " . $e->getMessage());
             //Session::flash('error', 'Failed to update badge.');
             return redirect()->route('getBadges')->with('error', 'Failed to update badge.');

        }
    }


    public function badgesList(Request $request)
    {
        $query = Badge::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
        }
        $query->select('badges.*', 'vote-count as vote_count');
        $badges = $query->orderBy('created_at', 'desc')->paginate(10);
        return view('backend.badges.badgesList', compact('badges'));
    }

    public function show($id)
    {
        $query = Badge::query();
        $query->select('badges.*', 'vote-count as vote_count');

        $badge = $query->findOrFail($id);
        return view('backend.badges.viewBadge', compact('badge'));
    }

    public function destroy($id)
    {
        $badge = Badge::findOrFail($id);
        $badge->delete();

        return back()->with('success', 'Badge deleted successfully.');
    }
}
