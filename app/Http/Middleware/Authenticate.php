<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Authenticate 
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }

    public function handle(Request $request, Closure $next)
{

    if (Auth::check() && !Auth::user()->is_admin) {
        return $next($request);
    }

    return redirect('')->with('error', 'Unauthorized action.');
}
}
