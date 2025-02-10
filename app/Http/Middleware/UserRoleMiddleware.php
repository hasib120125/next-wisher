<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class UserRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if(Auth::check() && Auth::user()->role == $role){
            if ($role == 'talent') {
                if (!auth()->user()->profile_image || auth()->user()->status == 0) {
                    if (request()->url() != asset('talent/dashboard/profile/setup')) {
                       return redirect()->route('talent.profile.setup');
                    }
                }
                if(!auth()->user()->approved_at && Route::is('talent.payout')) {
                    session()->flash('earning_disabled', true);
                    return redirect()->route('talent.profile.setup');
                }
            }
            return $next($request);
        }else{
            return redirect()->route('home');
        }
    }
}
