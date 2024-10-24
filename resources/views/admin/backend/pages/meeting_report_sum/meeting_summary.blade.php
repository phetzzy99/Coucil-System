@extends('admin.admin_dashboard')
@section('admin')
<div class="page-content">
    <div class="container-fluid">
        <!-- Meeting Content -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            รายงานการประชุม
                            <span class="text-muted">
                                ครั้งที่ {{ $meetingAgenda->meeting_agenda_number }}/{{ $meetingAgenda->meeting_agenda_year }}
                            </span>
                        </div>
                        <div>
                            <a href="{{ route('meeting.report.summary.index') }}" class="btn btn-danger btn-sm">
                                <i class="fas fa-arrow-left"></i> กลับ
                            </a>
                        </div>
                    </div>
                </h5>

                <!-- ข้อมูลทั่วไปของการประชุม -->
                <div class="mb-4 p-3 bg-light rounded">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>ประเภทการประชุม:</strong> {{ $meetingAgenda->meeting_type->name }}</p>
                            <p class="mb-2"><strong>วันที่ประชุม:</strong> {{ \Carbon\Carbon::parse($meetingAgenda->meeting_agenda_date)->format('d/m/Y') }}</p>
                            <p class="mb-2"><strong>เวลา:</strong> {{ \Carbon\Carbon::parse($meetingAgenda->meeting_agenda_time)->format('H:i') }} น.</p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>สถานที่:</strong> {{ $meetingAgenda->meeting_location }}</p>
                            <p class="mb-2"><strong>รูปแบบการประชุม:</strong> {{ $meetingAgenda->meetingFormat->name }}</p>
                        </div>
                    </div>
                </div>

                @foreach($meetingAgenda->sections as $section)
                    <div class="mb-4">
                        <!-- หัวข้อส่วน -->
                        <div class="section-header bg-light p-3 rounded">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="text-primary mb-0">{{ $section->section_title }}</h6>
                                <span class="badge bg-info">
                                    {{ $section->approvalDetails->count() }} การรับรอง
                                </span>
                            </div>
                        </div>

                        <!-- เนื้อหาส่วน -->
                        @if($section->description)
                            <div class="section-content mt-3 p-3">
                                {!! $section->description !!}
                            </div>
                        @endif

                        <!-- Lectures และ Items -->
                        @foreach($section->meetingAgendaLectures as $lecture)
                            <div class="lecture-content mt-3 p-3 border-start border-primary border-3">
                                <h6 class="text-dark">{{ $lecture->lecture_title }}</h6>
                                @if($lecture->content)
                                    <div class="lecture-text mb-3">
                                        {!! $lecture->content !!}
                                    </div>
                                @endif

                                <!-- Items ภายใน Lecture -->
                                @foreach($lecture->meetingAgendaItems as $item)
                                    <div class="item-content ms-4 mt-2 p-2 border-start border-secondary">
                                        <h6 class="text-secondary">{{ $item->item_title }}</h6>
                                        @if($item->content)
                                            <div class="item-text">
                                                {!! $item->content !!}
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @endforeach

                        <!-- ส่วนแสดงการรับรองและความคิดเห็น -->
                        @if(isset($approvalsBySection[$section->id]))
                            <div class="mt-3">
                                <div class="card border shadow-none">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">การรับรองและความคิดเห็น</h6>
                                    </div>
                                    <div class="card-body">
                                        <!-- [ส่วนแสดงตารางการรับรองและความคิดเห็นคงเดิม] -->
                                        @include('admin.backend.pages.meeting_report_sum.partials.approval_table', [
                                            'approvals' => $approvalsBySection[$section->id]
                                        ])
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</div>

<style>
    .section-content {
        background-color: #fff;
        border-radius: 4px;
    }

    .lecture-content {
        background-color: #f8f9fa;
        border-radius: 4px;
    }

    .item-content {
        background-color: #fff;
        border-radius: 4px;
    }

    .lecture-text, .item-text {
        font-size: 0.95rem;
        line-height: 1.6;
    }

    /* [CSS สำหรับส่วนอื่นๆ คงเดิม] */
</style>
@endsection
