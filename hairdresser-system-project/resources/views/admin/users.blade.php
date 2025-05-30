@extends('admin.layout.app')

@section('content')
    <h2 class="page-title mb-4 d-flex align-items-center">
        <i class="bi bi-people-fill me-2"></i> Kullanıcılar
    </h2>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <!-- Yeni Kullanıcı Ekle Butonu -->
    <div class="mb-3 text-end">
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createUserModal">
            <i class="bi bi-person-plus me-1"></i> Yeni Kullanıcı Ekle
        </button>
    </div>

    <!-- Filtreleme -->
    <form method="GET" class="row g-2 align-items-center mb-4">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="🔍 İsim veya email..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <select name="role" class="form-select">
                <option value="">Tüm Roller</option>
                <option value="client" {{ request('role') === 'client' ? 'selected' : '' }}>Müşteri</option>
                <option value="hairdresser" {{ request('role') === 'hairdresser' ? 'selected' : '' }}>Kuaför</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-outline-primary w-100">
                <i class="bi bi-search"></i> Ara
            </button>
        </div>
    </form>

    <!-- Kullanıcı Tablosu -->
    <div class="card p-3">
        <table class="table table-hover align-middle">
            <thead class="table-light">
            <tr>
                <th>İsim</th>
                <th>Email</th>
                <th>Rol</th>
                <th>Durum</th>
                <th>Kayıt Tarihi</th>
                <th class="text-end">İşlem</th>
            </tr>
            </thead>
            <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        <span class="badge bg-{{ $user->role === 'client' ? 'success' : 'primary' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td>
                        @if($user->active)
                            <span class="badge bg-success">Aktif</span>
                        @else
                            <span class="badge bg-secondary">Pasif</span>
                        @endif
                    </td>
                    <td>{{ $user->created_at->format('d.m.Y H:i') }}</td>
                    <td class="text-end">
                        <!-- Durum Değiştir -->
                        <form method="POST" action="{{ route('admin.users.toggleStatus', $user->id) }}" class="d-inline me-1">
                            @csrf
                            @method('PUT')
                            @if($user->active)
                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Pasif Yap">
                                    <i class="bi bi-person-x"></i>
                                </button>
                            @else
                                <button type="submit" class="btn btn-sm btn-outline-success" title="Aktif Yap">
                                    <i class="bi bi-person-check"></i>
                                </button>
                            @endif
                        </form>

                        <!-- Düzenle -->
                        <button class="btn btn-sm btn-outline-secondary rounded-circle me-1" title="Düzenle"
                                data-bs-toggle="modal" data-bs-target="#editUserModal{{ $user->id }}">
                            <i class="bi bi-pencil"></i>
                        </button>

                        <!-- Sil -->
                        <form method="POST" action="{{ route('admin.users.delete', $user->id) }}"
                              onsubmit="return confirm('Silmek istediğinize emin misiniz?')"
                              style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger rounded-circle" title="Sil">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                <!-- Modal: Kullanıcı Düzenle -->
                <div class="modal fade" id="editUserModal{{ $user->id }}" tabindex="-1" aria-labelledby="editUserModalLabel{{ $user->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('admin.users.update', $user->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Kullanıcıyı Düzenle</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">İsim</label>
                                        <input type="text" name="name" class="form-control" value="{{ $user->name }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="email" class="form-label">Email</label>
                                        <input type="email" name="email" class="form-control" value="{{ $user->email }}" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="role" class="form-label">Rol</label>
                                        <select name="role" class="form-select">
                                            <option value="client" @selected($user->role === 'client')>Müşteri</option>
                                            <option value="hairdresser" @selected($user->role === 'hairdresser')>Kuaför</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Güncelle</button>
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted fst-italic">Kullanıcı bulunamadı.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $users->withQueryString()->links() }}
    </div>

    <!-- Modal: Yeni Kullanıcı Ekle -->
    <div class="modal fade" id="createUserModal" tabindex="-1" aria-labelledby="createUserModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('admin.users.store') }}">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Yeni Kullanıcı Ekle</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">İsim</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Şifre</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label for="role" class="form-label">Rol</label>
                            <select name="role" class="form-select">
                                <option value="client">Müşteri</option>
                                <option value="hairdresser">Kuaför</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Kaydet</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">İptal</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
