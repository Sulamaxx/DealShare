<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;


class AuthenticationController extends Controller
{
    public function forgotPassword()
    {
        return view('backend.authentication/forgotPassword');
    }

    public function signin()
    {
        if (Auth::check() && Auth::user()->user_type === 'admin') {
            return redirect('/admin');
        } else if (Auth::check()) {
            Auth::logout();
            return redirect('/login');
        }
        return view('backend.authentication/signin');
    }
    public function login(Request $request)
    {
        // Define validation rules
        $rules = [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6|max:100',
        ];

        $messages = [
            'email.required' => 'Please enter your name or email.',
            'email.string' => 'Name or email must be valid text.',
            'password.required' => 'Please enter your password.',
            'password.string' => 'Password must be valid text.',
        ];

        // Validate the request
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator) // ✅ This is important: send validation errors
                ->withInput();
        }

        // Login logic
        $loginIdentifier = $request->input('email');
        $credentials = [
            'password' => $request->input('password'),
        ];

        if (filter_var($loginIdentifier, FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $loginIdentifier;
        } else {
            $credentials['name'] = $loginIdentifier;
        }

        if (Auth::attempt($credentials)) {
            // Success
            return redirect()->intended('/admin')->with('success', 'Login successful!');
        } else {
            // Authentication failed
            return redirect()->back()
                ->withInput()
                ->withErrors(['error' => 'Invalid login credentials.']); // ✅ show error properly
        }
    }

    public function signup()
    {
        return view('backend.authentication/signup');
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/admin');
    }
}
