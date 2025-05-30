@extends('admin.layout.app')

@section('content')
    <h2 class="page-title mb-4">✂️ Kuaförler</h2>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <form method="GET" class="row g-2 align-items-center mb-4">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="🔍 Kuaför ismi veya e-posta" value="{{ request('search') }}">
        </div>
        <div class="col-md-3">
            <select name="status" class="form-select">
                <option value="">Tüm Statüler</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Bekliyor</option>
                <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Onaylı</option>
                <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Reddedildi</option>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-outline-primary w-100">
                <i class="bi bi-search"></i> Ara
            </button>
        </div>
    </form>

    <div class="card p-3">
        <table class="table table-hover align-middle">
            <thead class="table-light">
            <tr>
                <th>İsim</th>
                <th>Email</th>
                <th>Durum</th>
                <th>Kayıt Tarihi</th>
                <th class="text-end">İşlemler</th>
            </tr>
            </thead>
            <tbody>
            @forelse($hairdressers as $hd)
                <tr>
                    <td>{{ $hd->user->name }}</td>
                    <td>{{ $hd->user->email }}</td>
                    <td>
                        @if($hd->status === 'pending')
                            <span class="badge bg-warning text-dark">Bekliyor</span>
                        @elseif($hd->status === 'approved')
                            <span class="badge bg-success">Onaylı</span>
                        @elseif($hd->status === 'rejected')
                            <span class="badge bg-danger">Reddedildi</span>
                        @endif
                    </td>
                    <td>{{ $hd->created_at->format('d.m.Y H:i') }}</td>
                    <td class="text-end">
                        <button class="btn btn-outline-info btn-sm" data-bs-toggle="modal" data-bs-target="#detailModal{{ $hd->id }}">
                            <i class="bi bi-info-circle"></i> Detay
                        </button>

                        @if($hd->status === 'pending')
                            <form method="POST" action="{{ route('admin.hairdressers.approve', $hd->id) }}" class="d-inline">
                                @csrf
                                <button class="btn btn-success btn-sm">Onayla</button>
                            </form>
                            <form method="POST" action="{{ route('admin.hairdressers.reject', $hd->id) }}" class="d-inline">
                                @csrf
                                <button class="btn btn-danger btn-sm">Reddet</button>
                            </form>
                        @endif

                        <form method="POST" action="{{ route('admin.hairdressers.delete', $hd->id) }}"
                              onsubmit="return confirm('Kuaförü silmek istediğinize emin misiniz?')"
                              class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-outline-danger btn-sm" title="Sil">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                <!-- Detay Modal -->
                <div class="modal fade" id="detailModal{{ $hd->id }}" tabindex="-1" aria-labelledby="detailModalLabel{{ $hd->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-lg modal-dialog-scrollable">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Kuaför Detayları: {{ $hd->user->name }}</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row mb-3">
                                    <div class="col-md-4 text-center">
                                        @if($hd->photo)
                                            <img src="{{ $hd->photo }}" class="img-fluid rounded shadow" style="max-height: 200px;" alt="Profil Fotoğrafı">
                                        @else
                                            <div class="text-muted">📷 Fotoğraf yok</div>
                                        @endif
                                    </div>
                                    <div class="col-md-8">
                                        <p><strong>Ad Soyad:</strong> {{ $hd->user->name }}</p>
                                        <p><strong>Email:</strong> {{ $hd->user->email }}</p>
                                        <p><strong>Telefon:</strong> {{ $hd->user->phone_number }}</p>
                                        <p><strong>Durum:</strong>
                                            @if($hd->status === 'approved')
                                                <span class="badge bg-success">Onaylı</span>
                                            @elseif($hd->status === 'pending')
                                                <span class="badge bg-warning text-dark">Bekliyor</span>
                                            @else
                                                <span class="badge bg-secondary">Bilinmiyor</span>
                                            @endif
                                        </p>
                                        @if($hd->bio)
                                            <p><strong>Hakkında:</strong><br>{{ $hd->bio }}</p>
                                        @endif
                                    </div>
                                </div>

                                <div>
                                    <h6 class="mb-2">Hizmetler</h6>
                                    @if($hd->services->count())
                                        <ul class="list-group">
                                            @foreach($hd->services as $service)
                                                <li class="list-group-item">{{ $service->name }}</li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-muted fst-italic">Tanımlı hizmet yok.</p>
                                    @endif
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Kapat</button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <tr>
                    <td colspan="5" class="text-center text-muted fst-italic">Kuaför bulunamadı.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
