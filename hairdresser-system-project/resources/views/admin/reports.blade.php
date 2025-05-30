@extends('admin.layout.app')

@section('content')
    <h2 class="page-title mb-4">📈 Raporlar</h2>

    {{-- Günlük Randevu Sayısı --}}
    <div class="card p-4 mb-4">
        <h5 class="mb-3">📅 Son 7 Günlük Randevu Sayısı</h5>
        <div style="overflow-x: auto;">
            <canvas id="appointmentsChart" style="min-width: 100%; max-height: 300px;"></canvas>
        </div>
    </div>

    {{-- Hizmet Bazlı Ortalama Puan --}}
    <div class="card p-4">
        <h5 class="mb-3">🛠️ Hizmetlere Göre Ortalama Puan</h5>
        <div style="overflow-x: auto;">
            <canvas id="servicesChart" style="min-width: 100%; max-height: 400px;"></canvas>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Günlük randevu sayısı - Line chart
        const ctxAppointments = document.getElementById('appointmentsChart').getContext('2d');
        new Chart(ctxAppointments, {
            type: 'line',
            data: {
                labels: {!! json_encode($dateLabels) !!},
                datasets: [{
                    label: 'Randevu Sayısı',
                    data: {!! json_encode($dateData) !!},
                    backgroundColor: 'rgba(52, 152, 219, 0.15)',
                    borderColor: '#3498db',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4,
                    pointRadius: 4,
                    pointBackgroundColor: '#3498db'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: { beginAtZero: true }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });

        // Hizmet bazlı ortalama puan - Horizontal bar chart
        const ctxServices = document.getElementById('servicesChart').getContext('2d');
        new Chart(ctxServices, {
            type: 'bar',
            data: {
                labels: {!! json_encode($services->pluck('name')) !!},
                datasets: [{
                    label: 'Ortalama Puan',
                    data: {!! json_encode($services->pluck('average_rating')) !!},
                    backgroundColor: '#2ecc71',
                    borderRadius: 6,
                }]
            },
            options: {
                indexAxis: 'y',
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    x: {
                        beginAtZero: true,
                        max: 5
                    }
                },
                plugins: {
                    legend: { display: false }
                }
            }
        });
    </script>
@endpush
