<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserStatus
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Check if the user is authenticated and has a status of 1
        if (Auth::check() && Auth::user()->status !== 1) {
            // Log out the user if status is not 1
            Auth::logout();

            // Redirect to login with an error message
            return redirect()->route('login')->withErrors([
                'email' => 'Your account is inactive or banned. Please contact support.',
            ]);
        }

        return $next($request);
    }
}
