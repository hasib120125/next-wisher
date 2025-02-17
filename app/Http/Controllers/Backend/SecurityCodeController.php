<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SecurityCodeController extends Controller
{
    public function codeForm() {
        return Inertia::render('Auth/SecurityCode');
    }

    public function codeSubmit (Request $request) {
        $request->validate([
            'security_code' => 'required'
        ]);

        if (session('security_code') == $request->security_code) {
            session()->forget('security_code');
            return redirect()->intended(RouteServiceProvider::ADMIN_DASHBOARD);
        }
        return back()->withErrors(['security_code' => 'Invalid code']);
    }
}
