@extends('hairdresser.layout.app')

@section('content')
    <h2 class="page-title mb-4">👤 Profil Bilgileri</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Kapat"></button>
        </div>
    @endif

    <form method="POST" action="{{ route('hairdresser.profile.update') }}" class="needs-validation" novalidate>
        @csrf

        <div class="row g-3 mb-3">
            {{-- Ad Soyad --}}
            <div class="col-md-6">
                <label for="name" class="form-label fw-semibold">Ad Soyad</label>
                <input type="text" id="name" name="name"
                       value="{{ old('name', $user->name) }}"
                       class="form-control @error('name') is-invalid @enderror" required>
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Telefon --}}
            <div class="col-md-6">
                <label for="phone_number" class="form-label fw-semibold">Telefon Numarası</label>
                <input type="text" id="phone_number" name="phone_number"
                       value="{{ old('phone_number', $user->phone_number) }}"
                       class="form-control @error('phone_number') is-invalid @enderror" required>
                @error('phone_number')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- E-posta --}}
        <div class="mb-3">
            <label for="email" class="form-label fw-semibold">E-posta</label>
            <input type="email" id="email" name="email"
                   value="{{ old('email', $user->email) }}"
                   class="form-control @error('email') is-invalid @enderror" required>
            @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="row g-3 mb-4">
            {{-- Şifre --}}
            <div class="col-md-6">
                <label for="password" class="form-label fw-semibold">Yeni Şifre (Opsiyonel)</label>
                <input type="password" id="password" name="password"
                       class="form-control @error('password') is-invalid @enderror" placeholder="Yeni şifre">
                @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{-- Şifre Tekrarı --}}
            <div class="col-md-6">
                <label for="password_confirmation" class="form-label fw-semibold">Şifre Tekrarı</label>
                <input type="password" id="password_confirmation" name="password_confirmation"
                       class="form-control" placeholder="Yeni şifre tekrar">
            </div>
        </div>

        {{-- Fotoğraf Linki --}}
        <div class="mb-4">
            <label for="photo" class="form-label fw-semibold">Profil Fotoğrafı (Resim Linki)</label>
            <input type="url" id="photo" name="photo"
                   value="{{ old('photo', $user->hairdresserProfile->photo) }}"
                   placeholder="https://resim.com/foto.jpg"
                   class="form-control @error('photo') is-invalid @enderror">
            @error('photo')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        {{-- Biyografi --}}
        <div class="mb-4">
            <label for="bio" class="form-label fw-semibold">Kısa Biyografi</label>
            <textarea id="bio" name="bio" rows="4" placeholder="Kendinizden bahsedin..."
                      class="form-control @error('bio') is-invalid @enderror" style="resize:none;">{{ old('bio', $user->hairdresserProfile->bio) }}</textarea>
            @error('bio')
            <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary btn-lg w-100">Güncelle</button>
    </form>

    <script>
        (() => {
            'use strict'
            const forms = document.querySelectorAll('.needs-validation')
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault()
                        event.stopPropagation()
                    }
                    form.classList.add('was-validated')
                }, false)
            })
        })()
    </script>
@endsection
