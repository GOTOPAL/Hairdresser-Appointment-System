<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Notification;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
      /*  // settings tablosundan session timeout süresini al
        $timeout = DB::table('settings')->where('key', 'session_timeout')->value('value');

        if ($timeout) {
            Config::set('session.lifetime', (int) $timeout); // dakika
        }

        // View composer ile tüm blade view'larına bildirimleri gönder
        View::composer('*', function ($view) {
            if (Auth::check() && Auth::user()->role === 'client') {
                $clientId = Auth::user()->client->id;

                $notifications = Notification::where('client_id', $clientId)
                    ->orderByDesc('created_at')
                    ->take(5)
                    ->get();

                $unreadCount = $notifications->where('is_read', false)->count();

                $view->with(compact('notifications', 'unreadCount'));
            }
        }); */
    }
}
