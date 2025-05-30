<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>Kuaför Paneli</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700&display=swap" rel="stylesheet">

    <!-- Özel CSS -->
    <link rel="stylesheet" href="{{ asset('css/hairdresser.css') }}">
    <link rel="stylesheet" href="{{ asset('css/notifications.css') }}">
    <link rel="stylesheet" href="{{ asset('css/notification-custom.css') }}">



</head>
<body class="bg-light">

@php
    $user = auth()->user();
    $hairdresser = $user->hairdresserProfile ?? null;
    $isApproved = $user->role === 'hairdresser' && $hairdresser && $hairdresser->status === 'approved';
@endphp

@if($isApproved)
    <div class="hairdresser-container d-flex min-vh-100">
        @include('hairdresser.layout.sidebar')

        <div class="hairdresser-main flex-grow-1 d-flex flex-column">
            <header class="hairdresser-header d-flex justify-content-between align-items-center p-3 border-bottom bg-white shadow-sm">
                <h2 class="mb-0">Kuaför Paneli</h2>

                <div class="header-right d-flex align-items-center gap-3">
                    <span class="fw-semibold">{{ $user->name }}</span>

                    @php
                        $notifications = \App\Models\Notification::where(function ($q) use ($hairdresser) {
                            $q->where('hairdresser_id', $hairdresser->id)
                              ->orWhere('is_global', true);
                        })
                        ->latest()
                        ->take(5)
                        ->get();

                        $unreadCount = $notifications->where('is_read', false)->count();
                    @endphp

                    <div class="dropdown notification-dropdown">
                        <button class="btn btn-outline-primary position-relative" id="notifToggle" data-bs-toggle="dropdown" aria-expanded="false" type="button">
                            <i class="bi bi-bell"></i>
                            @if($unreadCount > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $unreadCount }}
                                    <span class="visually-hidden">okunmamış bildirimler</span>
                                </span>
                            @endif
                        </button>

                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notifToggle">
                            @if($notifications->count() > 0)
                                <li class="mb-2 px-2">
                                    <form method="POST" action="{{ route('hairdresser.notifications.markAsRead') }}">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-secondary w-100 fw-semibold">
                                            📨 Tümünü Okundu Yap
                                        </button>
                                    </form>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                            @endif

                            @forelse($notifications as $notif)
                                <li class="mb-2 rounded p-2 notification-item {{ $notif->is_read ? '' : 'unread' }}">
                                    <a href="{{ $notif->appointment_id ? route('hairdresser.appointments', ['highlight' => $notif->appointment_id]) : '#' }}">
                                        <strong>{{ $notif->title }}</strong>
                                        @if($notif->is_global)
                                            <span class="badge-global">Genel</span>
                                        @endif
                                        <p class="mb-0 small text-truncate">{{ $notif->message }}</p>
                                        <small class="text-muted">{{ $notif->created_at->diffForHumans() }}</small>
                                    </a>
                                </li>
                            @empty
                                <li class="text-muted text-center">Bildirim yok.</li>
                            @endforelse
                        </ul>
                    </div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger btn-sm">Çıkış Yap</button>
                    </form>
                </div>
            </header>

            <main class="flex-grow-1 p-4 bg-white overflow-auto">
                @yield('content')
            </main>
        </div>
    </div>
@else
    @include('hairdresser.waiting')
@endif

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
<script src="{{ asset('js/notifications.js') }}" defer></script>
</body>
</html>
