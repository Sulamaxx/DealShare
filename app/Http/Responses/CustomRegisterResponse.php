<?php

namespace App\Http\Responses;

use Laravel\Fortify\Contracts\RegisterResponse as RegisterResponseContract;
use Illuminate\Support\Facades\Auth;

class CustomRegisterResponse implements RegisterResponseContract
{
  public function toResponse($request)
  {
    Auth::logout();

    return redirect('/approval-pending')->with('status', 'Your account has been created and is awaiting admin approval.');
  }
}
