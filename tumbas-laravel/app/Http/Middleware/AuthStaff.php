<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthStaff
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('staff_id')) {
            return redirect()->route('login')->with('error', 'Silakan login terlebih dahulu.');
        }

        return $next($request);
    }
}
