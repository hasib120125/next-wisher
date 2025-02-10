<?php

namespace App\Http\Middleware;

use Closure;
use App\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Contracts\Auth\MustVerifyEmail;

class VerifiedApi
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
        if (! $request->user() ||
        ($request->user() instanceof MustVerifyEmail &&
        ! $request->user()->hasVerifiedEmail())) {
        if (empty($earning)) return Response::error([__('Your email address is not verified')],[],400);
       
    }

    return $next($request);
    }
}
