<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CheckForMaintenanceMode
{
    public function handle($request, Closure $next)
    {
        // Bakım modu açık mı kontrol et
        $maintenance = DB::table('settings')->where('key', 'maintenance_mode')->value('value');

        if ($maintenance !== 'on') {
            return $next($request);
        }

        // Admin guard ile giriş yapıldıysa => geç
        if (Auth::guard('admin')->check()) {
            return $next($request);
        }

        // Login sayfası GET/POST izinli
        if (
            $request->is('login') ||
            $request->routeIs('login') ||
            $request->routeIs('login.submit')
        ) {
            return $next($request);
        }

        // Diğer her şey => bakım sayfası
        return response()->view('maintenance');
    }
}
