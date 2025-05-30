<nav class="nav flex-column gap-2">
    {{-- Kullanıcı Fotoğrafı ve İsim --}}
    <div class="d-flex flex-column align-items-center mb-4">
        @php
            $photo = auth()->user()->client->photo ?? null;
        @endphp
        <div class="rounded-circle overflow-hidden border border-white" style="width: 110px; height: 110px;">
            <img src="{{ $photo ? $photo : asset('images/default-avatar.png') }}" alt="Profil Fotoğrafı" class="w-100 h-100" style="object-fit: cover;">
        </div>
        <h5 class="mt-3 text-white text-center fw-semibold">{{ auth()->user()->name }}</h5>
    </div>

    <a href="{{ route('client.dashboard') }}"
       class="nav-link {{ request()->routeIs('client.dashboard') ? 'active fw-bold bg-primary text-white' : 'text-white' }}">
        <i class="bi bi-house-door-fill me-2"></i> Ana Sayfa
    </a>
    <a href="{{ route('client.hairdressers') }}"
       class="nav-link {{ request()->routeIs('client.hairdressers') ? 'active fw-bold bg-primary text-white' : 'text-white' }}">
        <i class="bi bi-people-fill me-2"></i> Kuaförler
    </a>

    <a href="{{ route('client.services') }}"
       class="nav-link {{ request()->routeIs('client.services') ? 'active fw-bold bg-primary text-white' : 'text-white' }}">
        <i class="bi bi-tags-fill me-2"></i> Hizmetler
    </a>

    <a href="{{ route('client.appointments.create') }}"
       class="nav-link {{ request()->routeIs('client.appointments.create') ? 'active fw-bold bg-primary text-white' : 'text-white' }}">
        <i class="bi bi-calendar-plus-fill me-2"></i> Randevu Oluştur
    </a>

    <a href="{{ route('client.appointments') }}"
       class="nav-link {{ (request()->routeIs('client.appointments') && !request()->routeIs('client.appointments.create')) ? 'active fw-bold bg-primary text-white' : 'text-white' }}">
        <i class="bi bi-card-list me-2"></i> Randevularım
    </a>


    <a href="{{ route('client.profile') }}"
       class="nav-link {{ request()->routeIs('client.profile') ? 'active fw-bold bg-primary text-white' : 'text-white' }}">
        <i class="bi bi-gear-fill me-2"></i> Profilim
    </a>

    <a href="{{ route('client.calendar') }}"
       class="nav-link {{ request()->routeIs('client.calendar') ? 'active fw-bold bg-primary text-white' : 'text-white' }}">
        <i class="bi bi-calendar3 me-2"></i> Takvim
    </a>


</nav>
