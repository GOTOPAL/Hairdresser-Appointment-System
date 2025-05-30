@extends('client.layout.app')

@section('content')
    <h2 class="page-title mb-4">📅 Randevu Takvimi</h2>

    <div id="calendar"></div>

    <!-- Modal -->
    <div class="modal fade" id="appointmentModal" tabindex="-1" aria-labelledby="appointmentModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="appointmentModalLabel">Randevu Detayı</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Kapat"></button>
                </div>
                <div class="modal-body" id="modalContent">
                    Yükleniyor...
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.css' rel='stylesheet' />
@endpush

@push('scripts')
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const calendarEl = document.getElementById('calendar');
            const calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                locale: 'tr',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,listWeek'
                },
                events: '/client/calendar/data',
                eventDidMount: function (info) {
                    // Renk ayarı direkt JSON'dan geldiği için ekstra stil gerekmez
                    info.el.style.border = '1px solid #ccc';
                },
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
