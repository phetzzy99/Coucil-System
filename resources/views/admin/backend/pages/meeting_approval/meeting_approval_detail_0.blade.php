@extends('admin.admin_dashboard')

@section('admin')
    {{-- <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script> --}}

    @php
        use Carbon\Carbon;

        // ตั้งค่า locale เป็นไทย
        Carbon::setLocale('th');

        // แปลงวันที่เป็น Carbon instance
        $meeting_date = Carbon::parse($my_meetings->meeting_date);
        $approval_date = Carbon::parse($my_meetings->approval_date);

        // อาร์เรย์สำหรับชื่อวันและเดือนภาษาไทย
        $thai_days = ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'];
        $thai_months = [
            1 => 'มกราคม',
            2 => 'กุมภาพันธ์',
            3 => 'มีนาคม',
            4 => 'เมษายน',
            5 => 'พฤษภาคม',
            6 => 'มิถุนายน',
            7 => 'กรกฎาคม',
            8 => 'สิงหาคม',
            9 => 'กันยายน',
            10 => 'ตุลาคม',
            11 => 'พฤศจิกายน',
            12 => 'ธันวาคม',
        ];

        // สร้างสตริงวันที่ภาษาไทย
        $thai_date =
            'วัน' .
            $thai_days[$meeting_date->dayOfWeek] .
            'ที่ ' .
            $meeting_date->day .
            ' ' .
            $thai_months[$meeting_date->month] .
            ' พ.ศ. ' .
            ($meeting_date->year + 543);
        $thai_date_approval =
            'วัน' .
            $thai_days[$approval_date->dayOfWeek] .
            'ที่ ' .
            $approval_date->day .
            ' ' .
            $thai_months[$approval_date->month] .
            ' พ.ศ. ' .
            ($approval_date->year + 543);
    @endphp

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="card shadow-sm mb-4">
                    <!-- ส่วนหัวของการ์ด -->
                    <div class="card-header bg-white py-3">
                        <div class="d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">{{ $meetingAgenda->meeting_agenda_title }}</h5>
                            <div>
                                @if (now()->lt($meetingAgenda->approval_deadline))
                                    <span class="badge bg-info">
                                        เหลือเวลา {{ now()->diffInDays($meetingAgenda->approval_deadline) }} วัน
                                    </span>
                                @else
                                    <span class="badge bg-danger">หมดเวลารับรอง</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="card-body p-5">
                        <!-- ข้อมูลการประชุม -->
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center border-bottom pb-3">
                                <div>
                                    <p class="mb-1"><strong>การประชุมครั้งที่:</strong>
                                        {{ $meetingAgenda->meeting_agenda_number }}/{{ $meetingAgenda->meeting_agenda_year }}
                                    </p>
                                    <p class="mb-1"><strong>วันที่:</strong> {{ $thai_date }}</p>
                                    <p class="mb-0"><strong>สถานที่:</strong> {{ $meetingAgenda->meeting_location }}</p>
                                </div>
                                <div class="text-end">
                                    <p class="mb-1"><strong>กำหนดรับรอง:</strong></p>
                                    <p class="mb-0">{{ $meetingAgenda->approval_deadline }}</p>
                                    {{-- <p class="mb-0">{{ $meetingAgenda->approval_deadline->format('d/m/Y H:i') }}</p> --}}
                                </div>
                            </div>
                        </div>

                        <!-- สถานะการรับรองปัจจุบัน -->
                        <div class="alert {{ $hasApproved ? 'alert-success' : 'alert-warning' }} mb-4">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <h6 class="mb-0">สถานะการรับรอง</h6>
                                    <p class="mb-0">
                                        @if ($hasApproved)
                                            คุณได้รับรองการประชุมนี้แล้ว
                                            <small>(เมื่อ {{ $approvals->where('user_id', Auth::id())->first()->approval_date }})</small>
                                            <a href="{{ route('meeting.approval.edit', $meetingAgenda->id) }}"
                                            class="btn btn-sm btn-warning ms-2"
                                            onclick="return confirm('คุณต้องการแก้ไขการรับรองใช่หรือไม่?')">
                                                <i class="fas fa-edit"></i> แก้ไขการรับรอง
                                            </a>
                                        @endif
                                        {{-- @if ($hasApproved)
                                            คุณได้รับรองการประชุมนี้แล้ว
                                            <small>(เมื่อ
                                                {{ $approvals->where('user_id', Auth::id())->first()->approval_date }})</small>
                                            <button class="btn btn-sm btn-warning ms-2 edit-approval-btn">
                                                <i class="fas fa-edit"></i> แก้ไขการรับรอง
                                            </button>
                                        @else
                                            คุณยังไม่ได้รับรองการประชุมนี้
                                        @endif --}}
                                    </p>
                                </div>
                                <div class="text-end">
                                    <strong>ผู้รับรองทั้งหมด:</strong> {{ $approvals->count() }} คน
                                </div>
                            </div>
                        </div>

                        <!-- รายการผู้รับรองที่มีอยู่ -->
                        @if ($approvals->isNotEmpty())
                            <div class="mb-4">
                                <h6 class="border-bottom pb-2">รายการผู้รับรอง</h6>
                                <div class="table-responsive">
                                    <table class="table table-hover">
                                        <thead>
                                            <tr>
                                                <th>ผู้รับรอง</th>
                                                <th>วันที่รับรอง</th>
                                                <th>สถานะ</th>
                                                <th></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($approvals as $approval)
                                                <tr>
                                                    <td>{{ $approval->user->prefix_name_id }}
                                                        {{ $approval->user->first_name }} {{ $approval->user->last_name }}
                                                    </td>
                                                    <td>{{ $approval->approval_date }}</td>
                                                    {{-- <td>{{ $approval->approval_date->format('d/m/Y H:i') }}</td> --}}
                                                    <td>
                                                        <span
                                                            class="badge {{ $approval->status === 'approved' ? 'bg-success' : 'bg-warning' }}">
                                                            {{ $approval->status === 'approved' ? 'รับรองแล้ว' : 'รอดำเนินการ' }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <button type="button" class="btn btn-sm btn-info show-details"
                                                            data-bs-toggle="modal" data-bs-target="#approvalDetailsModal"
                                                            data-approval-id="{{ $approval->id }}">
                                                            <i class="fas fa-info-circle"></i> รายละเอียด
                                                        </button>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif

                        <!-- แบบฟอร์มการรับรอง -->
                        @if (!$hasApproved && now()->lt($meetingAgenda->approval_deadline))
                            <form id="meetingApprovalForm"
                                action="{{ route('meeting.approval.store', $meetingAgenda->id) }}" method="POST"
                                style="{{ $hasApproved && !session('editing_approval') ? 'display: none;' : '' }}">
                                @csrf

                                <input type="hidden" name="meeting_agenda_id" value="{{ $my_meetings->id }}">
                                <input type="hidden" name="meeting_type_id" value="{{ $my_meetings->meeting_type_id }}">
                                <input type="hidden" name="user_id" value="{{ Auth::user()->id }}">
                                <input type="hidden" name="committee_category_id"
                                    value="{{ $my_meetings->committee_category_id }}">
                                <input type="hidden" name="meeting_format_id"
                                    value="{{ $my_meetings->meeting_format_id }}">
                                <input type="hidden" name="rule_of_meeting_id"
                                    value="{{ $my_meetings->rule_of_meeting_id }}">
                                <input type="hidden" name="regulation_meeting_id"
                                    value="{{ $my_meetings->regulation_meeting_id }}">

                                @php
                                    $sections = App\Models\MeetingAgendaSection::where(
                                        'meeting_agenda_id',
                                        $my_meetings->id,
                                    )
                                        ->orderBy('id', 'asc')
                                        ->get();
                                @endphp

                                <!-- ส่วนของการวนลูปแสดงเนื้อหาและฟอร์มรับรอง -->
                                @foreach ($sections as $section)
                                    <div class="mb-5">
                                        <div class="card">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0">{{ $section->section_title }}</h6>
                                            </div>
                                            <div class="card-body">
                                                @php
                                                    // ตรวจสอบว่ามีเนื้อหาหรือไม่
                                                    $hasContent = false;

                                                    // ตรวจสอบ description
                                                    if ($section->description != null) {
                                                        $hasContent = true;
                                                    }

                                                    // ตรวจสอบ lectures และ items
                                                    $lectures = App\Models\MeetingAgendaLecture::where(
                                                        'meeting_agenda_section_id',
                                                        $section->id,
                                                    )->get();
                                                    $agendaItems = App\Models\MeetingAgendaItems::where(
                                                        'meeting_agenda_section_id',
                                                        $section->id,
                                                    )
                                                        ->orderBy('id', 'asc')
                                                        ->get();

                                                    if ($lectures->count() > 0 || $agendaItems->count() > 0) {
                                                        $hasContent = true;
                                                    }
                                                @endphp

                                                <!-- แสดงเนื้อหาของวาระ -->
                                                @if ($section->description != null)
                                                    <div class="mb-4">
                                                        <div class="content-section">
                                                            {!! $section->description !!}
                                                        </div>
                                                    </div>
                                                @endif

                                                <!-- แสดงเนื้อหาของ Lecture -->
                                                @foreach ($lectures as $lecture)
                                                    <div class="ms-4 mb-3">
                                                        <h6 class="mb-2">{{ $lecture->lecture_title }}</h6>
                                                        @if ($lecture->content)
                                                            <div class="content-section mb-3">
                                                                {!! $lecture->content !!}
                                                            </div>
                                                        @endif

                                                        <!-- แสดง Items ภายใต้ Lecture -->
                                                        @php
                                                            $lectureItems = $agendaItems->where(
                                                                'meeting_agenda_lecture_id',
                                                                $lecture->id,
                                                            );
                                                        @endphp

                                                        @if ($lectureItems->count() > 0)
                                                            <div class="ms-4">
                                                                @foreach ($lectureItems as $item)
                                                                    <div class="mb-3">
                                                                        <h6 class="mb-2">{{ $item->item_title }}</h6>
                                                                        @if ($item->content)
                                                                            <div class="content-section">
                                                                                {!! $item->content !!}
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endforeach

                                                <!-- แสดง Items ที่ไม่อยู่ภายใต้ Lecture -->
                                                @php
                                                    $sectionItems = $agendaItems->whereNull(
                                                        'meeting_agenda_lecture_id',
                                                    );
                                                @endphp

                                                @if ($sectionItems->count() > 0)
                                                    <div class="ms-4">
                                                        @foreach ($sectionItems as $itemIndex => $item)
                                                            <div class="mb-3">
                                                                <h6 class="mb-2">
                                                                    {{ $section->section_title }}.{{ $itemIndex + 1 }}
                                                                    {{ $item->item_title }}</h6>
                                                                @if ($item->content)
                                                                    <div class="content-section">
                                                                        {!! $item->content !!}
                                                                    </div>
                                                                @endif
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @endif

                                                <!-- แสดงข้อความเมื่อไม่มีเนื้อหา -->
                                                @if (!$hasContent)
                                                    <div class="alert alert-info mb-0">
                                                        <i class="fas fa-info-circle me-2"></i> ไม่มีรายละเอียดในวาระนี้
                                                    </div>
                                                @endif

                                                <!-- ส่วนของการรับรอง - แสดงเฉพาะเมื่อมีเนื้อหา -->
                                                @if ($hasContent && !$hasApproved && now()->lt($meetingAgenda->approval_deadline))
                                                    <div class="approval-section mt-4 border-top pt-3">
                                                        <h6 class="mb-3">การรับรองวาระที่: {{ $section->section_title }}
                                                        </h6>

                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="radio"
                                                                name="approvals[{{ $section->id }}][type]"
                                                                value="no_changes" checked
                                                                id="noChanges_{{ $section->id }}">
                                                            <label class="form-check-label"
                                                                for="noChanges_{{ $section->id }}">
                                                                รับรองโดยไม่มีการแก้ไข
                                                            </label>
                                                        </div>

                                                        <div class="form-check mb-2">
                                                            <input class="form-check-input" type="radio"
                                                                name="approvals[{{ $section->id }}][type]"
                                                                value="with_changes" id="withChanges_{{ $section->id }}">
                                                            <label class="form-check-label"
                                                                for="withChanges_{{ $section->id }}">
                                                                รับรองโดยมีการแก้ไข
                                                            </label>
                                                        </div>

                                                        <div id="comments_{{ $section->id }}" class="mt-3"
                                                            style="display: none;">
                                                            <div class="form-group">
                                                                <textarea class="form-control" name="approvals[{{ $section->id }}][comments]" rows="3"
                                                                    placeholder="โปรดระบุรายละเอียดการแก้ไข"></textarea>
                                                                <div class="invalid-feedback">
                                                                    กรุณาระบุรายละเอียดการแก้ไข
                                                                </div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach

                                <div class="d-grid gap-2">
                                    <button type="submit" class="btn btn-primary">บันทึกการรับรอง</button>
                                </div>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal สำหรับแสดงรายละเอียด -->
    <div class="modal fade" id="approvalDetailsModal" tabindex="-1" aria-labelledby="approvalDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approvalDetailsModalLabel">รายละเอียดการรับรอง</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="approvalDetailsContent">
                    <!-- Content will be loaded here -->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                </div>
            </div>
        </div>
    </div>


    <!-- Modal แสดงรายละเอียดการรับรอง -->
    {{-- <div class="modal fade" id="approvalDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">รายละเอียดการรับรอง</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="approvalDetailsContent">
                <!-- เนื้อหาจะถูกเพิ่มด้วย AJAX -->
            </div>
        </div>
    </div>
</div> --}}

<script>
$(document).ready(function() {
    // Toggle comment textarea เมื่อเลือกรูปแบบการรับรอง
    $('input[type=radio][name^="approvals"]').change(function() {
        const sectionId = $(this).attr('name').match(/\[(\d+)\]/)[1];
        const commentSection = $('#comments_' + sectionId);

        if ($(this).val() === 'with_changes') {
            commentSection.slideDown(300);
            commentSection.find('textarea').prop('required', true);
        } else {
            commentSection.slideUp(300);
            commentSection.find('textarea').prop('required', false).val('');
        }
    });

    // Handle form submission
    $('#meetingApprovalForm').submit(function(e) {
        e.preventDefault();

        if (!validateForm()) {
            alert('กรุณากรอกรายละเอียดการแก้ไขให้ครบถ้วน');
            return;
        }

        if (!confirm('ยืนยันการบันทึกการรับรอง?')) {
            return;
        }

        const meetingId = '{{ $meetingAgenda->id }}';
        const submitBtn = $(this).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html(
            '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> กำลังบันทึก...'
        );

        $.ajax({
            url: `/meeting-approval/${meetingId}/update`,
            method: 'POST',
            data: $(this).serialize(),
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    location.reload();
                } else {
                    alert('เกิดข้อผิดพลาด: ' + response.message);
                    submitBtn.prop('disabled', false).text('บันทึกการรับรอง');
                }
            },
            error: function(xhr) {
                const errorMsg = xhr.responseJSON?.message || 'เกิดข้อผิดพลาดในการบันทึกข้อมูล';
                alert(errorMsg);
                submitBtn.prop('disabled', false).text('บันทึกการรับรอง');
            }
        });
    });

    // Show approval details
    $('.show-details').click(function() {
        const approvalId = $(this).data('approval-id');
        const modalContent = $('#approvalDetailsContent');

        // แสดง Loading
        modalContent.html(`
            <div class="d-flex justify-content-center p-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        `);

        // เรียกข้อมูลจาก API
        $.ajax({
            url: `/meeting-approval-details/${approvalId}`,
            method: 'GET',
            success: function(response) {
                modalContent.html(response);
            },
            error: function(xhr) {
                modalContent.html(`
                    <div class="alert alert-danger m-3">
                        <i class="fas fa-exclamation-circle me-2"></i>
                        เกิดข้อผิดพลาดในการโหลดข้อมูล
                    </div>
                `);
            }
        });
    });

    // Edit existing approval
    // เพิ่มการจัดการ form submission
    $('#meetingApprovalForm').submit(function(e) {
        e.preventDefault();

        if (!validateForm()) {
            alert('กรุณากรอกรายละเอียดการแก้ไขให้ครบถ้วน');
            return;
        }

        if (confirm('ยืนยันการบันทึกการรับรอง?')) {
            this.submit();
        }
    });

    // ฟังก์ชันตรวจสอบความถูกต้องของฟอร์ม
    function validateForm() {
        let isValid = true;
        const withChangesRadios = $('input[type="radio"][value="with_changes"]:checked');

        withChangesRadios.each(function() {
            const sectionId = $(this).attr('name').match(/\[(\d+)\]/)[1];
            const textarea = $(`textarea[name="approvals[${sectionId}][comments]"]`);
            const commentText = textarea.val().trim();

            if (!commentText) {
                isValid = false;
                textarea.addClass('is-invalid');
                textarea.next('.invalid-feedback').show();
            } else {
                textarea.removeClass('is-invalid');
                textarea.next('.invalid-feedback').hide();
            }
        });

        return isValid;
    }

    // Clear validation on input
    $(document).on('input', 'textarea', function() {
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').hide();
    });




    function validateForm() {
        let isValid = true;
        const withChangesRadios = $('input[type="radio"][value="with_changes"]:checked');

        withChangesRadios.each(function() {
            const sectionId = $(this).attr('name').match(/\[(\d+)\]/)[1];
            const textarea = $(`textarea[name="approvals[${sectionId}][comments]"]`);
            const commentText = textarea.val().trim();

            if (!commentText) {
                isValid = false;
                textarea.addClass('is-invalid');
                textarea.next('.invalid-feedback').show();
            } else {
                textarea.removeClass('is-invalid');
                textarea.next('.invalid-feedback').hide();
            }
        });

        return isValid;
    }


    // Clear validation on input
    $(document).on('input', 'textarea', function() {
        $(this).removeClass('is-invalid');
        $(this).next('.invalid-feedback').hide();
    });

    // Auto resize textarea
    $('textarea').on('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    // Prevent form submission when pressing Enter in textarea
    $('textarea').keydown(function(e) {
        if (e.keyCode === 13 && !e.shiftKey) {
            e.preventDefault();
            return false;
        }
    });

    // Handle modal cleanup
    $('#approvalDetailsModal').on('hidden.bs.modal', function() {
        $('#approvalDetailsContent').html('');
    });

    // Initialize tooltips
    $('[data-bs-toggle="tooltip"]').tooltip();

    // Handle approval deadline countdown
    function updateDeadlineCountdown() {
        const deadlineElement = $('.approval-deadline');
        if (deadlineElement.length) {
            const deadline = moment(deadlineElement.data('deadline'));
            const now = moment();
            const diff = deadline.diff(now, 'hours');

            if (diff > 0) {
                deadlineElement.html(`เหลือเวลาอีก ${diff} ชั่วโมง`);
            } else {
                deadlineElement.html('หมดเวลารับรอง').addClass('text-danger');
            }
        }
    }

    // Update countdown every minute
    if ($('.approval-deadline').length) {
        updateDeadlineCountdown();
        setInterval(updateDeadlineCountdown, 60000);
    }
});
</script>



{{-- <script>
    // แก้ไขส่วน Show approval details ใน script
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
</script> --}}


@endsection
