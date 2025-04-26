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
        return view('backend.authentication/signin');
    }
    public function login(Request $request)
    {
  
        // Define validation rules
        $rules = [
            'email' => 'required|string',
            'password' => 'required|string',
        ];

        // Custom validation messages (optional but recommended)
        $messages = [
            'email.required' => 'Please enter your name or email.',
            'email.string' => 'Name or email must be a valid text.',
            'password.required' => 'Please enter your password.',
            'password.string' => 'Password must be a valid text.',
        ];

        // Validate the incoming request data
        $validator = Validator::make($request->all(), $rules, $messages);

        // If validation fails, return the error messages
        if ($validator->fails()) {
            if ($validator->fails()) {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Invalid login credentials.');
            }

        }

        // Attempt to authenticate the user using either name or email
        $loginIdentifier = $request->input('email');
        $credentials = [
            'password' => $request->input('password'),
        ];

        // Check if the login identifier is likely an email
        if (filter_var($loginIdentifier, FILTER_VALIDATE_EMAIL)) {
            $credentials['email'] = $loginIdentifier;
        } else {
            $credentials['name'] = $loginIdentifier;
        }

        if (Auth::attempt($credentials)) {
            // Authentication successful
            $request->session()->flash('success', 'Login successful!');
            return redirect()->intended('/dashboard'); // Redirect to the intended URL or a default route
        } else {
            // Authentication failed
            $request->session()->flash('error', 'Invalid login credentials.');
            return redirect()->back()->withInput(); // Redirect back to the login form with input
        }
    }

    public function signup()
    {
        return view('backend.authentication/signup');
    }
}
