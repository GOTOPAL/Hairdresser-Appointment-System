@extends('hairdresser.layout.app')

@section('content')
    <h2 class="page-title mb-4">📝 Müşteri Yorumları</h2>

    @if($appointments->count())
        <div class="row g-3">
            @foreach($appointments as $app)
                @php
                    try {
                        $cleanDate = str_replace([':AM', ':PM'], [' AM', ' PM'], $app->date);
                        $formattedDate = \Carbon\Carbon::parse($cleanDate)->format('d.m.Y');
                    } catch (\Exception $e) {
                        $formattedDate = 'Geçersiz Tarih';
                    }
                @endphp

                <div class="col-md-6 col-lg-4">
                    <div class="card shadow-sm h-100">
                        <div class="card-body d-flex flex-column">
                            <h5 class="card-title mb-2">{{ $app->client->user->name }}</h5>
                            <h6 class="card-subtitle mb-3 text-muted">{{ $app->service->name }}</h6>

                            <div class="mb-2">
                                <strong>Puan:</strong>
                                @for ($i = 1; $i <= 5; $i++)
                                    @if ($i <= $app->review->rating)
                                        <i class="bi bi-star-fill text-warning"></i>
                                    @else
                                        <i class="bi bi-star text-secondary"></i>
                                    @endif
                                @endfor
                                <span class="ms-2">({{ $app->review->rating }}/5)</span>
                            </div>

                            <p class="card-text flex-grow-1 text-truncate" style="max-height: 4.5em;" title="{{ $app->review->comment }}">
                                <strong>Yorum:</strong> {{ $app->review->comment }}
                            </p>

                            <p class="text-muted mb-0"><small><strong>Tarih:</strong> {{ $formattedDate }}</small></p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-muted">Henüz yorum yapılmamış.</p>
    @endif
@endsection
