<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\Hairdresser;
use App\Models\Service;
use App\Models\Appointment;
use App\Models\Notification;

class ClientController extends Controller
{
    public function dashboard()
    {
        $client = auth()->user()->clientProfile;

        $pendingCount = $client->appointments()->where('status', 'pending')->count();
        $approvedCount = $client->appointments()->where('status', 'approved')->count();
        $reviewCount = $client->appointments()->has('review')->count();
        $serviceCount = $client->appointments()->distinct('service_id')->count('service_id');

        $nextAppointment = $client->appointments()
            ->where('status', 'approved')
            ->orderBy('date')
            ->orderBy('time')
            ->with(['hairdresser.user', 'service'])
            ->first();

        $lastAppointments = $client->appointments()
            ->with(['review', 'service', 'hairdresser.user'])
            ->orderByDesc('date')
            ->orderByDesc('time')
            ->take(3)
            ->get();

        $notifications = Notification::where(function ($q) use ($client) {
            $q->where('client_id', $client->id)
                ->orWhere('is_global', true);
        })
            ->orderByDesc('created_at')
            ->take(10)
            ->get();

        return view('client.dashboard', compact(
            'pendingCount',
            'approvedCount',
            'reviewCount',
            'serviceCount',
            'nextAppointment',
            'lastAppointments',
            'notifications'
        ));
    }

    public function profile()
    {
        return view('client.profile');
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $user->id,
            'phone_number' => ['required','digits:11','unique:users,phone_number,' . $user->id],
            'password' => 'nullable|min:6',
            'photo' => 'nullable|url',
        ]);

        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        if ($user->client) {
            $user->client->phone = $request->phone_number;
            $user->client->photo = $request->photo ?? $user->client->photo;
            $user->client->save();
        }

        return redirect()->route('client.profile')->with('success', 'Profil bilgileri başarıyla güncellendi.');
    }

    public function createAppointment()
    {
        $hairdressers = Hairdresser::with('user')->get();
        $services = Service::all();
        return view('client.appointments.create', compact('hairdressers', 'services'));
    }

    public function storeAppointment(Request $request)
    {
        $request->validate([
            'hairdresser_id' => 'required|exists:hairdressers,id',
            'services' => 'required|array',
            'services.*' => 'exists:services,id',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
        ]);

        $client = auth()->user()->clientProfile;
        $createdAppointments = [];

        foreach ($request->services as $serviceId) {
            $appointment = Appointment::create([
                'client_id' => $client->id,
                'hairdresser_id' => $request->hairdresser_id,
                'service_id' => $serviceId,
                'date' => $request->date,
                'time' => $request->time,
                'status' => 'pending',
            ]);

            $createdAppointments[] = $appointment;
        }

        if (!empty($createdAppointments)) {
            Notification::create([
                'hairdresser_id' => $request->hairdresser_id,
                'appointment_id' => $createdAppointments[0]->id,
                'title' => 'Yeni Randevu Talebi',
                'message' => auth()->user()->name . ' isimli müşteriden yeni randevu talebi.',
            ]);
        }

        return redirect()->route('client.appointments')->with('success', 'Randevunuz oluşturuldu ve onay bekliyor.');
    }

    public function appointments()
    {
        $client = Auth::user()->clientProfile;

        $appointments = $client->appointments()
            ->with(['hairdresser.user', 'service', 'review'])
            ->orderByDesc('date')
            ->orderByDesc('time')
            ->get();

        return view('client.appointments.index', compact('appointments'));
    }

    public function completeAppointment(Request $request, $id)
    {
        $appointment = Appointment::where('id', $id)
            ->where('client_id', auth()->user()->clientProfile->id)
            ->where('status', 'approved')
            ->firstOrFail();

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|max:1000',
        ]);

        $appointment->review()->create([
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        $appointment->status = 'completed';
        $appointment->save();

        return redirect()->route('client.appointments')->with('success', 'Randevu tamamlandı ve değerlendirildi.');
    }

    public function cancelAppointment($id)
    {
        $appointment = Appointment::findOrFail($id);
        if ($appointment->status === 'pending') {
            $appointment->status = 'rejected';
            $appointment->save();
        }
        return back()->with('success', 'Randevu iptal edildi.');
    }

    public function notifications()
    {
        $clientId = auth()->user()->client->id;
        $notifications = Notification::where(function ($q) use ($clientId) {
            $q->where('client_id', $clientId)
                ->orWhere('is_global', true);
        })
            ->orderByDesc('created_at')
            ->take(5)
            ->get();

        $unreadCount = $notifications->where('is_read', false)->count();

        return response()->json([
            'notifications' => $notifications,
            'unreadCount' => $unreadCount,
        ]);
    }

    public function markNotificationAsRead($id)
    {
        $notification = Notification::where('id', $id)
            ->where(function ($q) {
                $q->where('client_id', auth()->user()->client->id)
                    ->orWhere('is_global', true);
            })->firstOrFail();

        $notification->is_read = true;
        $notification->save();

        return response()->json(['success' => true]);
    }

    public function markAllNotificationsRead()
    {
        $clientId = auth()->user()->client->id;
        Notification::where(function ($q) use ($clientId) {
            $q->where('client_id', $clientId)
                ->orWhere('is_global', true);
        })
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return redirect()->back()->with('success', 'Tüm bildirimler okundu olarak işaretlendi.');    }

    public function calendarData()
    {
        $client = auth()->user()->clientProfile;

        $appointments = $client->appointments()->with('hairdresser.user', 'service')->get();

        $events = $appointments->map(function ($appt) {
            $rawDate = str_replace([':AM', ':PM'], [' AM', ' PM'], $appt->date);
            $parsedDate = strtotime($rawDate);
            $parsedTime = strtotime($appt->time);

            if (!$parsedDate || !$parsedTime) {
                return null;
            }

            $datePart = \Carbon\Carbon::createFromTimestamp($parsedDate)->format('Y-m-d');
            $timePart = \Carbon\Carbon::createFromTimestamp($parsedTime)->format('H:i:s');
            $datetime = $datePart . 'T' . $timePart;

            return [
                'title' => $appt->service->name,
                'start' => $datetime,
                'hairdresser' => $appt->hairdresser->user->name ?? 'Kuaför',
                'service' => $appt->service->name ?? 'Hizmet',
                'status' => ucfirst($appt->status),
                'color' => match($appt->status) {
                    'pending' => '#ffc107',
                    'approved' => '#0d6efd',
                    'completed' => '#28a745',
                    'rejected' => '#dc3545',
                    default => '#6c757d'
                },
            ];
        })->filter();

        return response()->json($events->values());
    }
    public function services()
    {
        $services = \App\Models\Service::orderBy('name')->get(); // Veya kategoriye göre gruplu
        return view('client.services', compact('services'));
    }
    public function hairdressers()
    {
        $hairdressers = \App\Models\Hairdresser::with(['user', 'services'])->where('status', 'approved')->get();
        return view('client.hairdressers', compact('hairdressers'));
    }
}
