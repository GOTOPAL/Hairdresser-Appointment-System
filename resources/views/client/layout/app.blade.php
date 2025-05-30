<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8" />
    <title>Müşteri Paneli</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <!-- Bootstrap Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600&display=swap" rel="stylesheet" />
    <!-- Özel CSS -->
    <link rel="stylesheet" href="{{ asset('css/client.css') }}" />
</head>
<body>

@php
    $client = auth()->user()->client ?? null;
    $notifications = collect();
    $unreadCount = 0;

    if ($client) {
        $notifications = \App\Models\Notification::where(function ($q) use ($client) {
            $q->where('client_id', $client->id)
              ->orWhere('is_global', true);
        })->orderByDesc('created_at')->take(5)->get();

        $unreadCount = $notifications->where('is_read', false)->count();
    }
@endphp

<div class="container-fluid">
    {{-- Sidebar --}}
    <aside class="sidebar bg-dark">
        @include('client.layout.sidebar')
    </aside>

    {{-- Main --}}
    <main class="client-main">
        <header class="d-flex justify-content-between align-items-center shadow-sm bg-white border-bottom">
            <h1 class="h5 mb-0 text-primary p-3">🎉 Müşteri Paneli</h1>

            <div class="d-flex align-items-center gap-2 p-3">
                {{-- Bildirimler Dropdown --}}
                <div class="dropdown notification-dropdown">
                    <button class="btn btn-outline-primary btn-sm position-relative" id="notifDropdownBtn" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell"></i>
                        @if($unreadCount > 0)
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                {{ $unreadCount }}
                                <span class="visually-hidden">okunmamış bildirimler</span>
                            </span>
                        @endif
                    </button>

                    <ul class="dropdown-menu dropdown-menu-end shadow" style="min-width: 320px; max-height: 400px; overflow-y: auto;">
                        {{-- Tümünü Okundu Yap --}}
                        @if($notifications->count() > 0)
                            <li class="mb-2 px-2">
                                <form method="POST" action="{{ route('client.notifications.markAllRead') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-outline-secondary w-100 fw-semibold">📨 Tümünü Okundu Yap</button>
                                </form>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                        @endif

                        {{-- Bildirimler --}}
                        @forelse($notifications as $notif)
                            <li class="mb-2 rounded px-2 py-2 notification-item {{ $notif->is_read ? '' : 'bg-primary bg-opacity-10' }}"
                                data-id="{{ $notif->id }}"
                                style="cursor: pointer;">
                                <div>
                                    <strong>{{ $notif->title }}</strong>
                                    @if($notif->is_global)
                                        <span class="badge bg-info ms-1">Genel</span>
                                    @endif
                                    <div class="small text-muted">{{ $notif->message }}</div>
                                    <small class="text-secondary">{{ $notif->created_at->diffForHumans() }}</small>
                                </div>
                            </li>
                        @empty
                            <li class="text-muted text-center">Bildirim yok.</li>
                        @endforelse
                    </ul>
                </div>

                {{-- Çıkış Butonu --}}
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-box-arrow-right me-1"></i> Çıkış Yap
                    </button>
                </form>
            </div>
        </header>

        {{-- İçerik Alanı --}}
        <section class="p-4 bg-white overflow-auto">
            @yield('content')
        </section>
    </main>
</div>

<!-- Bootstrap Bundle JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" defer></script>
<!-- Özel JS -->
<script src="{{ asset('js/client.js') }}" defer></script>

{{-- Bildirim AJAX --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.notification-item').forEach(item => {
            item.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const url = `/client/notifications/${id}/read`;

                fetch(url)
                    .then(res => res.json())
                    .then(data => {
                        if (data.success) {
                            this.classList.remove('bg-primary', 'bg-opacity-10');
                            this.classList.add('text-muted');
                            this.querySelector('strong')?.classList.add('fw-normal');
                        }
                    })
                    .catch(err => console.error('Bildirim işaretleme hatası:', err));
            });
        });
    });
</script>

@stack('scripts')
</body>
</html>
