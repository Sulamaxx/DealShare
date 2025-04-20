<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AiapplicationController extends Controller
{
    public function codeGenerator()
    {
        return view('backend.aiapplication/codeGenerator');
    }

    public function codeGeneratorNew()
    {
        return view('backend.aiapplication/codeGeneratorNew');
    }

    public function imageGenerator()
    {
        return view('backend.aiapplication/imageGenerator');
    }

    public function textGenerator()
    {
        return view('backend.aiapplication/textGenerator');
    }

    public function textGeneratorNew()
    {
        return view('backend.aiapplication/textGeneratorNew');
    }

    public function videoGenerator()
    {
        return view('backend.aiapplication/videoGenerator');
    }

    public function VoiceGenerator()
    {
        return view('backend.aiapplication/VoiceGenerator   ');
    }

}
