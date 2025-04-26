<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SettingsController extends Controller
{
    public function codeGenerator()
    {
        return view('backend.aiapplication/codeGenerator');
    }

    public function company()
    {
        return view('backend.settings/company');
    }
    
    public function currencies()
    {
        return view('backend.settings/currencies');
    }
    
    public function language()
    {
        return view('backend.settings/language');
    }
    
    public function notification()
    {
        return view('backend.settings/notification');
    }
    
    public function notificationAlert()
    {
        return view('backend.settings/notificationAlert');
    }
    
    public function paymentGateway()
    {
        return view('backend.settings/paymentGateway');
    }
    
    public function theme()
    {
        return view('backend.settings/theme');
    }
    
}
