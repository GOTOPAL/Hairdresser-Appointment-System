<aside class="sidebar bg-dark text-white d-flex flex-column align-items-center p-4" style="width: 260px; min-height: 100vh;">
    {{-- Profil Fotoğrafı ve İsim --}}
    @php
        $photo = auth()->user()->hairdresserProfile->photo ?? null;
    @endphp
    <div class="d-flex flex-column align-items-center mb-4">
        <div class="rounded-circle overflow-hidden border border-white" style="width: 110px; height: 110px;">
            <img src="{{ $photo ? $photo : asset('images/default-avatar.png') }}" alt="Profil Fotoğrafı"
                 class="w-100 h-100" style="object-fit: cover;">
        </div>
        <h5 class="mt-3 text-white text-center fw-semibold">{{ auth()->user()->name }}</h5>
    </div>

    {{-- Navigasyon --}}
    <nav class="nav flex-column w-100 gap-2">
        <a href="{{ route('hairdresser.dashboard') }}"
           class="nav-link {{ request()->routeIs('hairdresser.dashboard') ? 'active fw-bold bg-primary text-white' : 'text-white' }}">
            <i class="bi bi-house-door-fill me-2"></i> Ana Sayfa
        </a>

        <a href="{{ route('hairdresser.profile') }}"
           class="nav-link {{ request()->routeIs('hairdresser.profile') ? 'active fw-bold bg-primary text-white' : 'text-white' }}">
            <i class="bi bi-person-fill me-2"></i> Profilim
        </a>

        <a href="{{ route('hairdresser.services') }}"
           class="nav-link {{ request()->routeIs('hairdresser.services') ? 'active fw-bold bg-primary text-white' : 'text-white' }}">
            <i class="bi bi-scissors me-2"></i> Hizmetler
        </a>

        <a href="{{ route('hairdresser.appointments') }}"
           class="nav-link {{ request()->routeIs('hairdresser.appointments') ? 'active fw-bold bg-primary text-white' : 'text-white' }}">
            <i class="bi bi-calendar-check-fill me-2"></i> Randevular
        </a>

        <a href="{{ route('hairdresser.reviews') }}"
           class="nav-link {{ request()->routeIs('hairdresser.reviews') ? 'active fw-bold bg-primary text-white' : 'text-white' }}">
            <i class="bi bi-chat-left-text-fill me-2"></i> Müşteri Yorumları
        </a>
    </nav>
</aside>
