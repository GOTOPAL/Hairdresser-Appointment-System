@extends('client.layout.app')

@section('content')
    <div class="container">
        <h2 class="mb-4 text-primary">💇‍♀️ Sunulan Hizmetler ve Fiyatlar</h2>

        @if($services->isEmpty())
            <div class="alert alert-info">Henüz hizmet tanımlanmamış.</div>
        @else
            <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                @foreach($services as $service)
                    <div class="col">
                        <div class="card h-100 shadow-sm border-0">
                            <div class="card-body">
                                <h5 class="card-title text-primary">{{ $service->name }}</h5>
                                <p class="card-text text-muted">{{ $service->description ?? 'Açıklama mevcut değil.' }}</p>
                            </div>
                            <div class="card-footer bg-transparent border-top-0">
                                <span class="fw-bold fs-5 text-success">
                                    ₺{{ number_format($service->price, 2, ',', '.') }}
                                </span>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection
