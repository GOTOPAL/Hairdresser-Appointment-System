@extends('client.layout.app')

@section('content')
    <h2 class="page-title mb-3">🎉 Hoşgeldiniz, {{ Auth::user()->name }}!</h2>

    <div class="row gx-2 gy-1 mb-3 align-items-start">
        {{-- Özet Kartlar --}}
        <div class="col-lg-8">
            <div class="row row-cols-1 row-cols-sm-2 gx-2 gy-1">
                <div class="col">
                    <div class="card shadow-sm text-center border-0">
                        <div class="card-body py-2 px-2">
                            <div class="fs-6 text-warning">📅</div>
                            <h6 class="mt-1 text-muted small">Bekleyen Randevu</h6>
                            <div class="fw-bold fs-6">{{ $pendingCount }}</div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow-sm text-center border-0">
                        <div class="card-body py-2 px-2">
                            <div class="fs-6 text-success">✅</div>
                            <h6 class="mt-1 text-muted small">Onaylı Randevu</h6>
                            <div class="fw-bold fs-6">{{ $approvedCount }}</div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow-sm text-center border-0">
                        <div class="card-body py-2 px-2">
                            <div class="fs-6 text-primary">⭐</div>
                            <h6 class="mt-1 text-muted small">Yorum Sayısı</h6>
                            <div class="fw-bold fs-6">{{ $reviewCount }}</div>
                        </div>
                    </div>
                </div>
                <div class="col">
                    <div class="card shadow-sm text-center border-0">
                        <div class="card-body py-2 px-2">
                            <div class="fs-6 text-info">🛠</div>
                            <h6 class="mt-1 text-muted small">Toplam Hizmet</h6>
                            <div class="fw-bold fs-6">{{ $serviceCount }}</div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Yaklaşan Randevu --}}
            @if($nextAppointment)
                <div class="card shadow-sm border-0 mt-3">
                    <div class="card-body p-3">
                        <h5 class="card-title">🕒 En Yakın Randevunuz</h5>
                        <p class="mb-1"><strong>Kuaför:</strong> {{ $nextAppointment->hairdresser->user->name }}</p>
                        <p class="mb-1"><strong>Hizmet:</strong> {{ $nextAppointment->service->name }}</p>
                        <p class="mb-1"><strong>Tarih:</strong> {{ \Carbon\Carbon::createFromTimestamp(strtotime(str_replace([':AM', ':PM'], [' AM', ' PM'], $nextAppointment->date)))->format('d.m.Y') }}</p>
                        <p class="mb-1"><strong>Saat:</strong> {{ \Carbon\Carbon::createFromTimestamp(strtotime(str_replace([':AM', ':PM'], [' AM', ' PM'], $nextAppointment->time)))->format('H:i') }}</p>
                    </div>
                </div>
            @endif

            {{-- Son 3 Randevu --}}
            <div class="card shadow-sm border-0 mt-3">
                <div class="card-body p-3">
                    <h5 class="card-title mb-3">🗂 Son Randevular</h5>
                    @forelse($lastAppointments as $appt)
                        <div class="mb-2 pb-2 border-bottom">
                            <p class="mb-1"><strong>{{ $appt->service->name }}</strong> - {{ $appt->hairdresser->user->name }}</p>
                            <small class="text-muted">
                                {{ \Carbon\Carbon::createFromTimestamp(strtotime(str_replace([':AM', ':PM'], [' AM', ' PM'], $appt->date)))->format('d.m.Y') }} -
                                {{ \Carbon\Carbon::createFromTimestamp(strtotime(str_replace([':AM', ':PM'], [' AM', ' PM'], $appt->time)))->format('H:i') }}
                            </small><br>
                            <span class="badge bg-{{ $appt->status === 'completed' ? 'success' : ($appt->status === 'rejected' ? 'danger' : 'secondary') }} text-capitalize">{{ $appt->status }}</span>
                            @if($appt->review)
                                <div class="mt-1 text-warning">⭐ {{ $appt->review->rating }} / 5 - <em class="text-muted">{{ $appt->review->comment }}</em></div>
                            @endif
                        </div>
                    @empty
                        <p class="text-muted">Randevu geçmişiniz bulunmuyor.</p>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Mini Takvim --}}
        <div class="col-lg-4">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-3">
                    <h5 class="card-title mb-3">📆 Mini Takvim</h5>
                    <div id="mini-calendar" style="min-height: 320px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Randevu Detayı</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                </div>
                <div class="modal-body" id="modalContent">
                    Yükleniyor...
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('mini-calendar');
            if (!calendarEl) return;

            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'listWeek',
                locale: 'tr',
                height: 'auto',
                headerToolbar: {
                    left: '',
                    center: 'title',
                    right: ''
                },
                events: '/client/calendar/data',
                eventClick: function (info) {
                    const event = info.event;
                    const content = `
                        <p><strong>Kuaför:</strong> ${event.extendedProps.hairdresser}</p>
                        <p><strong>Hizmet:</strong> ${event.extendedProps.service}</p>
                        <p><strong>Tarih:</strong> ${event.start.toLocaleDateString()}</p>
                        <p><strong>Saat:</strong> ${event.start.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'})}</p>
                        <p><strong>Durum:</strong> ${event.extendedProps.status}</p>
                    `;
                    document.getElementById('modalContent').innerHTML = content;
                    new bootstrap.Modal(document.getElementById('appointmentModal')).show();
                }
            });

            calendar.render();
        });
    </script>
@endpush
