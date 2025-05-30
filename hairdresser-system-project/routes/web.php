<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\HairdresserController;
use App\Http\Controllers\AdminController;
use App\Http\Middleware\CheckRole;
use App\Models\Setting;

/*
|--------------------------------------------------------------------------
| Yardımcı Fonksiyon: Bakım Modu Kontrolü
|--------------------------------------------------------------------------
*/
function siteIsInMaintenance() {
    $setting = Setting::first();
    return $setting && $setting->maintenance_mode;
}

/*
|--------------------------------------------------------------------------
| Anasayfa (Landing Page)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return view('index');
})->name('homepage');

/*
|--------------------------------------------------------------------------
| Giriş & Kayıt
|--------------------------------------------------------------------------
*/
Route::get('/login', function () {
    if (siteIsInMaintenance() && !(Auth::check() && Auth::user()->role === 'admin')) {
        return view('maintenance');
    }
    return app(LoginController::class)->showForm();
})->name('login.form');

Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/register', function () {
    if (siteIsInMaintenance()) {
        return view('maintenance');
    }
    return app(RegisterController::class)->showForm();
})->name('register.form');

Route::post('/register', [RegisterController::class, 'register'])->name('register');

/*
|--------------------------------------------------------------------------
| Bakım Modu Koruması Altında Tüm Route'lar
|--------------------------------------------------------------------------
*/
Route::middleware(['web', \App\Http\Middleware\CheckForMaintenanceMode::class])->group(function () {

    /*
    |--------------------------------------------------------------------------
    | Client Paneli
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', CheckRole::class . ':client'])->prefix('client')->name('client.')->group(function () {
        Route::get('/dashboard', [ClientController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [ClientController::class, 'profile'])->name('profile');
        Route::put('/profile', [ClientController::class, 'updateProfile'])->name('profile.update');
        Route::get('/appointments', [ClientController::class, 'appointments'])->name('appointments');
        Route::get('/appointments/create', [ClientController::class, 'createAppointment'])->name('appointments.create');
        Route::post('/appointments/create', [ClientController::class, 'storeAppointment'])->name('appointments.store');
        Route::put('/appointments/{id}/cancel', [ClientController::class, 'cancelAppointment'])->name('appointments.cancel');
        Route::match(['put', 'post'], '/appointments/{id}/complete', [ClientController::class, 'completeAppointment'])->name('appointments.complete');
        Route::get('/calendar', fn () => view('client.calendar'))->name('calendar'); // Takvim görünümü
        Route::get('/calendar/data', [ClientController::class, 'calendarData'])->name('calendar.data');
        Route::get('/notifications', [ClientController::class, 'notifications'])->name('notifications');
        Route::put('/notifications/{id}/read', [ClientController::class, 'markNotificationAsRead'])->name('notifications.markAsRead');
        Route::post('/notifications/mark-all-read', [ClientController::class, 'markAllNotificationsRead'])->name('notifications.markAllRead');
        Route::get('/services', [ClientController::class, 'services'])->name('services');
        Route::get('/hairdressers', [ClientController::class, 'hairdressers'])->name('hairdressers');
    });

    /*
    |--------------------------------------------------------------------------
    | Hairdresser Paneli
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth', CheckRole::class . ':hairdresser'])->prefix('hairdresser')->name('hairdresser.')->group(function () {
        Route::get('/dashboard', [HairdresserController::class, 'dashboard'])->name('dashboard');
        Route::get('/profile', [HairdresserController::class, 'profile'])->name('profile');
        Route::post('/profile', [HairdresserController::class, 'updateProfile'])->name('profile.update');
        Route::get('/services', [HairdresserController::class, 'services'])->name('services');
        Route::post('/services', [HairdresserController::class, 'updateServices'])->name('services.update');
        Route::get('/appointments', [HairdresserController::class, 'appointments'])->name('appointments');
        Route::put('/appointments/{id}/status', [HairdresserController::class, 'updateAppointmentStatus'])->name('appointments.updateStatus');
        Route::get('/reviews', [HairdresserController::class, 'reviews'])->name('reviews');
        Route::post('/notifications/mark-as-read', [HairdresserController::class, 'markNotificationsRead'])->name('notifications.markAsRead');
    });

    /*
    |--------------------------------------------------------------------------
    | Admin Paneli
    |--------------------------------------------------------------------------
    */
    Route::middleware(['auth:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');

        // 👤 Kullanıcılar
        Route::get('/users', [AdminController::class, 'users'])->name('users');
        Route::post('/users', [AdminController::class, 'storeUser'])->name('users.store');
        Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('users.update');
        Route::put('/users/{id}/toggle-status', [AdminController::class, 'toggleUserStatus'])->name('users.toggleStatus');
        Route::delete('/users/{id}', [AdminController::class, 'deleteUser'])->name('users.delete');

        // 💇 Kuaförler
        Route::get('/hairdressers', [AdminController::class, 'hairdressers'])->name('hairdressers');
        Route::post('/hairdressers/{id}/approve', [AdminController::class, 'approveHairdresser'])->name('hairdressers.approve');
        Route::post('/hairdressers/{id}/reject', [AdminController::class, 'rejectHairdresser'])->name('hairdressers.reject');
        Route::delete('/hairdressers/{id}', [AdminController::class, 'deleteHairdresser'])->name('hairdressers.delete');

        // 📅 Randevular
        Route::get('/appointments', [AdminController::class, 'appointments'])->name('appointments');
        Route::put('/appointments/{id}', [AdminController::class, 'updateAppointment'])->name('appointments.update');
        Route::delete('/appointments/{id}', [AdminController::class, 'deleteAppointment'])->name('appointments.destroy');

        // ⭐ Yorumlar
        Route::get('/reviews', [AdminController::class, 'reviews'])->name('reviews');
        Route::put('/reviews/{id}', [AdminController::class, 'updateReview'])->name('reviews.update');
        Route::delete('/reviews/{id}', [AdminController::class, 'deleteReview'])->name('reviews.delete');

        // ⚙️ Ayarlar
        Route::get('/settings', [AdminController::class, 'settings'])->name('settings');
        Route::match(['POST', 'PUT'], '/settings/update', [AdminController::class, 'updateSettings'])->name('settings.update');

        // 📈 Raporlar
        Route::get('/reports', [AdminController::class, 'reports'])->name('reports');

        // 💈 Hizmetler
        Route::get('/services', [AdminController::class, 'services'])->name('services');
        Route::post('/services', [AdminController::class, 'storeService'])->name('services.store');
        Route::get('/services/{id}/edit', [AdminController::class, 'editService'])->name('services.edit');
        Route::delete('/services/{id}', [AdminController::class, 'deleteService'])->name('services.delete');
        Route::delete('/services/bulk-delete', [AdminController::class, 'bulkDeleteServices'])->name('services.bulkDelete');
    });
});

/*
|--------------------------------------------------------------------------
| Onay Bekleyen Kuaförler İçin Bekleme Sayfası
|--------------------------------------------------------------------------
*/
Route::get('/hairdresser/waiting', function () {
    return view('hairdresser.waiting');
})->middleware(['auth', CheckRole::class . ':hairdresser'])->name('hairdresser.waiting');
