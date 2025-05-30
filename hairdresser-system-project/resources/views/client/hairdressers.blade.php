@extends('client.layout.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/client.css') }}">
    <h2 class="page-title mb-4">💇 Kuaförler</h2>

    <div class="row row-cols-1 row-cols-md-2 g-4">
        @foreach($hairdressers as $hairdresser)
            @php
                $shortBio = Str::limit($hairdresser->bio, 100);
                $isLong = Str::length($hairdresser->bio) > 100;
            @endphp

            <div class="col">
                <div class="card border-0 shadow-sm h-100 hover-shadow transition">
                    <div class="card-body d-flex gap-3 align-items-start">
                        <img src="{{ $hairdresser->photo ?? asset('images/default-avatar.png') }}"
                             alt="Fotoğraf"
                             class="rounded-circle border shadow-sm"
                             style="width: 80px; height: 80px; object-fit: cover;">

                        <div class="flex-grow-1">
                            <h5 class="fw-bold mb-1">{{ $hairdresser->user->name }}</h5>
                            <p class="text-muted mb-1 small"><i class="bi bi-envelope-fill me-1"></i>{{ $hairdresser->user->email }}</p>
                            <p class="text-muted mb-2 small"><i class="bi bi-telephone-fill me-1"></i>{{ $hairdresser->user->phone_number ?? 'Bilinmiyor' }}</p>

                            <p class="text-muted small mb-0">
                                {{ $shortBio }}
                                @if($isLong)
                                    <button class="btn btn-link btn-sm ps-1" data-bs-toggle="modal" data-bs-target="#bioModal{{ $hairdresser->id }}">
                                        Devamı
                                    </button>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="card-footer bg-white border-0 pt-0 px-4 pb-3">
                        <h6 class="fw-semibold mb-2">Hizmetler</h6>
                        <div class="d-flex flex-wrap gap-2">
                            @forelse($hairdresser->services as $service)
                                <span class="badge bg-primary bg-opacity-75">{{ $service->name }}</span>
                            @empty
                                <span class="text-muted">Hizmet bulunamadı.</span>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal --}}
            @if($isLong)
                <div class="modal fade" id="bioModal{{ $hairdresser->id }}" tabindex="-1" aria-labelledby="bioModalLabel{{ $hairdresser->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content border-0 rounded-4 shadow-lg">
                            <div class="modal-body p-4">
                                <div class="row align-items-center">
                                    <div class="col-md-4 text-center mb-3 mb-md-0">
                                        <img src="{{ $hairdresser->photo ?? asset('images/default-avatar.png') }}"
                                             alt="Fotoğraf"
                                             class="rounded-circle shadow border"
                                             style="width: 140px; height: 140px; object-fit: cover;">
                                        <h5 class="mt-3">{{ $hairdresser->user->name }}</h5>
                                        <small class="text-muted">{{ $hairdresser->user->email }}</small>
                                        <div class="text-muted small mt-1">
                                            <i class="bi bi-telephone-fill me-1"></i> {{ $hairdresser->user->phone_number ?? 'Belirtilmemiş' }}
                                        </div>
                                    </div>

                                    <div class="col-md-8">
                                        <h6 class="fw-semibold mb-2">Hakkında</h6>
                                        <p class="text-muted" style="max-height: 200px; overflow-y: auto;">{{ $hairdresser->bio }}</p>

                                        <h6 class="fw-semibold mt-4 mb-2">Hizmetler</h6>
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($hairdresser->services as $service)
                                                <span class="badge bg-primary bg-opacity-75">{{ $service->name }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer border-0">
                                <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                                    <i class="bi bi-x-circle me-1"></i> Kapat
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    </div>


@endsection
