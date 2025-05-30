@extends('admin.layout.app')

@section('content')
    <h2 class="page-title">📅 Tüm Randevular</h2>

    @if($appointments->count())
        <div class="card p-3">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Müşteri</th>
                    <th>Kuaför</th>
                    <th>Hizmet</th>
                    <th>Tarih</th>
                    <th>Saat</th>
                    <th>Durum</th>
                    <th>İşlemler</th>
                </tr>
                </thead>
                <tbody>
                @foreach($appointments as $index => $a)
                    @php
                        $rawDate = str_replace([':AM', ':PM'], [' AM', ' PM'], $a->date);
                        $rawTime = str_replace([':AM', ':PM'], [' AM', ' PM'], $a->time);
                        $formattedDate = strtotime($rawDate) ? \Carbon\Carbon::createFromTimestamp(strtotime($rawDate))->format('d.m.Y') : 'Tarih Hatalı';
                        $formattedTime = strtotime($rawTime) ? \Carbon\Carbon::createFromTimestamp(strtotime($rawTime))->format('H:i') : 'Saat Hatalı';
                    @endphp
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $a->client->user->name ?? '-' }}</td>
                        <td>{{ $a->hairdresser->user->name ?? '-' }}</td>
                        <td>{{ $a->service->name ?? '-' }}</td>
                        <td>{{ $formattedDate }}</td>
                        <td>{{ $formattedTime }}</td>
                        <td><span class="badge bg-info text-dark">{{ ucfirst($a->status) }}</span></td>
                        <td>
                            <!-- Güncelle -->
                            <button class="btn btn-sm btn-outline-secondary rounded-circle" title="Güncelle" data-bs-toggle="modal" data-bs-target="#editModal{{ $a->id }}">
                                <i class="bi bi-pencil"></i>
                            </button>

                            <!-- Sil -->
                            <form action="{{ route('admin.appointments.destroy', $a->id) }}" method="POST" style="display:inline-block;">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger rounded-circle" title="Sil" onclick="return confirm('Silmek istediğinize emin misiniz?')">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>

                    <!-- Güncelleme Modal -->
                    <div class="modal fade" id="editModal{{ $a->id }}" tabindex="-1" aria-labelledby="editModalLabel{{ $a->id }}" aria-hidden="true">
                        <div class="modal-dialog">
                            <form action="{{ route('admin.appointments.update', $a->id) }}" method="POST">
                                @csrf
                                @method('PUT')
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="editModalLabel{{ $a->id }}">Randevu Güncelle</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label>Tarih</label>
                                            <input type="date" name="date" value="{{ $a->date }}" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label>Saat</label>
                                            <input type="time" name="time" value="{{ $a->time }}" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label>Durum</label>
                                            <select name="status" class="form-control" required>
                                                <option value="pending" @selected($a->status === 'pending')>Bekliyor</option>
                                                <option value="approved" @selected($a->status === 'approved')>Onaylandı</option>
                                                <option value="rejected" @selected($a->status === 'rejected')>Reddedildi</option>
                                                <option value="completed" @selected($a->status === 'completed')>Tamamlandı</option>
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
                @endforeach
                </tbody>
            </table>
        </div>
    @else
        <p class="text-muted">Hiç randevu bulunamadı.</p>
    @endif
@endsection
