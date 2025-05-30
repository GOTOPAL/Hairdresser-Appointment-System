@extends('hairdresser.layout.app')

@section('content')
    <h2 class="page-title mb-4">📅 Randevular</h2>

    @php
        $pending = $appointments->where('status', 'pending');
        $approved = $appointments->where('status', 'approved');
        $rejected = $appointments->where('status', 'rejected');
        $completed = $appointments->where('status', 'completed');
    @endphp

    {{-- Bootstrap nav-tabs --}}
    <ul class="nav nav-tabs mb-4" id="appointmentTabs" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link active" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab" aria-controls="pending" aria-selected="true">
                ⏳ Onay Bekleyen ({{ $pending->count() }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="approved-tab" data-bs-toggle="tab" data-bs-target="#approved" type="button" role="tab" aria-controls="approved" aria-selected="false">
                ✅ Onaylanan ({{ $approved->count() }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="rejected-tab" data-bs-toggle="tab" data-bs-target="#rejected" type="button" role="tab" aria-controls="rejected" aria-selected="false">
                ❌ Reddedilen ({{ $rejected->count() }})
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link" id="completed-tab" data-bs-toggle="tab" data-bs-target="#completed" type="button" role="tab" aria-controls="completed" aria-selected="false">
                ✔️ Tamamlanan ({{ $completed->count() }})
            </button>
        </li>
    </ul>

    {{-- Tab Panelleri --}}
    <div class="tab-content" id="appointmentTabsContent">
        @foreach (['pending' => $pending, 'approved' => $approved, 'rejected' => $rejected, 'completed' => $completed] as $key => $list)
            <div class="tab-pane fade {{ $key === 'pending' ? 'show active' : '' }}" id="{{ $key }}" role="tabpanel" aria-labelledby="{{ $key }}-tab">
                @if($list->count())
                    <div class="row g-3">
                        @foreach($list as $appointment)
                            @php
                                $rawDate = str_replace([':AM', ':PM'], [' AM', ' PM'], $appointment->date);
                                $formattedDate = strtotime($rawDate) ? \Carbon\Carbon::createFromTimestamp(strtotime($rawDate))->format('d.m.Y') : 'Tarih Hatalı';

                                $formattedTime = strtotime($appointment->time) ? \Carbon\Carbon::createFromTimestamp(strtotime($appointment->time))->format('H:i') : 'Saat Hatalı';
                            @endphp

                            <div class="col-md-6">
                                <div class="card {{ isset($highlightId) && $highlightId == $appointment->id ? 'border-primary shadow-sm' : '' }}">
                                    <div class="card-body">
                                        <h5 class="card-title mb-3">
                                            <i class="bi bi-person-fill me-2"></i>{{ $appointment->client->user->name ?? '-' }}
                                        </h5>
                                        <p class="mb-1"><strong>Hizmet:</strong> {{ $appointment->service->name }}</p>
                                        <p class="mb-1"><strong>Tarih:</strong> {{ $formattedDate }}</p>
                                        <p class="mb-1"><strong>Saat:</strong> {{ $formattedTime }}</p>
                                        <p class="mb-2"><strong>Durum:</strong> <span class="badge bg-info text-dark">{{ ucfirst($appointment->status) }}</span></p>

                                        @if($appointment->status === 'pending')
                                            <form action="{{ route('hairdresser.appointments.updateStatus', $appointment->id) }}" method="POST" class="d-flex gap-2">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" name="action" value="approved" class="btn btn-success btn-sm flex-grow-1">
                                                    <i class="bi bi-check-lg"></i> Onayla
                                                </button>
                                                <button type="submit" name="action" value="rejected" class="btn btn-danger btn-sm flex-grow-1">
                                                    <i class="bi bi-x-lg"></i> Reddet
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="text-muted fst-italic">Bu kategoride randevu bulunmamaktadır.</p>
                @endif
            </div>
        @endforeach
    </div>

@endsection
