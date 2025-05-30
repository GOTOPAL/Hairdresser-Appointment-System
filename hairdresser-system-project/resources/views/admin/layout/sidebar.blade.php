<!-- Sidebar -->
<aside class="sidebar p-3" style="width: 240px; background-color: #f0f2f5; border-right: 1px solid #ddd;">
    <div class="sidebar-header mb-4 text-center fw-bold fs-5 text-primary">
        <i class="bi bi-speedometer2 me-1"></i> Admin Paneli
    </div>

    <nav class="sidebar-menu">
        <ul class="nav flex-column">
            <li class="nav-item mb-2">
                <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active fw-semibold bg-white rounded shadow-sm px-3 py-2 text-dark' : 'text-dark' }}">
                    <i class="bi bi-bar-chart-fill me-2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="{{ route('admin.users') }}" class="nav-link {{ request()->routeIs('admin.users') ? 'active fw-semibold bg-white rounded shadow-sm px-3 py-2 text-dark' : 'text-dark' }}">
                    <i class="bi bi-people-fill me-2"></i> Kullanıcılar
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="{{ route('admin.hairdressers') }}" class="nav-link {{ request()->routeIs('admin.hairdressers') ? 'active fw-semibold bg-white rounded shadow-sm px-3 py-2 text-dark' : 'text-dark' }}">
                    <i class="bi bi-scissors me-2"></i> Kuaförler
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="{{ route('admin.appointments') }}" class="nav-link {{ request()->routeIs('admin.appointments') ? 'active fw-semibold bg-white rounded shadow-sm px-3 py-2 text-dark' : 'text-dark' }}">
                    <i class="bi bi-calendar-check me-2"></i> Randevular
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="{{ route('admin.services') }}" class="nav-link {{ request()->routeIs('admin.services') ? 'active fw-semibold bg-white rounded shadow-sm px-3 py-2 text-dark' : 'text-dark' }}">
                    <i class="bi bi-hammer me-2"></i> Hizmetler
                </a>
            </li>

            <li class="nav-item mb-2">
                <a href="{{ route('admin.reviews') }}" class="nav-link {{ request()->routeIs('admin.reviews') ? 'active fw-semibold bg-white rounded shadow-sm px-3 py-2 text-dark' : 'text-dark' }}">
                    <i class="bi bi-chat-left-text me-2"></i> Yorumlar
                </a>
            </li>

            <li class="nav-item mb-2">
                <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings') ? 'active fw-semibold bg-white rounded shadow-sm px-3 py-2 text-dark' : 'text-dark' }}">
                    <i class="bi bi-gear me-2"></i> Ayarlar
                </a>
            </li>
            <li class="nav-item mb-2">
                <a href="{{ route('admin.reports') }}" class="nav-link {{ request()->routeIs('admin.reports') ? 'active fw-semibold bg-white rounded shadow-sm px-3 py-2 text-dark' : 'text-dark' }}">
                    <i class="bi bi-graph-up-arrow me-2"></i> Raporlar
                </a>
            </li>
        </ul>
    </nav>
</aside>
