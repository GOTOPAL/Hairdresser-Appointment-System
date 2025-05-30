<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Hairdresser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class RegisterController extends Controller
{
    public function showForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|string|digits:11|unique:users,phone_number',
            'password' => 'required|confirmed|min:6',
            'role' => 'required|in:client,hairdresser',
            'photo' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Ad Soyad alanı zorunludur.',
            'email.required' => 'E-posta adresi zorunludur.',
            'email.email' => 'Geçerli bir e-posta adresi giriniz.',
            'email.unique' => 'Bu e-posta adresi zaten kayıtlı.',
            'phone_number.required' => 'Telefon numarası zorunludur.',
            'phone_number.digits' => 'Telefon numarası 11 haneli olmalıdır.',
            'phone_number.unique' => 'Bu telefon numarası daha önce kullanılmış.',
            'password.required' => 'Şifre zorunludur.',
            'password.confirmed' => 'Şifreler eşleşmiyor.',
            'password.min' => 'Şifre en az 6 karakter olmalıdır.',
            'role.required' => 'Lütfen rol seçiniz.',
            'role.in' => 'Seçilen rol geçersiz.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        if ($user->role === 'hairdresser') {
            Hairdresser::create([
                'user_id' => $user->id,
                'status' => 'pending',
            ]);
        }

        if ($user->role === 'client') {
            \App\Models\Client::create([
                'user_id' => $user->id,
                'phone_number' => $request->phone_number,
                'photo' => $request->photo ?? null,
            ]);
        }

        Auth::login($user);

        if ($user->role === 'client') {
            return redirect()->route('client.dashboard');
        } elseif ($user->role === 'hairdresser') {
            return redirect()->route('hairdresser.dashboard');
        } elseif ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } else {
            abort(403, 'Yetkisiz erişim.');
        }
    }
}
