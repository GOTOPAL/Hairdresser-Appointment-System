@extends('admin.layout.app')
@section('content')

    <h2 class="page-title mb-4">💇‍♂️ Hizmetler</h2>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
        </div>
    @endif

    {{-- Ekleme Formu --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.services.store') }}">
                @csrf
                <div class="row g-3 align-items-center">
                    <div class="col-md-5">
                        <label class="form-label">Hizmet Adı</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="col-md-3">
                        <label class="form-label">Varsayılan Fiyat (₺)</label>
                        <input type="number" name="price" class="form-control" step="0.01" required>
                    </div>
                    <div class="col-md-2 mt-4">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-plus-circle"></i> Ekle
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Hizmet Listesi --}}
    <div class="card">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Hizmet Adı</th>
                    <th>Varsayılan Fiyat</th>
                    <th>Oluşturulma</th>
                    <th class="text-end">İşlem</th>
                </tr>
                </thead>
                <tbody>
                @forelse($services as $service)
                    <tr>
                        <td>{{ $service->id }}</td>
                        <td>{{ $service->name }}</td>
                        <td>{{ number_format($service->price, 2) }} ₺</td>
                        <td>{{ $service->created_at->format('d.m.Y H:i') }}</td>
                        <td class="text-end">
                            {{-- ✏️ Düzenle Butonu --}}
                            <button class="btn btn-sm btn-outline-secondary me-1"
                                    data-bs-toggle="modal" data-bs-target="#editModal{{ $service->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>

                            {{-- 🗑 Silme Formu --}}
                            <form method="POST" action="{{ route('admin.services.delete', $service->id) }}"
                                  class="d-inline"
                                  onsubmit="return confirm('Bu hizmeti silmek istediğinize emin misiniz?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    {{-- Modal: Hizmeti Düzenle --}}
                    <div class="modal fade" id="editModal{{ $service->id }}" tabindex="-1" aria-hidden="true">
                        <div class="modal-dialog">
                            <form method="POST" action="{{ route('admin.services.store') }}">
                                @csrf
                                <input type="hidden" name="service_id" value="{{ $service->id }}">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Hizmeti Düzenle</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Hizmet Adı</label>
                                            <input type="text" name="name" value="{{ $service->name }}" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Varsayılan Fiyat (₺)</label>
                                            <input type="number" name="price" value="{{ $service->price }}" class="form-control" step="0.01" required>
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
                @empty
                    <tr>
                        <td colspan="5" class="text-center text-muted">Hizmet bulunamadı.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
