<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use App\Models\Admin;
use App\Models\User;
class LoginController extends Controller
{
    public function showForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        $remember = $request->has('remember');

        // 1. Öncelikle Admin giriş denemesi yap
        if (Auth::guard('admin')->attempt($credentials, $remember)) {
            return redirect()->route('admin.dashboard');
        }

        // 2. Normal kullanıcıyı bul ve aktiflik kontrolü yap
        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Geçersiz e-posta veya şifre.');
        }

        if (!$user->active) {
            return redirect()->back()->with('error', 'Hesabınız pasif durumda. Lütfen yönetici ile iletişime geçin.');
        }

        // 3. Normal kullanıcı girişi
        if (Auth::attempt($credentials, $remember)) {
            $user = Auth::user();

            switch ($user->role) {
                case 'client':
                    return redirect()->route('client.dashboard');
                case 'hairdresser':
                    return redirect()->route('hairdresser.dashboard');
                default:
                    Auth::logout();
                    return redirect()->route('login')->with('error', 'Yetkisiz rol.');
            }
        }

        return redirect()->back()->with('error', 'Geçersiz e-posta veya şifre.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
