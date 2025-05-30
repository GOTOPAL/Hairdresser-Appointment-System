<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Hairdresser;
use App\Models\Appointment;
use App\Models\Review;
use App\Models\Setting;
use Carbon\Carbon;
use App\Models\Service;
use App\Models\Notification;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function dashboard()
    {
        $userCount = \App\Models\User::count();
        $hairdresserCount = User::where('role', 'hairdresser')->count();
        $activeAppointments = \App\Models\Appointment::where('status', 'approved')->count();
        $reviewCount = \App\Models\Review::count();
        $maintenance = \App\Models\Setting::where('key', 'maintenance_mode')->value('value') === 'on';

        $recentAppointments = \App\Models\Appointment::with(['client.user', 'hairdresser.user'])
            ->latest()->take(5)->get();

        $recentReviews = \App\Models\Review::with(['appointment.client.user', 'appointment.hairdresser.user'])
            ->latest()->take(5)->get();

        $newUsers = \App\Models\User::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'userCount', 'hairdresserCount', 'activeAppointments', 'reviewCount',
            'maintenance', 'recentAppointments', 'recentReviews', 'newUsers'
        ));
    }

    public function settings()
    {
        $announcement = \App\Models\Setting::getValue('homepage_announcement') ?? '';
        $maintenance = \App\Models\Setting::getValue('maintenance_mode') ?? 'off';
        $sessionTimeout = \App\Models\Setting::getValue('session_timeout') ?? '60';

        return view('admin.settings', compact('announcement', 'maintenance', 'sessionTimeout'));
    }

    public function updateSettings(Request $request)
    {
        // Ayarları güncelle
        $settings = [
            'maintenance_mode' => $request->has('maintenance_mode') ? 'on' : 'off',
            'session_timeout' => $request->session_timeout,
        ];

        foreach ($settings as $key => $value) {
            DB::table('settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'updated_at' => now()]
            );
        }

        // Bildirim gönderimi
        if ($request->filled('notification_message')) {
            $message = $request->notification_message;
            $target = $request->target_group; // all, clients, hairdressers

            if ($target === 'clients') {
                $clients = \App\Models\User::where('role', 'client')->get();
                foreach ($clients as $user) {
                    if ($user->client) {
                        Notification::create([
                            'title' => 'Admin Duyurusu',
                            'message' => $message,
                            'is_read' => false,
                            'is_global' => true,
                            'client_id' => $user->client->id,
                        ]);
                    }
                }

            } elseif ($target === 'hairdressers') {
                $hairdressers = \App\Models\User::where('role', 'hairdresser')->get();
                foreach ($hairdressers as $user) {
                    if ($user->hairdresser) {
                        Notification::create([
                            'title' => 'Admin Duyurusu',
                            'message' => $message,
                            'is_read' => false,
                            'is_global' => true,
                            'hairdresser_id' => $user->hairdresser->id,
                        ]);
                    }
                }

            } elseif ($target === 'all') {
                $users = \App\Models\User::where('role', '!=', 'admin')->get();
                foreach ($users as $user) {
                    $client    = $user->client;
                    $hairdresser = $user->hairdresser;

                    $data = [
                        'title' => 'Admin Duyurusu',
                        'message' => $message,
                        'is_read' => false,
                        'is_global' => true,
                    ];

                    if ($hairdresser && !$client) {
                        $data['hairdresser_id'] = $hairdresser->id;
                    } elseif ($client && !$hairdresser) {
                        $data['client_id'] = $client->id;
                    } elseif ($client && $hairdresser) {
                        $data['client_id'] = $client->id; // tercih client
                    } else {
                        continue;
                    }

                    Notification::create($data);
                }
            }
        }

        return redirect()->back()->with('success', 'Ayarlar ve bildirim gönderimi tamamlandı.');
    }



    public function users(Request $request)
    {
        $query = \App\Models\User::query();

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', "%{$request->search}%")
                    ->orWhere('email', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        $users = $query->orderByDesc('created_at')->paginate(10);

        return view('admin.users', compact('users'));
    }

    public function deleteUser($id)
    {
        $user = \App\Models\User::findOrFail($id);
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'Kullanıcı silindi.');
    }



    public function appointments()
    {
        $appointments = \App\Models\Appointment::with(['client.user', 'hairdresser.user', 'service'])
            ->orderByDesc('date')
            ->orderByDesc('time')
            ->get();

        return view('admin.appointments', compact('appointments'));
    }

    public function updateAppointment(Request $request, $id)
    {
        $request->validate([
            'date' => 'required|date',
            'time' => 'required',
            'status' => 'required|in:pending,approved,rejected,completed',
        ]);

        $appointment = Appointment::findOrFail($id);
        $appointment->update($request->only('date', 'time', 'status'));

        return redirect()->route('admin.appointments')->with('success', 'Randevu güncellendi.');
    }

    public function deleteAppointment($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return redirect()->route('admin.appointments')->with('success', 'Randevu silindi.');
    }

    public function updateUser(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'role' => 'required|in:client,hairdresser',
        ]);

        $user = \App\Models\User::findOrFail($id);
        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        return redirect()->route('admin.users')->with('success', 'Kullanıcı güncellendi.');
    }

    public function reviews()
    {
        $reviews = Review::with(['appointment.client.user', 'appointment.hairdresser.user'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.reviews', compact('reviews'));
    }

    public function deleteReview($id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return redirect()->route('admin.reviews')->with('success', 'Yorum başarıyla silindi.');
    }
    public function updateReview(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $review = \App\Models\Review::findOrFail($id);
        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('admin.reviews')->with('success', 'Yorum başarıyla güncellendi.');
    }

    public function hairdressers(Request $request)
    {
        $query = \App\Models\Hairdresser::with('user');

        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $hairdressers = $query->orderByDesc('created_at')->get();

        return view('admin.hairdressers', compact('hairdressers'));
    }

    public function approveHairdresser($id)
    {
        $hd = \App\Models\Hairdresser::findOrFail($id);
        $hd->status = 'approved';
        $hd->save();

        return back()->with('success', 'Kuaför başvurusu onaylandı.');
    }

    public function rejectHairdresser($id)
    {
        $hd = \App\Models\Hairdresser::findOrFail($id);
        $hd->status = 'rejected';
        $hd->save();

        return back()->with('success', 'Kuaför başvurusu reddedildi.');
    }

    public function deleteHairdresser($id)
    {
        $hd = \App\Models\Hairdresser::findOrFail($id);
        $hd->user()->delete(); // User ile birlikte tüm ilişkili veri de gider
        $hd->delete();

        return back()->with('success', 'Kuaför başarıyla silindi.');
    }

    public function reports()
    {
        // 🔹 Son 7 gün için randevu sayısı (tarih, toplam)
        $startDate = Carbon::now()->subDays(6)->startOfDay();
        $endDate = Carbon::now()->endOfDay();

        $appointmentsPerDay = Appointment::select(DB::raw("CONVERT(date, [date]) as day"), DB::raw('COUNT(*) as total'))
            ->whereBetween('date', [$startDate, $endDate])
            ->groupBy(DB::raw("CONVERT(date, [date])"))
            ->orderBy(DB::raw("CONVERT(date, [date])"))
            ->get()
            ->pluck('total', 'day')
            ->toArray();

        // 🔹 Boş günleri sıfırla doldur
        $dateLabels = [];
        $dateData = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $dateLabels[] = Carbon::parse($date)->format('d.m');
            $dateData[] = $appointmentsPerDay[$date] ?? 0;
        }

        // 🔹 Hizmet bazlı yorumlar: toplam yorum sayısı ve ortalama puan
        $services = Service::with(['appointments.review'])
            ->get()
            ->map(function ($service) {
                $reviews = $service->appointments->pluck('review')->filter();
                $averageRating = $reviews->count() ? round($reviews->avg('rating'), 2) : 0;
                return [
                    'name' => $service->name,
                    'review_count' => $reviews->count(),
                    'average_rating' => $averageRating,
                ];
            });

        return view('admin.reports', compact('dateLabels', 'dateData', 'services'));
    }

    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);
        $user->active = !$user->active;
        $user->save();

        return redirect()->back()->with('success', 'Kullanıcı durumu başarıyla güncellendi.');
    }




    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'role' => 'required|in:client,hairdresser',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'active' => true, // varsayılan aktif
        ]);

        // Rolüne göre ilişkili tabloya kayıt
        if ($request->role === 'client') {
            $user->client()->create([]);
        } elseif ($request->role === 'hairdresser') {
            $user->hairdresser()->create([
                'status' => 'pending',
            ]);
        }

        return redirect()->route('admin.users')->with('success', 'Yeni kullanıcı başarıyla eklendi.');
    }




    public function services()
    {
        $services = \App\Models\Service::orderByDesc('created_at')->get();
        return view('admin.services', compact('services'));
    }

    public function storeService(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0', // 'default_price' değil
        ]);

        if ($request->filled('service_id')) {
            // Güncelleme
            $service = Service::findOrFail($request->service_id);
            $service->update([
                'name' => $request->name,
                'price' => $request->price, // ← burada da
            ]);
            return redirect()->route('admin.services')->with('success', 'Hizmet başarıyla güncellendi.');
        } else {
            // Yeni ekleme
            Service::create([
                'name' => $request->name,
                'price' => $request->price, // ← burada da
            ]);
            return redirect()->route('admin.services')->with('success', 'Yeni hizmet başarıyla eklendi.');
        }
    }

    public function deleteService($id)
    {
        $service = \App\Models\Service::findOrFail($id);
        $service->delete();
        return redirect()->route('admin.services')->with('success', 'Hizmet silindi.');
    }


}
