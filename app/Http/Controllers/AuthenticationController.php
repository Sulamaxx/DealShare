<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
    
    public function signup()
    {
        return view('backend.authentication/signup');
    }
}
