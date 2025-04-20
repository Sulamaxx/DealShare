<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ComponentspageController extends Controller
{

    public function typography()
    {
        return view('backend.componentspage/typography');
    }

    public function alert()
    {
        return view('backend.componentspage/alert');
    }

    public function avatar()
    {
        return view('backend.componentspage/avatar');
    }

    public function badges()
    {
        return view('backend.componentspage/badges');
    }

    public function button()
    {
        return view('backend.componentspage/button');
    }

    public function calendar()
    {
        return view('backend.componentspage/calendar');
    }

    public function card()
    {
        return view('backend.componentspage/card');
    }

    public function carousel()
    {
        return view('backend.componentspage/carousel');
    }

    public function colors()
    {
        return view('backend.componentspage/colors');
    }

    public function dropdown()
    {
        return view('backend.componentspage/dropdown');
    }

    public function imageUpload()
    {
        return view('backend.componentspage/imageUpload');
    }

    public function list()
    {
        return view('backend.componentspage/list');
    }

    public function pagination()
    {
        return view('backend.componentspage/pagination');
    }

    public function progress()
    {
        return view('backend.componentspage/progress');
    }

    public function radio()
    {
        return view('backend.componentspage/radio');
    }

    public function starRating()
    {
        return view('backend.componentspage/starRating');
    }

    public function switch()
    {
        return view('backend.componentspage/switch');
    }

    public function tabs()
    {
        return view('backend.componentspage/tabs');
    }

    public function tags()
    {
        return view('backend.componentspage/tags');
    }

    public function tooltip()
    {
        return view('backend.componentspage/tooltip');
    }

    public function videos()
    {
        return view('backend.componentspage/videos');
    }

}
