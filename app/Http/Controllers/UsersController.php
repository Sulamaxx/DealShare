<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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

    public function usersGrid()
    {
        return view('backend.users/usersGrid');
    }

    public function usersList()
    {
        return view('backend.users/usersList');
    }

    public function viewProfile()
    {
        return view('backend.users/viewProfile');
    }

}
