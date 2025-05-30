@extends('admin.layout.app')

@section('content')
    <h2 class="page-title mb-4">📊 Yönetim Paneli</h2>

    {{-- Özet Bilgiler --}}
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card text-white bg-primary h-100 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <i class="bi bi-people-fill fs-1 me-3"></i>
                    <div>
                        <h4 class="mb-0">{{ $userCount }}</h4>
                        <small>Kullanıcı</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success h-100 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <i class="bi bi-scissors fs-1 me-3"></i>
                    <div>
                        <h4 class="mb-0">{{ $hairdresserCount }}</h4>
                        <small>Kuaför</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-warning h-100 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <i class="bi bi-calendar-check-fill fs-1 me-3"></i>
                    <div>
                        <h4 class="mb-0">{{ $activeAppointments }}</h4>
                        <small>Aktif Randevu</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-info h-100 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <i class="bi bi-chat-left-dots-fill fs-1 me-3"></i>
                    <div>
                        <h4 class="mb-0">{{ $reviewCount }}</h4>
                        <small>Yorum</small>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 mt-3">
            <div class="card bg-light h-100 shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <i class="bi bi-gear-fill fs-1 me-3 text-secondary"></i>
                    <div>
                        <small>Sistem Durumu</small>
                        <h5 class="mb-0 {{ $maintenance ? 'text-danger' : 'text-success' }}">
                            {{ $maintenance ? 'Bakımda' : 'Aktif' }}
                        </h5>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Son İşlemler --}}
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-bold">🕓 Son Randevular</div>
                <ul class="list-group list-group-flush">
                    @foreach ($recentAppointments as $app)
                        @php
                            $rawDate = str_replace([':AM', ':PM'], [' AM', ' PM'], $app->date);
                            $formattedDate = strtotime($rawDate) ? \Carbon\Carbon::createFromTimestamp(strtotime($rawDate))->format('d.m.Y') : 'Tarih Hatalı';
                            $formattedTime = strtotime($app->time) ? \Carbon\Carbon::createFromTimestamp(strtotime($app->time))->format('H:i') : 'Saat Hatalı';
                        @endphp
                        <li class="list-group-item small">
                            <strong>{{ $app->client->user->name }}</strong> →
                            <strong>{{ $app->hairdresser->user->name }}</strong>
                            <span class="text-muted d-block">📅 {{ $formattedDate }} {{ $formattedTime }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-bold">⭐ Son Yorumlar</div>
                <ul class="list-group list-group-flush">
                    @foreach ($recentReviews as $review)
                        <li class="list-group-item small">
                            <span class="text-warning">{{ $review->rating }}/5</span> -
                            “{{ \Illuminate\Support\Str::limit($review->comment, 60) }}”
                            <br><small class="text-muted">{{ $review->appointment->client->user->name }} ➝ {{ $review->appointment->hairdresser->user->name }}</small>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow-sm">
                <div class="card-header bg-white fw-bold">🆕 Yeni Kayıtlar</div>
                <ul class="list-group list-group-flush">
                    @foreach ($newUsers as $user)
                        <li class="list-group-item small">
                            {{ $user->name }} ({{ ucfirst($user->role) }})
                            <br><span class="text-muted">{{ $user->created_at->format('d.m.Y') }}</span>
                        </li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
@endsection
