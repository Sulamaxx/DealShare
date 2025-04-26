<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function calendarMain()
    {
        return view('backend.calendarMain');
    }

    public function chatEmpty()
    {
        return view('backend.chatEmpty');
    }

    public function chatMessage()
    {
        return view('backend.chatMessage');
    }

    public function chatProfile()
    {
        return view('backend.chatProfile');
    }

    public function email()
    {
        return view('backend.email');
    }

    public function pageError()
    {
        return view('backend.pageError');
    }

    public function faq()
    {
        return view('backend.faq');
    }

    public function gallery()
    {
        return view('backend.gallery');
    }

    public function imageUpload()
    {
        return view('backend.imageUpload');
    }

    public function kanban()
    {
        return view('backend.kanban');
    }

    public function pricing()
    {
        return view('backend.pricing');
    }

    public function starred()
    {
        return view('backend.starred');
    }

    public function termsCondition()
    {
        return view('backend.termsCondition');
    }

    public function typography()
    {
        return view('backend.typography');
    }

    public function veiwDetails()
    {
        return view('backend.veiwDetails');
    }

    public function widgets()
    {
        return view('backend.widgets');
    }

}
