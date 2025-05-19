<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Mail\WarningEmail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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
            $path = $request->file('image')->store('users', 'public');
            $imagePath = Storage::url($path);
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

        $users = $query->where('user_type', 'user')->where(function ($q) {
            $q->where('status', 1)->orWhere('status', 0);
        })->orderBy('created_at', 'desc')->paginate(8);

        return view('backend.users/usersList', compact('users'));
    }
    public function reportedUsersList(Request $request)
    {
        $query = User::query();

        if ($request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->where('user_type', 'user')->where(function ($q) {
            $q->where('post_report_count', '>', 0)
                ->orWhere('comment_report_count', '>', 0);
        })->where('status', 1)->orderBy('created_at', 'desc')->paginate(8);

        return view('backend.users/reportedUsersList', compact('users'));
    }

    public function bannedUsersList(Request $request)
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

        $users = $query->where('user_type', 'user')->where(function ($q) {
            $q->where('status', 2)->orWhere('status', 3);
        })->orderBy('created_at', 'desc')->paginate(8);

        return view('backend.users/bannedUsersList', compact('users'));
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

    public function sendWarningEmail($id)
    {
        $user = User::findOrFail($id);
        try {
            Mail::to($user->email)->send(new WarningEmail($user));
            return back()->with('success', 'Warning email sent to user.');
        } catch (\Exception $e) {
            Log::error('Mail Send Failed: ' . $e->getMessage());
            return back()->with('error', 'Failed to send warning email.');
        }
    }

    public function temporaryBan($id)
    {
        $user = User::findOrFail($id);
        $user->status = 2;
        $user->save();
        return back()->with('success', 'User temporarily banned.');
    }

    public function banUser($id)
    {
        $user = User::findOrFail($id);
        $user->status = 3;
        $user->save();
        return back()->with('success', 'User permanently banned.');
    }

    public function activate($id)
    {
        $user = User::findOrFail($id);
        $user->status = 1; // Set status to Active
        $user->save();

        return redirect()->back()->with('success', 'User account activated.');
    }


}
