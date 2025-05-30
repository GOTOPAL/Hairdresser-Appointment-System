@extends('admin.layout.app')

@section('content')
    <h2 class="page-title mb-4">📝 Yorumlar</h2>

    @if(session('success'))
        <div class="alert alert-success d-flex align-items-center" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i>
            {{ session('success') }}
        </div>
    @endif

    <div class="card p-3">
        <table class="table table-hover align-middle">
            <thead class="table-light">
            <tr>
                <th>Puan</th>
                <th>Yorum</th>
                <th>Müşteri</th>
                <th>Kuaför</th>
                <th>Hizmet</th> <!-- Yeni sütun -->
                <th>Tarih</th>
                <th class="text-end">İşlem</th>
            </tr>
            </thead>
            <tbody>
            @forelse($reviews as $review)
                <tr>
                    <td><span class="badge bg-warning text-dark">{{ $review->rating }}/5</span></td>
                    <td>{{ \Illuminate\Support\Str::limit($review->comment, 80) }}</td>
                    <td>{{ $review->appointment->client->user->name ?? '-' }}</td>
                    <td>{{ $review->appointment->hairdresser->user->name ?? '-' }}</td>
                    <td>{{ $review->appointment->service->name ?? '-' }}</td> <!-- Hizmet adı -->
                    <td>{{ $review->created_at->format('d.m.Y H:i') }}</td>
                    <td class="text-end">
                        <!-- Düzenle Butonu -->
                        <button class="btn btn-sm btn-outline-secondary rounded-circle me-1" title="Düzenle"
                                data-bs-toggle="modal" data-bs-target="#editReviewModal{{ $review->id }}">
                            <i class="bi bi-pencil"></i>
                        </button>

                        <!-- Sil Butonu -->
                        <form method="POST" action="{{ route('admin.reviews.delete', $review->id) }}"
                              onsubmit="return confirm('Yorumu silmek istediğinize emin misiniz?')"
                              style="display:inline-block;">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-outline-danger rounded-circle" title="Sil">
                                <i class="bi bi-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>

                <!-- Modal: Yorum Düzenle -->
                <div class="modal fade" id="editReviewModal{{ $review->id }}" tabindex="-1" aria-labelledby="editReviewModalLabel{{ $review->id }}" aria-hidden="true">
                    <div class="modal-dialog">
                        <form method="POST" action="{{ route('admin.reviews.update', $review->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Yorumu Düzenle</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <label for="rating" class="form-label">Puan (1-5)</label>
                                        <input type="number" name="rating" class="form-control" value="{{ $review->rating }}" min="1" max="5" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="comment" class="form-label">Yorum</label>
                                        <textarea name="comment" class="form-control" rows="3" required>{{ $review->comment }}</textarea>
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
                    <td colspan="7" class="text-center text-muted fst-italic">Hiç yorum bulunamadı.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $reviews->links() }}
    </div>
@endsection
