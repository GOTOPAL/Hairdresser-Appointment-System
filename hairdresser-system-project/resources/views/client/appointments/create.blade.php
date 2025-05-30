@extends('client.layout.app')
@section('content')

    <h2 class="page-title mb-4">💈 Randevu Oluştur</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @foreach($hairdressers as $hairdresser)
        <div class="card mb-4 shadow-sm border-0">
            <div class="card-header bg-white d-flex align-items-center gap-3 border-bottom">
                @if($hairdresser->user->photo)
                    <img src="{{ asset('storage/' . $hairdresser->user->photo) }}" alt="Fotoğraf"
                         class="rounded-circle shadow-sm" style="width: 60px; height: 60px; object-fit: cover;">
                @else
                    <div class="bg-secondary rounded-circle d-flex justify-content-center align-items-center shadow-sm"
                         style="width: 60px; height: 60px; color: white; font-weight: 600;">
                        {{ strtoupper(substr($hairdresser->user->name, 0, 1)) }}
                    </div>
                @endif
                <h4 class="mb-0 text-dark fw-semibold">{{ $hairdresser->user->name }}</h4>
            </div>

            <div class="card-body">
                <form action="{{ route('client.appointments.store') }}" method="POST" class="needs-validation" novalidate>
                    @csrf
                    <input type="hidden" name="hairdresser_id" value="{{ $hairdresser->id }}">

                    {{-- Hizmetler --}}
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Hizmet Seçimi</label>
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($hairdresser->services as $service)
                                <div class="form-check btn btn-outline-primary position-relative" style="min-width: 160px;">
                                    <input class="form-check-input position-absolute top-0 start-0 m-2" type="checkbox"
                                           name="services[]" value="{{ $service->id }}" id="service{{ $service->id }}">
                                    <label class="form-check-label w-100 ps-4 text-start" for="service{{ $service->id }}">
                                        <strong>{{ $service->name }}</strong><br>
                                        <small class="text-muted">
                                            {{ $service->price ? number_format($service->price, 2) . '₺' : 'Fiyat belirtilmemiş' }}
                                        </small>
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('services')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Tarih --}}
                    <div class="mb-3">
                        <label for="date{{ $hairdresser->id }}" class="form-label fw-semibold">Tarih</label>
                        <input type="date" class="form-control" id="date{{ $hairdresser->id }}" name="date"
                               min="{{ now()->toDateString() }}" max="{{ now()->addDays(14)->toDateString() }}" required>
                        <div class="invalid-feedback">Lütfen geçerli bir tarih seçin.</div>
                        @error('date')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Saat --}}
                    <div class="mb-4">
                        <label for="time{{ $hairdresser->id }}" class="form-label fw-semibold">Saat</label>
                        <input type="time" class="form-control" id="time{{ $hairdresser->id }}" name="time" required>
                        <div class="invalid-feedback">Lütfen geçerli bir saat seçin.</div>
                        @error('time')
                        <div class="text-danger small mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-circle-fill me-1"></i> Randevu Al
                    </button>
                </form>
            </div>
        </div>
    @endforeach

    <script>
        (() => {
            'use strict';
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        })();
    </script>
@endsection
