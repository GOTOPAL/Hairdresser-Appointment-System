@extends('client.layout.app')

@section('content')
    <h2 class="page-title mb-4">📅 Randevularım</h2>

    @if($appointments->count())
        @php $groupedAppointments = $appointments->groupBy('hairdresser_id'); @endphp

        <div class="d-flex flex-column gap-4">
            @foreach($groupedAppointments as $hairdresserId => $group)
                @php $hairdresser = $group->first()->hairdresser; @endphp

                <div class="card border-0 shadow-sm rounded-4 bg-white p-4">
                    {{-- Kuaför Başlığı --}}
                    <div class="d-flex align-items-center mb-4">
                        <img src="{{ $hairdresser->photo ?? 'https://via.placeholder.com/70' }}" class="rounded-circle border border-2" style="width: 70px; height: 70px; object-fit: cover;" alt="Kuaför Foto">
                        <div class="ms-3">
                            <h5 class="mb-0">{{ $hairdresser->user->name ?? 'Kuaför' }}</h5>
                            <small class="text-muted">👤 Kuaför Randevuları</small>
                        </div>
                    </div>

                    {{-- Randevu Tablosu --}}
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                            <tr class="text-muted small text-uppercase">
                                <th>Hizmet</th>
                                <th>Tarih</th>
                                <th>Saat</th>
                                <th>Durum</th>
                                <th class="text-center">İşlem</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($group as $appointment)
                                @php
                                    $rawDate = str_replace([':AM', ':PM'], [' AM', ' PM'], $appointment->date);
                                    $formattedDate = strtotime($rawDate)
                                        ? \Carbon\Carbon::createFromTimestamp(strtotime($rawDate))->format('d.m.Y')
                                        : 'Tarih Hatalı';

                                    $rawTime = str_replace([':AM', ':PM'], [' AM', ' PM'], $appointment->time);
                                    $formattedTime = strtotime($rawTime)
                                        ? \Carbon\Carbon::createFromTimestamp(strtotime($rawTime))->format('H:i')
                                        : 'Saat Hatalı';

                                    $statusClass = match($appointment->status) {
                                        'pending' => 'warning',
                                        'approved' => 'primary',
                                        'completed' => 'success',
                                        'rejected' => 'danger',
                                        default => 'secondary',
                                    };
                                @endphp

                                <tr>
                                    <td>{{ $appointment->service->name }}</td>
                                    <td>{{ $formattedDate }}</td>
                                    <td>{{ $formattedTime }}</td>
                                    <td><span class="badge bg-{{ $statusClass }}">{{ ucfirst($appointment->status) }}</span></td>
                                    <td class="text-center">
                                        @if($appointment->status === 'pending')
                                            <form method="POST" action="{{ route('client.appointments.cancel', $appointment->id) }}" class="d-inline">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" class="btn btn-outline-danger btn-sm" title="İptal Et">
                                                    <i class="bi bi-x-circle"></i>
                                                </button>
                                            </form>

                                        @elseif($appointment->status === 'approved' && !$appointment->review)
                                            <button type="button" class="btn btn-outline-success btn-sm mb-1" onclick="toggleReviewForm({{ $appointment->id }})" title="Tamamlandı">
                                                <i class="bi bi-check-circle"></i> Tamamla
                                            </button>

                                            {{-- Değerlendirme Formu --}}
                                            <form method="POST" action="{{ route('client.appointments.complete', $appointment->id) }}" class="review-form d-none mt-3" id="review-form-{{ $appointment->id }}">
                                                @csrf
                                                @method('PUT')

                                                <select class="form-select form-select-sm mb-2" name="rating" required>
                                                    <option value="" disabled selected>⭐ Değerlendirme</option>
                                                    @for($i = 1; $i <= 5; $i++)
                                                        <option value="{{ $i }}">{{ $i }} - {{ ['Çok Kötü', 'Kötü', 'Orta', 'İyi', 'Mükemmel'][$i-1] }}</option>
                                                    @endfor
                                                </select>

                                                <textarea name="comment" class="form-control form-control-sm mb-2" rows="2" placeholder="Yorumunuzu yazınız..." required></textarea>

                                                <button type="submit" class="btn btn-primary btn-sm">
                                                    <i class="bi bi-send"></i> Gönder
                                                </button>
                                            </form>

                                        @elseif($appointment->review)
                                            <span class="text-warning">⭐ {{ $appointment->review->rating }}/5</span><br>
                                            <em class="text-muted small">{{ $appointment->review->comment }}</em>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <div class="alert alert-info text-center">Henüz randevunuz bulunmuyor.</div>
    @endif
@endsection

@push('scripts')
    <script>
        function toggleReviewForm(id) {
            const form = document.getElementById('review-form-' + id);
            if (form) {
                form.classList.toggle('d-none');
            }
        }
    </script>
@endpush
