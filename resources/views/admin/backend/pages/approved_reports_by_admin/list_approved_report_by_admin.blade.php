@extends('admin.admin_dashboard')
@section('admin')

@php
    use Carbon\Carbon;

    // ตั้งค่า locale เป็นไทย
    Carbon::setLocale('th');

    // แปลงวันที่เป็น Carbon instance
    $meeting_date = Carbon::parse($report->meeting_agenda_date);

    // อาร์เรย์สำหรับชื่อวันและเดือนภาษาไทย
    $thai_days = ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'];
    $thai_months = [
        1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม', 4 => 'เมษายน', 5 => 'พฤษภาคม', 6 => 'มิถุนายน',
        7 => 'กรกฎาคม', 8 => 'สิงหาคม', 9 => 'กันยายน', 10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม',
    ];

    // สร้างสตริงวันที่ภาษาไทย
    $thai_date = "วัน" . $thai_days[$meeting_date->dayOfWeek] . "ที่ " . $meeting_date->day . ' ' . $thai_months[$meeting_date->month] . ' พ.ศ. ' . ($meeting_date->year + 543);
@endphp

<div class="page-content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-body">
                <!-- ส่วนแสดงผู้รับรอง -->
                <div class="mb-4">
                    <span class="badge bg-success px-3 py-2">
                        <i class="fas fa-check-circle me-1"></i>
                        รับรองแล้วโดย: {{ $report->adminApprovedBy->username }}
                        เมื่อ {{ \Carbon\Carbon::parse($report->admin_approved_at)->format('d/m/Y H:i') }}
                    </span>
                </div>

                <div class="mb-4">
                    <a href="{{ route('all.approved.meeting.reports') }}" class="btn btn-danger btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> กลับ
                    </a>
                </div>

                <!-- เพิ่มปุ่ม Export -->
                <div class="position-absolute top-0 end-0 mt-3 me-3">
                    {{-- <a href="{{ route('reports.export', ['id' => $report->id, 'type' => 'pdf']) }}" class="btn btn-danger me-2">Export เป็น PDF</a> --}}
                    <a href="{{ route('reports.export', ['id' => $report->id, 'type' => 'word']) }}" class="btn btn-primary">Export เป็น Word</a>
                </div>

                <div class="word-document p-5 bg-white shadow-sm">
                    <!-- ส่วนหัวรายงาน -->
                    <div class="text-center mb-5">
                        {{-- <h4 class="report-title">รายงานการประชุม{{ $report->meeting_type->name }}</h4> --}}
                        <h5>{{ $report->meeting_agenda_title }}</h5>
                        <h5>ครั้งที่ {{ $report->meeting_agenda_number }}/{{ $report->meeting_agenda_year }}</h5>
                        <h5>{{ $thai_date }}</h5>
                        <h5>เวลา {{ \Carbon\Carbon::parse($report->meeting_agenda_time)->format('H:i') }} น.</h5>
                        <h5>{{ $report->meeting_location }}</h5>
                    </div>

                    <!-- เนื้อหารายงาน -->
                    @foreach($report->sections as $section)
                        <div class="section-content mb-4">
                            <h5 class="section-title">{{ $section->section_title }}</h5>

                            @if($section->description)
                                <div class="section-description mb-3">
                                    {!! $section->description !!}
                                </div>
                            @endif

                            <!-- แสดงเนื้อหา Lectures และ Items -->
                            @foreach($section->meetingAgendaLectures as $lecture)
                                <div class="lecture-content ms-4 mb-3">
                                    <h6 class="lecture-title">{{ $lecture->lecture_title }}</h6>
                                    @if($lecture->content)
                                        <div class="lecture-text mb-2">
                                            {!! $lecture->content !!}
                                        </div>
                                    @endif

                                    @foreach($lecture->meetingAgendaItems as $item)
                                        <div class="item-content ms-4 mb-2">
                                            <p class="item-title mb-1">{{ $item->item_title }}</p>
                                            @if($item->content)
                                                <div class="item-text">
                                                    {!! $item->content !!}
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach

                            <!-- แสดงการรับรองท้ายแต่ละวาระ -->
                            @if($section->approvalDetails->isNotEmpty())
                                <div class="approval-section mt-3 p-3 bg-light rounded">
                                    <h6 class="text-primary mb-2">การรับรองวาระนี้</h6>
                                    <div class="approval-list">
                                        @foreach($section->approvalDetails as $detail)
                                            <div class="approval-item mb-2">
                                                <div class="d-flex align-items-center">
                                                    <span class="badge {{ $detail->approval_type == 'no_changes' ? 'bg-success' : 'bg-warning' }} me-2">
                                                        {{ $detail->approval_type == 'no_changes' ? 'รับรองโดยไม่มีแก้ไข' : 'รับรองโดยมีแก้ไข' }}
                                                    </span>
                                                    <span class="text-muted">
                                                        โดย {{ $detail->meetingApproval->user->first_name }}
                                                        {{ $detail->meetingApproval->user->last_name }}
                                                        ({{ $detail->meetingApproval->user->position->name ?? '-' }})
                                                    </span>
                                                </div>
                                                @if($detail->comments)
                                                    <div class="ms-4 mt-1 text-muted">
                                                        <small><i class="fas fa-comment-dots me-1"></i>{{ $detail->comments }}</small>
                                                    </div>
                                                @endif
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</div>

<style>
/* Word Document Styling */
.word-document {
    font-family: 'TH Sarabun New', 'Sarabun', sans-serif;
    font-size: 16px;
    line-height: 1.6;
    color: #333;
    border: 1px solid #ddd;
    border-radius: 4px;
    background-color: white;
    max-width: 210mm; /* A4 width */
    margin: 0 auto;
}

.report-title {
    font-size: 24px;
    font-weight: bold;
    margin-bottom: 1rem;
}

.section-title {
    font-size: 20px;
    font-weight: bold;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #333;
    margin-bottom: 1rem;
}

.section-description {
    text-align: justify;
    padding-left: 1.5rem;
}

.lecture-title {
    font-size: 18px;
    font-weight: bold;
    margin-bottom: 0.5rem;
    color: #444;
}

.lecture-text {
    padding-left: 1rem;
    text-align: justify;
}

.item-title {
    font-weight: bold;
    color: #555;
}

.item-text {
    padding-left: 1rem;
    text-align: justify;
}

/* Print Styles */
@media print {
    .word-document {
        margin: 0;
        padding: 15mm;
        border: none;
        background: none;
        box-shadow: none;
    }

    .btn, .badge {
        display: none;
    }
}
</style>

<script>
// Initialize rich text editor if needed
document.addEventListener('DOMContentLoaded', function() {
    // Add any JavaScript for handling printing, etc.
});
</script>

@endsection
