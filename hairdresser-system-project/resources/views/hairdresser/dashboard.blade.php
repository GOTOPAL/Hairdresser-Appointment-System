@extends('hairdresser.layout.app')

@section('content')
    <h2 class="page-title mb-4">📊 Dashboard</h2>

    <div class="row g-4">
        <!-- İstatistik Kartları -->
        <div class="col-md-3">
            <div class="card text-white bg-primary p-3">
                <h5>Toplam Randevular</h5>
                <h2>{{ $totalAppointments }}</h2>
                <small>Bu Ay</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-warning p-3">
                <h5>Onay Bekleyen</h5>
                <h2>{{ $pendingAppointments }}</h2>
                <small>Bekleyen Randevular</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-success p-3">
                <h5>Tamamlanan</h5>
                <h2>{{ $completedAppointments }}</h2>
                <small>Başarıyla Tamamlanan</small>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card text-white bg-info p-3">
                <h5>Ortalama Puan</h5>
                <h2>{{ number_format($averageRating, 1) }}/5</h2>
                <small>Müşteri Değerlendirmesi</small>
            </div>
        </div>
    </div>

    <!-- Yaklaşan Randevular -->
    <div class="mt-5">
        <h4>Yaklaşan Randevular</h4>
        @if($upcomingAppointments->count())
            <table class="table table-striped">
                <thead>
                <tr>
                    <th>Müşteri</th>
                    <th>Hizmet</th>
                    <th>Tarih</th>
                    <th>Saat</th>
                    <th>Durum</th>
                </tr>
                </thead>
                <tbody>
                @foreach($upcomingAppointments as $app)
                    <tr>
                        <td>{{ $app->client->user->name ?? '-' }}</td>
                        <td>{{ $app->service->name }}</td>
                        @php
                            try {
                                $cleanDate = str_replace(':AM', ' AM', str_replace(':PM', ' PM', $app->date));
                                $formattedDate = \Carbon\Carbon::parse($cleanDate)->format('d.m.Y');
                            } catch (\Exception $e) {
                                $formattedDate = 'Geçersiz Tarih';
                            }

                            try {
                                $cleanTime = str_replace(':AM', ' AM', str_replace(':PM', ' PM', $app->time));
                                $formattedTime = \Carbon\Carbon::parse($cleanTime)->format('H:i');
                            } catch (\Exception $e) {
                                $formattedTime = 'Geçersiz Saat';
                            }
                        @endphp

                        <td>{{ $formattedDate }}</td>
                        <td>{{ $formattedTime }}</td>
                        <td><span class="badge bg-{{ $app->status === 'pending' ? 'warning' : ($app->status === 'approved' ? 'success' : 'secondary') }}">{{ ucfirst($app->status) }}</span></td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        @else
            <p class="text-muted">Yaklaşan randevunuz yok.</p>
        @endif
    </div>

    <!-- Son Yorumlar -->
    <div class="mt-5">
        <h4>Son Müşteri Yorumları</h4>
        @if($recentReviews->count())
            @foreach($recentReviews as $review)
                <div class="card mb-3 p-3">
                    <strong>{{ $review->appointment->client->user->name ?? '-' }}</strong> — ⭐ {{ $review->rating }}/5
                    <p>{{ $review->comment }}</p>
                    <small class="text-muted">{{ \Carbon\Carbon::parse($review->created_at)->diffForHumans() }}</small>
                </div>
            @endforeach
        @else
            <p class="text-muted">Henüz yorum yok.</p>
        @endif
    </div>
@endsection
