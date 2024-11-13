@extends('admin.admin_dashboard')

@section('admin')
<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <h5 class="mb-0">รับรองรายการการประชุม</h5>
                        </div>
                        <div class="col-auto">
                            <div class="d-flex gap-2">
                                <select id="meetingTypeFilter" class="form-select">
                                    <option value="">ทั้งหมด</option>
                                    @foreach($meeting_types as $type)
                                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card-body p-3">
                    <div class="row row-cols-1 row-cols-md-3 g-4" id="meetingCards">
                        @foreach ($my_meetings as $item)
                        <div class="col meeting-card show" data-meeting-type="{{ $item->meeting_type_id }}">
                            <div class="card h-100 border-0 shadow-sm">
                                <div class="d-flex flex-column justify-content-center p-3">
                                    <img src="{{ asset('uploads/no_image.jpg') }}" class="rounded-circle mx-auto d-block"
                                        width="90" height="90" alt="Meeting Image">
                                </div>
                                <div class="card-body p-3">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <h6 class="card-title mb-0">
                                            <a href="{{ route('meeting.detail', $item->id) }}"
                                               class="text-decoration-none text-dark hover-primary">
                                                {{ $item->meeting_agenda_title }}
                                            </a>
                                        </h6>
                                        <span class="badge bg-primary">
                                            {{ Str::limit($item->meeting_type->name, 10) }}
                                        </span>
                                    </div>

                                    <h6 class="card-subtitle text-muted mb-3">
                                        ครั้งที่ {{ $item->meeting_agenda_number }} / {{ $item->meeting_agenda_year }}
                                    </h6>

                                    <div class="mb-3">
                                        <small class="text-muted d-block">
                                            <i class="bi bi-person me-1"></i>
                                            สร้างโดย: {{ $item->user->first_name }} {{ $item->user->last_name }}
                                        </small>
                                        <small class="text-muted d-block">
                                            <i class="bi bi-calendar-event me-1"></i>
                                            ปรับปรุงข้อมูล: {{ $item->created_at->format('d M Y') }}
                                        </small>
                                    </div>

                                    @php
                                        $now = \Carbon\Carbon::now();
                                        $deadline = $item->approval_deadline;
                                        $isDeadlinePassed = $deadline ? $now->gt($deadline) : false;
                                        $daysUntilDeadline = $deadline ? $now->diffInDays($deadline, false) : null;
                                    @endphp

                                    <div class="mb-3">
                                        @if($deadline)
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-clock me-2"></i>
                                                <span class="badge {{ $isDeadlinePassed ? 'bg-danger' : ($daysUntilDeadline <= 2 ? 'bg-warning deadline-urgent' : 'bg-success') }}">
                                                    @if($isDeadlinePassed)
                                                        หมดเวลารับรอง: {{ \Carbon\Carbon::parse($deadline)->format('d/m/Y H:i') }}
                                                    @else
                                                        เหลือเวลารับรอง: {{ $now->diffForHumans($deadline, ['parts' => 2]) }}
                                                        <small>({{ \Carbon\Carbon::parse($deadline)->format('d/m/Y H:i') }})</small>
                                                    @endif
                                                </span>
                                            </div>
                                        @else
                                            <span class="badge bg-secondary">
                                                <i class="bi bi-exclamation-circle me-1"></i>
                                                ไม่ได้กำหนดเวลาสิ้นสุดการรับรอง
                                            </span>
                                        @endif
                                    </div>

                                    @php
                                        $section = App\Models\MeetingAgendaSection::where('meeting_agenda_id', $item->id)->get();
                                        $hasPermission = $item->hasPermission;
                                        $hasViewed = $item->hasViewed;
                                    @endphp

                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="badge bg-info">
                                            <i class="bi bi-list-check me-1"></i>
                                            วาระการประชุม: {{ count($section) }} รายการ
                                        </span>

                                        @if($hasViewed)
                                            <span class="badge bg-success">
                                                <i class="bi bi-eye-fill me-1"></i>
                                                ดูแล้ว
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="card-footer bg-white border-top-0 p-3">
                                    <div class="d-grid">
                                        @if ($hasPermission)
                                            <a href="{{ route('meeting.approval.detail', $item->id) }}"
                                                class="btn {{ (!$item->isAdmin && $item->isDeadlinePassed) ? 'btn-secondary' : 'btn-primary' }}"
                                                {{ (!$item->isAdmin && $item->isDeadlinePassed) ? 'disabled' : '' }}
                                                data-bs-toggle="tooltip"
                                                data-bs-placement="top"
                                                title="{{ (!$item->isAdmin && $item->isDeadlinePassed) ? 'หมดเวลารับรองแล้ว' : '' }}">
                                                <i class="bi {{ $hasViewed ? 'bi-eye-fill' : 'bi-eye' }} me-1"></i>
                                                {{ $hasViewed ? 'ดูรายละเอียดอีกครั้ง' : 'ดูรายละเอียด' }}
                                            </a>
                                        @else
                                            <button class="btn btn-secondary" disabled
                                                    data-bs-toggle="tooltip"
                                                    data-bs-placement="top"
                                                    title="ไม่มีสิทธิ์เข้าถึง">
                                                <i class="bi bi-lock-fill me-1"></i>
                                                ไม่มีสิทธิ์เข้าถึง
                                            </button>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">

<style>
    .meeting-card {
        opacity: 1;
        transition: all 0.3s ease-in-out;
    }

    .meeting-card.hidden {
        opacity: 0;
        transform: scale(0.95);
    }

    .form-select {
        min-width: 200px;
        padding: 0.5rem 2.25rem 0.5rem 0.75rem;
        font-size: 0.875rem;
        border-radius: 0.375rem;
        border: 1px solid #dee2e6;
        background-color: #fff;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }

    .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .badge {
        font-weight: 500;
        padding: 0.5em 0.75em;
        font-size: 0.75rem;
    }

    .badge small {
        display: block;
        font-size: 0.85em;
        opacity: 0.85;
        margin-top: 0.25em;
    }

    .deadline-urgent {
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0% { opacity: 1; }
        50% { opacity: 0.7; }
        100% { opacity: 1; }
    }

    .card {
        transition: transform 0.2s ease-in-out;
    }

    .card:hover {
        transform: translateY(-2px);
    }

    .hover-primary:hover {
        color: #0d6efd !important;
    }

    .btn:disabled {
        cursor: not-allowed;
    }

    .tooltip {
        font-size: 0.8125rem;
    }
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script>
$(document).ready(function() {
    const filterMeetings = (selectedType) => {
        const cards = $('.meeting-card');
        const noResults = $('.no-results');

        noResults.remove();

        if (selectedType) {
            cards.each(function() {
                const card = $(this);
                const meetingType = card.data('meeting-type');

                if (meetingType == selectedType) {
                    card.removeClass('hidden').fadeIn(300);
                } else {
                    card.addClass('hidden').fadeOut(300);
                }
            });

            setTimeout(() => {
                if ($('.meeting-card:visible').length === 0) {
                    $('#meetingCards').append(`
                        <div class="col-12 no-results">
                            <div class="alert alert-info d-flex align-items-center" role="alert">
                                <i class="bi bi-info-circle-fill me-2"></i>
                                <div>
                                    ไม่พบรายการที่ตรงกับการค้นหา
                                </div>
                            </div>
                        </div>
                    `);
                }
            }, 350);
        } else {
            cards.removeClass('hidden').fadeIn(300);
        }
    };

    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });

    // Initial filter state
    const initialType = $('#meetingTypeFilter').val();
    if (initialType) {
        filterMeetings(initialType);
    }

    // Filter on change
    $('#meetingTypeFilter').on('change', function() {
        const selectedType = $(this).val();
        filterMeetings(selectedType);
    });
});
</script>

@endsection
