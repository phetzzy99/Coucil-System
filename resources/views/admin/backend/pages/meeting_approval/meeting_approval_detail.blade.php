@extends('admin.admin_dashboard')

@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
@php
    use Carbon\Carbon;

    // ตั้งค่า locale เป็นไทย
    Carbon::setLocale('th');

    // แปลงวันที่เป็น Carbon instance
    $meeting_date = Carbon::parse($my_meetings->meeting_date);

    // อาร์เรย์สำหรับชื่อวันและเดือนภาษาไทย
    $thai_days = ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'];
    $thai_months = [
        1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม', 4 => 'เมษายน', 5 => 'พฤษภาคม', 6 => 'มิถุนายน',
        7 => 'กรกฎาคม', 8 => 'สิงหาคม', 9 => 'กันยายน', 10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม',
    ];

    // สร้างสตริงวันที่ภาษาไทย
    $thai_date = "วัน" . $thai_days[$meeting_date->dayOfWeek] . "ที่ " . $meeting_date->day . ' ' . $thai_months[$meeting_date->month] . ' พ.ศ. ' . ($meeting_date->year + 543);
@endphp

<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-12 col-lg-10">
            <div class="card shadow-sm mb-4">
                <div class="card-body p-5">
                    <div class="text-center mb-5">
                        <h5 class="mb-3 text-primary">{{ $my_meetings->meeting_agenda_title }}</h5>
                        <div class="d-inline-block p-3 border border-primary rounded">
                            <p class="mb-1"><strong>การประชุมครั้งที่:</strong> {{ $my_meetings->meeting_agenda_number }} / {{ $my_meetings->meeting_agenda_year }}</p>
                            <p class="mb-1"><strong>วันที่:</strong> {{ $thai_date }}</p>
                            <p class="mb-0"><strong>สถานที่:</strong> {{ $my_meetings->meeting_location }}</p>
                        </div>
                    </div>

                    <!-- แสดงรายการการรับรองที่มีอยู่ -->
                    @if($approvals->isNotEmpty())
                        <h6 class="mb-3 text-danger">การรับรองที่มีอยู่:</h6>
                        <ul class="list-group mb-4">
                            @foreach($approvals as $approval)
                                <li class="list-group-item bg-danger text-white">
                                    <strong>ผู้รับรอง:</strong> {{ $approval->user->first_name }}
                                    <strong>วันที่รับรอง:</strong> {{ $approval->approval_date }}
                                    <button class="btn btn-sm btn-danger float-end show-details" data-approval-id="{{ $approval->id }}">รายละเอียด</button>
                                </li>
                            @endforeach
                        </ul>
                    @endif

                    <!-- ฟอร์มสำหรับการรับรองใหม่ -->
                    <form id="meetingApprovalForm" action="{{ route('meeting.approval.store', $my_meetings->id) }}" method="POST">
                        @csrf
                        <input type="hidden" name="meeting_agenda_id" value="{{ $my_meetings->id }}">
                        <input type="hidden" name="meeting_type_id" value="{{ $my_meetings->meeting_type_id }}">
                        <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                        <input type="hidden" name="committee_category_id" value="{{ $my_meetings->committee_category_id }}">
                        <input type="hidden" name="meeting_format_id" value="{{ $my_meetings->meeting_format_id }}">
                        <input type="hidden" name="rule_of_meeting_id" value="{{ $my_meetings->rule_of_meeting_id }}">
                        <input type="hidden" name="regulation_meeting_id" value="{{ $my_meetings->regulation_meeting_id }}">

                        @php
                            $sections = App\Models\MeetingAgendaSection::where('meeting_agenda_id', $my_meetings->id)
                                ->orderBy('id', 'asc')
                                ->get();
                        @endphp

                        @foreach ($sections as $index => $section)
                        <div class="mb-5">
                            <div class="d-flex align-items-center mb-3 pb-2 border-bottom">
                                <h5 class="mb-0">{{ $section->section_title }}</h5>
                            </div>

                            @php
                                $hasContent = false;
                                if ($section->description != null) {
                                    $hasContent = true;
                                }
                                $lectures = App\Models\MeetingAgendaLecture::where('meeting_agenda_section_id', $section->id)->get();
                                $agendaItems = App\Models\MeetingAgendaItems::where('meeting_agenda_section_id', $section->id)
                                    ->orderBy('id', 'asc')
                                    ->get();
                                if ($lectures->count() > 0 || $agendaItems->count() > 0) {
                                    $hasContent = true;
                                }
                            @endphp

                            @if ($section->description != null)
                                <div class="ms-4 mb-3">
                                    <p>{!! $section->description !!}</p>
                                </div>
                            @endif

                            @foreach ($lectures as $lectureIndex => $lecture)
                                <div class="ms-4 mb-3">
                                    <div class="ms-4 bg-light p-3 rounded">
                                        <h6 class="mb-2">{{ $lecture->lecture_title }}</h6>
                                        @if ($lecture->content)
                                            <div class="mb-2">{!! $lecture->content !!}</div>
                                        @endif
                                    </div>

                                    @php
                                        $lectureItems = $agendaItems->where('meeting_agenda_lecture_id', $lecture->id);
                                    @endphp

                                    @if ($lectureItems->count() > 0)
                                        <ul class="list-unstyled ms-4">
                                            @foreach ($lectureItems as $itemIndex => $item)
                                                <li class="mb-3">
                                                    <div class="ms-4 bg-light p-3 rounded">
                                                        <h6 class="mb-2">{{ $item->item_title }}</h6>
                                                        @if ($item->content)
                                                            <div>{!! $item->content !!}</div>
                                                        @endif
                                                    </div>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            @endforeach

                            @php
                                $sectionItems = $agendaItems->whereNull('meeting_agenda_lecture_id');
                            @endphp

                            @if ($sectionItems->count() > 0)
                                <ul class="list-unstyled ms-4">
                                    @foreach ($sectionItems as $itemIndex => $item)
                                        <li class="mb-3">
                                            <div class="ms-4 bg-light p-3 rounded">
                                                <h6 class="mb-2">{{ $section->section_title }}.{{ $itemIndex + 1 }} {{ $item->item_title }}</h6>
                                                @if ($item->content)
                                                    <div>{!! $item->content !!}</div>
                                                @endif
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            @if (!$hasContent)
                                <p class="ms-4 text-muted fst-italic">ไม่มีรายการในวาระนี้</p>
                            @else
                                <div class="approval-section mt-3">
                                    <div class="mb-4 p-3 border border-danger rounded" style="width: 100%;">
                                        <h6 class="mb-3">การรับรองรายงานการประชุม - {{ $section->section_title }}</h6>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="approvals[{{ $section->id }}][type]" id="noChanges_{{ $section->id }}" value="no_changes" checked>
                                            <label class="form-check-label" for="noChanges_{{ $section->id }}">
                                                รับรองโดยไม่มีข้อแก้ไข
                                            </label>
                                        </div>
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="approvals[{{ $section->id }}][type]" id="withChanges_{{ $section->id }}" value="with_changes">
                                            <label class="form-check-label" for="withChanges_{{ $section->id }}">
                                                รับรองโดยมีข้อแก้ไข
                                            </label>
                                        </div>
                                        <div id="commentsSection_{{ $section->id }}" class="mt-3" style="display: none;">
                                            <textarea class="form-control" name="approvals[{{ $section->id }}][comments]" rows="4" placeholder="กรุณาระบุข้อแก้ไข"></textarea>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                        @endforeach

                        <div style="width: 100%;" class="p-3 border border-danger rounded">
                            @php
                                $meeting_formats = App\Models\MeetingFormat::where('id', $my_meetings->meeting_format_id)->get();
                            @endphp

                            @if ($meeting_formats->count() > 0)
                                <ul class="list-group list-group-flush">
                                    @foreach ($meeting_formats as $meeting_format)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <p class="mb-0"><strong>รูปแบบการประชุม:</strong> {{ $meeting_format->name }}</p>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted">ไม่มีข้อมูลรูปแบบการประชุม</p>
                            @endif

                            @php
                                $regulations = App\Models\RegulationMeeting::where('id', $my_meetings->regulation_meeting_id)->get();
                            @endphp

                            @if ($regulations->count() > 0)
                                <ul class="list-group list-group-flush">
                                    @foreach ($regulations as $regulation)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <p class="mb-0"><strong>ระเบียบ:</strong> <a href="{{ asset($regulation->regulation_pdf) }}" target="_blank"><span class="badge rounded-pill bg-info text-dark">ดูรายละเอียด</span></a></p>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted">ไม่มีข้อมูลระเบียบ</p>
                            @endif
                        </div>

                        <div style="margin-top: 2rem;"><hr></div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">บันทึกการรับรอง</button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal สำหรับแสดงรายละเอียดการรับรอง -->
<div class="modal fade" id="approvalDetailsModal" tabindex="-1" aria-labelledby="approvalDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="approvalDetailsModalLabel">รายละเอียดการรับรอง</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="approvalDetailsContent">
                <!-- รายละเอียดการรับรองจะถูกใส่ที่นี่ด้วย JavaScript -->
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('input[name^="approvals"][name$="[type]"]').change(function() {
            var sectionId = $(this).attr('name').match(/\d+/)[0];
            if ($(this).val() === 'with_changes') {
                $('#commentsSection_' + sectionId).show();
            } else {
                $('#commentsSection_' + sectionId).hide();
            }
        });

        $('#meetingApprovalForm').submit(function(e) {
            e.preventDefault();
            var formData = $(this).serialize();

            $.ajax({
                url: $(this).attr('action'),
                method: 'POST',
                data: formData,
                success: function(response) {
                    alert(response.message);
                    window.location.href = "{{ route('all.meeting.approval') }}";
                },
                error: function(xhr) {
                    alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล');
                    console.error(xhr.responseText);
                }
            });
        });

        // เพิ่ม event listener สำหรับปุ่มแสดงรายละเอียด
        $('.show-details').click(function() {
            var approvalId = $(this).data('approval-id');
            $.ajax({
                url: '/meeting-approval-details/' + approvalId,
                method: 'GET',
                success: function(response) {
                    $('#approvalDetailsContent').html(response);
                    $('#approvalDetailsModal').modal('show');
                },
                error: function(xhr) {
                    alert('เกิดข้อผิดพลาดในการโหลดข้อมูล');
                    console.error(xhr.responseText);
                }
            });
        });
    });
</script>

@endsection

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;700&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Kanit', sans-serif;
        line-height: 1.6;
        background-color: #f8f9fa;
    }
    h5 {
        font-size: 1.25rem;
        font-weight: 700;
        color: #007bff;
    }
    h6 {
        font-size: 1rem;
        font-weight: 600;
        color: #343a40;
    }
    p {
        font-size: 0.9rem;
        font-weight: 400;
        color: #343a40;
    }
    strong {
        font-weight: 500;
    }
    .card {
        border: none;
        border-radius: 15px;
    }
    .card-body {
        background-color: #ffffff;
    }
    .border-primary {
        border-color: #007bff !important;
    }
    .text-primary {
        color: #007bff !important;
    }
    .bg-light {
        background-color: #f1f3f5 !important;
    }
</style>
@endpush
