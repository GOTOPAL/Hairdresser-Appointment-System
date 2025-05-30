@extends('client.layout.app')

@section('content')
    <div class="container">
        <h3 class="mb-4">👤 Profil Bilgileri</h3>

        @if(session('success'))
            <div class="alert alert-success fw-semibold">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('client.profile.update') }}" class="needs-validation" novalidate>
            @csrf
            @method('PUT')

            <div class="mb-3 text-center">
                @php
                    $photo = auth()->user()->client->photo ?? null;
                @endphp
                <img src="{{ $photo ?: asset('images/default-avatar.png') }}" alt="Profil Fotoğrafı" class="rounded-circle mb-3" style="width: 120px; height: 120px; object-fit: cover; border: 2px solid #007bff;">
            </div>

            <div class="mb-3">
                <label for="photo" class="form-label">Profil Fotoğrafı URL'si (Opsiyonel)</label>
                <input type="url" id="photo" name="photo"
                       value="{{ old('photo', auth()->user()->client->photo ?? '') }}"
                       class="form-control @error('photo') is-invalid @enderror"
                       placeholder="https://example.com/photo.jpg">
                @error('photo')
                <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <div class="form-text">Fotoğrafınızın URL'sini buraya yapıştırabilirsiniz.</div>
                    @enderror
            </div>

            <div class="mb-3">
                <label for="name" class="form-label">Ad Soyad</label>
                <input type="text" id="name" name="name"
                       value="{{ old('name', Auth::user()->name) }}"
                       class="form-control @error('name') is-invalid @enderror"
                       required>
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <div class="invalid-feedback">Lütfen adınızı girin.</div>
                    @enderror
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">E-posta</label>
                <input type="email" id="email" name="email"
                       value="{{ old('email', Auth::user()->email) }}"
                       class="form-control @error('email') is-invalid @enderror"
                       required>
                @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <div class="invalid-feedback">Lütfen geçerli bir e-posta girin.</div>
                    @enderror
            </div>

            <div class="mb-3">
                <label for="phone_number" class="form-label">Telefon Numarası</label>
                <input type="text" id="phone_number" name="phone_number"
                       value="{{ old('phone_number', auth()->user()->phone_number) }}"
                       class="form-control @error('phone_number') is-invalid @enderror"
                       required>
                @error('phone_number')
                <div class="invalid-feedback">{{ $message }}</div>
                @else
                    <div class="invalid-feedback">Lütfen telefon numaranızı girin.</div>
                    @enderror
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Yeni Şifre (Opsiyonel)</label>
                <input type="password" id="password" name="password"
                       class="form-control @error('password') is-invalid @enderror"
                       placeholder="Yeni şifre">
                @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary px-4">Güncelle</button>
        </form>
    </div>

    <script>
        // Bootstrap 5 form validation
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
