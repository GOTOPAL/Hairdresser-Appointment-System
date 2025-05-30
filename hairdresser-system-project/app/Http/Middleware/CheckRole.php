<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle($request, Closure $next, string $role)
    {
        if (!Auth::check() || Auth::user()->role !== $role) {
            return redirect('/login')->withErrors(['Bu alana erişiminiz yok.']);
        }

        return $next($request);
    }
}
