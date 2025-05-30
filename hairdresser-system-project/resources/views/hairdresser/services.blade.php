@extends('hairdresser.layout.app')

@section('content')
    <link rel="stylesheet" href="{{ asset('css/hairdresser.css') }}">

    <h2 class="page-title mb-3">✂️ Hizmetlerim</h2>
    <p>Verebildiğiniz hizmetleri seçin:</p>

    @if(session('success'))
        <div class="alert alert-success mt-3" role="alert">
            {{ session('success') }}
        </div>
    @endif

    <form action="{{ route('hairdresser.services.update') }}" method="POST" id="service-form" class="mt-4">
        @csrf

        <div class="services-grid">
            @foreach($allServices as $service)
                <button type="button"
                        class="btn service-toggle d-flex align-items-center {{ in_array($service->id, $selectedServices) ? 'active' : '' }}"
                        data-id="{{ $service->id }}"
                        aria-pressed="{{ in_array($service->id, $selectedServices) ? 'true' : 'false' }}"
                        tabindex="0"
                        @if(in_array($service->id, $selectedServices)) aria-current="true" @endif
                >
                    <span class="service-name flex-grow-1">{{ $service->name }}</span>
                    <svg class="checkmark" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="18" height="18" aria-hidden="true" focusable="false">
                        <path fill="none" stroke="#fff" stroke-width="3" d="M4 12l6 6L20 6"/>
                    </svg>
                </button>
            @endforeach
        </div>

        {{-- Gizli inputlar --}}
        <div id="selected-services" class="d-none"></div>

        <button type="submit" class="btn btn-success mt-4 px-4">Kaydet</button>
    </form>


    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const buttons = document.querySelectorAll('.service-toggle');
            const selectedContainer = document.getElementById('selected-services');

            function updateHiddenInputs() {
                selectedContainer.innerHTML = '';
                buttons.forEach(btn => {
                    if (btn.classList.contains('active')) {
                        const id = btn.dataset.id;
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'services[]';
                        input.value = id;
                        selectedContainer.appendChild(input);
                    }
                });
            }

            buttons.forEach(btn => {
                btn.addEventListener('click', () => {
                    btn.classList.toggle('active');
                    updateHiddenInputs();
                });
            });

            // Sayfa yüklendiğinde seçili inputları ayarla
            updateHiddenInputs();
        });
    </script>
@endsection
