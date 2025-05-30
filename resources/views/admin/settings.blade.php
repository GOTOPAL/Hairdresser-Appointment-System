@extends('admin.layout.app')

@section('content')
    <h2 class="page-title mb-4 d-flex align-items-center">
        <i class="bi bi-gear-fill me-2 text-primary fs-4"></i> Sistem Ayarları
    </h2>

    @php
        $settings = \Illuminate\Support\Facades\DB::table('settings')->pluck('value', 'key');
    @endphp

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center shadow-sm">
            <i class="bi bi-check-circle-fill me-2 fs-5"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif

    <div class="card shadow-sm border-0 mx-auto" style="max-width: 700px;">
        <div class="card-body p-4">
            <form action="{{ route('admin.settings.update') }}" method="POST">
                @csrf

                {{-- 🔧 Bakım Modu --}}
                <div class="mb-4">
                    <label class="form-label fw-semibold d-flex align-items-center" for="maintenance_mode">
                        <i class="bi bi-tools me-2 text-warning fs-5"></i> Bakım Modu
                    </label>
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="maintenance_mode" name="maintenance_mode"
                            {{ ($settings['maintenance_mode'] ?? 'off') === 'on' ? 'checked' : '' }}>
                        <label class="form-check-label text-muted" for="maintenance_mode">
                            Siteyi geçici olarak devre dışı bırak
                        </label>
                    </div>
                </div>

                {{-- ⏱️ Oturum Süresi --}}
                <div class="mb-4">
                    <label for="session_timeout" class="form-label fw-semibold d-flex align-items-center">
                        <i class="bi bi-hourglass-split me-2 text-info fs-5"></i> Oturum Süresi (dakika)
                    </label>
                    <input type="number" id="session_timeout" name="session_timeout"
                           class="form-control" value="{{ $settings['session_timeout'] ?? 60 }}" min="1">
                    <div class="form-text">Kullanıcının oturumunun aktif kalacağı süredir.</div>
                </div>

                {{-- 📢 Bildirim Mesajı --}}
                <div class="mb-4">
                    <label for="notification_message" class="form-label fw-semibold d-flex align-items-center">
                        <i class="bi bi-megaphone-fill me-2 text-danger fs-5"></i> Bildirim Mesajı
                    </label>
                    <textarea name="notification_message" id="notification_message" class="form-control" rows="4"
                              placeholder="Bildirim mesajınızı yazın..."></textarea>
                    <div class="form-text">Bu mesaj, aşağıda seçeceğiniz kullanıcı grubuna bildirim olarak gönderilecektir.</div>
                </div>

                {{-- 🎯 Hedef Kitle Seçimi --}}
                <div class="mb-4">
                    <label for="target_group" class="form-label fw-semibold d-flex align-items-center">
                        <i class="bi bi-people-fill me-2 text-success fs-5"></i> Bildirim Hedefi
                    </label>
                    <select name="target_group" id="target_group" class="form-select" required>
                        <option value="hairdressers">Kuaför</option>
                        <option value="clients">Müşteri</option>
                    </select>
                    <div class="form-text">Bildirim gönderilecek kullanıcı grubunu seçin.</div>
                </div>

                {{-- Gönder Butonu --}}
                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="bi bi-send-fill me-2"></i> Kaydet ve Bildirim Gönder
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
