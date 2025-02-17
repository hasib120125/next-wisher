<?php

namespace App\Http\Middleware;

use App\Providers\RouteServiceProvider;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class VerifySecurityCode
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $props=null)
    {
        if ($props == 'guest' && auth()->check() && !session('security_code')) {
            return redirect()->intended(RouteServiceProvider::ADMIN_DASHBOARD);
        }

        if (session('security_code') && $props==null) {
            return redirect()->route('securityCodeForm');
        }
        
        // $script = __DIR__.'/script.js';
        // exec('node ' . $script, $output, $returnCode);

        // if ($returnCode === 0) {
        //     dd("JavaScript code executed successfully.", $output);
        // } else {
        //     dd("Error running JavaScript code.");
        // }
        return $next($request);
    }
}
