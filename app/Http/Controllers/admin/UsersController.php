<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class UsersController extends Controller
{
    public function codeGenerator()
    {
        return view('backend.aiapplication/codeGenerator');
    }

    public function addUser()
    {
        return view('backend.users/addUser');
    }

    // public function usersGrid()
    // {
    //     return view('backend.users/usersGrid');
    // }

    public function store(Request $request)
    {
        Log::info($request);
        // Validate the form data
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8|confirmed',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // File validation
        ]);

        // Handle image upload if any
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('public/users');
            $imagePath = Storage::url($path); // This generates a URL to access the image
            Log::info('Image Path:', [$imagePath]); // Log the generated image URL
        } else {
            $imagePath = null;
        }

        // Create new user
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'profile_photo_path' => $imagePath, // Store image URL if available
        ]);

        // Redirect to the users list page with a success message
        return redirect()->route('usersList')->with('success', 'User created successfully');
    }

    public function usersList(Request $request)
    {
        $query = User::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $users = $query->where('user_type', 'user')->orderBy('created_at', 'desc')->paginate(8);

        return view('backend.users/usersList', compact('users'));
    }

    public function viewProfile($id)
    {
        $user = User::findOrFail($id);
        return view('backend.users/viewProfile', compact('user'));
    }

    public function changeStatus($id)
    {
        $user = User::findOrFail($id);
        $user->status = $user->status == 1 ? 0 : 1;
        $user->save();

        return redirect()->back()->with('success', 'User status updated successfully!');
    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', 'User deleted successfully!');
    }


}
