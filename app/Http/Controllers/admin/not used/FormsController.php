<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FormsController extends Controller
{

    public function form()
    {
        return view('backend.forms/form');
    }

    public function formLayout()
    {
        return view('backend.forms/formLayout');
    }

    public function formValidation()
    {
        return view('backend.forms/formValidation');
    }

    public function wizard()
    {
        return view('backend.forms/wizard');
    }

}
