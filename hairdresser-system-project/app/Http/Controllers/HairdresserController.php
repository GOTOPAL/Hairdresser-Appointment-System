<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Service;
use App\Models\Appointment;
use App\Models\Notification;
use App\Models\Review;
use Carbon\Carbon;

class HairdresserController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        $hairdresser = $user->hairdresserProfile;

        if (!$hairdresser) {
            abort(403, 'Kuaför profili bulunamadı.');
        }

        $totalAppointments = Appointment::where('hairdresser_id', $hairdresser->id)
            ->whereMonth('date', Carbon::now()->month)
            ->count();

        $pendingAppointments = Appointment::where('hairdresser_id', $hairdresser->id)
            ->where('status', 'pending')
            ->count();

        $completedAppointments = Appointment::where('hairdresser_id', $hairdresser->id)
            ->where('status', 'completed')
            ->count();

        $averageRating = Review::whereHas('appointment', function ($q) use ($hairdresser) {
            $q->where('hairdresser_id', $hairdresser->id);
        })->avg('rating');

        $averageRating = $averageRating ? round($averageRating, 1) : 0;

        $upcomingAppointments = Appointment::where('hairdresser_id', $hairdresser->id)
            ->where('date', '>=', Carbon::today())
            ->orderBy('date')
            ->orderBy('time')
            ->limit(5)
            ->get();

        $recentReviews = Review::whereHas('appointment', function ($q) use ($hairdresser) {
            $q->where('hairdresser_id', $hairdresser->id);
        })->latest()->limit(5)->get();

        $notifications = Notification::where(function ($q) use ($hairdresser) {
            $q->where('hairdresser_id', $hairdresser->id)
                ->orWhere('is_global', true);
        })
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        return view('hairdresser.dashboard', compact(
            'totalAppointments',
            'pendingAppointments',
            'completedAppointments',
            'averageRating',
            'upcomingAppointments',
            'recentReviews',
            'notifications'
        ));
    }

    public function profile()
    {
        $user = Auth::user();
        return view('hairdresser.profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'nullable|digits:11',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'password' => 'nullable|min:6|confirmed',
            'photo' => 'nullable|url',
            'bio' => 'nullable|string|max:1000',
        ]);

        $user->name = $request->name;
        $user->phone_number = $request->phone_number;
        $user->email = $request->email;

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        $hairdresser = $user->hairdresserProfile;
        $hairdresser->photo = $request->photo;
        $hairdresser->bio = $request->bio;
        $hairdresser->save();

        return redirect()->back()->with('success', 'Profil başarıyla güncellendi.');
    }

    public function services()
    {
        $user = auth()->user();
        $hairdresser = $user->hairdresserProfile;

        if (!$hairdresser) {
            abort(404, 'Kuaför profili bulunamadı.');
        }

        $allServices = Service::all();
        $selectedServices = $hairdresser->services->pluck('id')->toArray();

        return view('hairdresser.services', compact('allServices', 'selectedServices'));
    }

    public function updateServices(Request $request)
    {
        $request->validate([
            'services' => 'array|exists:services,id'
        ]);

        $user = auth()->user();
        $hairdresser = $user->hairdresserProfile;

        $hairdresser->services()->sync($request->services);

        return redirect()->route('hairdresser.services')->with('success', 'Hizmetleriniz güncellendi.');
    }

    public function appointments(Request $request)
    {
        $user = auth()->user();
        $hairdresser = $user->hairdresserProfile;

        $appointments = $hairdresser->appointments()
            ->with(['client.user', 'service'])
            ->orderByDesc('date')
            ->orderByDesc('time')
            ->get();

        $highlightId = $request->query('highlight');

        return view('hairdresser.appointments', compact('appointments', 'highlightId'));
    }

    public function updateAppointmentStatus(Request $request, $id)
    {
        $appointment = Appointment::findOrFail($id);

        if (!in_array($request->action, ['approved', 'rejected'])) {
            return back()->withErrors(['Geçersiz işlem.']);
        }

        $appointment->status = $request->action;
        $appointment->save();

        return back()->with('success', 'Randevu durumu güncellendi.');
    }

    public function markNotificationsRead()
    {
        $hairdresser = auth()->user()->hairdresserProfile;

        if ($hairdresser) {
            Notification::where('hairdresser_id', $hairdresser->id)
                ->orWhere('is_global', true)
                ->update(['is_read' => true]);
        }

        return redirect()->back();
    }

    public function reviews()
    {
        $hairdresser = auth()->user()->hairdresserProfile;

        $appointments = Appointment::with(['client.user', 'service', 'review'])
            ->where('hairdresser_id', $hairdresser->id)
            ->whereHas('review')
            ->latest()
            ->get();

        return view('hairdresser.reviews', compact('appointments'));
    }
}
