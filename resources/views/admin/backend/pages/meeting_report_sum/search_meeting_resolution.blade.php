@extends('admin.admin_dashboard')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<@php
    function formatThaiDate($dateString) {
        $months = [
            'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน',
            'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
        ];

        $date = new DateTime($dateString);
        $day = $date->format('d');
        $month = $months[$date->format('n') - 1];
        $year = $date->format('Y') + 543;

        return $day . ' ' . $month . ' ' . $year;
    }
@endphp

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">สืบค้นข้อมูลมติการประชุม</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-12">
                    <div class="card border-0 shadow-none bg-transparent">
                        <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(to right, #4e73df, #36b9cc);">
                            <h5 class="mb-0 text-white">
                                <i class="bx bx-search-alt me-2"></i> สืบค้นข้อมูลมติการประชุม
                            </h5>
                            <button class="btn btn-light btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#searchForm" aria-expanded="{{ request()->has('keyword') || request()->has('committee_category_id') ? 'false' : 'true' }}" aria-controls="searchForm">
                                <i class="bx bx-filter-alt"></i> {{ request()->has('keyword') || request()->has('committee_category_id') ? 'แสดงฟอร์มค้นหา' : 'ซ่อนฟอร์มค้นหา' }}
                            </button>
                        </div>
                        <div class="card-body bg-light rounded-bottom collapse {{ request()->has('keyword') || request()->has('committee_category_id') ? '' : 'show' }}" id="searchForm">
                            <form action="{{ route('search.meeting.resolution.results') }}" method="get" id="resolutionSearchForm" class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="meeting_type_id" class="form-label">ประเภทการประชุม</label>
                                        <select name="meeting_type_id" id="meeting_type_id" class="form-select">
                                            <option value="">-- เลือกทั้งหมด --</option>
                                            @foreach($meetingTypes as $type)
                                                <option value="{{ $type->id }}" {{ (isset($_GET['meeting_type_id']) && $_GET['meeting_type_id'] == $type->id) ? 'selected' : '' }}>
                                                    {{ $type->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="committee_category_id" class="form-label">ประเภทคณะกรรมการ</label>
                                        <select name="committee_category_id" id="committee_category_id" class="form-select">
                                            <option value="">-- เลือกทั้งหมด --</option>
                                            @foreach($committeeCategories as $category)
                                                <option value="{{ $category->id }}" {{ (isset($_GET['committee_category_id']) && $_GET['committee_category_id'] == $category->id) ? 'selected' : '' }}>
                                                    {{ $category->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="meeting_agenda_id" class="form-label">รายงานการประชุม</label>
                                        <select name="meeting_agenda_id" id="meeting_agenda_id" class="form-select" {{ (!isset($_GET['committee_category_id']) || !isset($_GET['meeting_type_id'])) ? 'disabled' : '' }}>
                                            <option value="">-- เลือกทั้งหมด --</option>
                                            <!-- จะถูกเติมด้วย Ajax -->
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="meeting_agenda_section_id" class="form-label">วาระการประชุม</label>
                                        <select name="meeting_agenda_section_id" id="meeting_agenda_section_id" class="form-select" {{ !isset($_GET['meeting_agenda_id']) ? 'disabled' : '' }}>
                                            <option value="">-- เลือกทั้งหมด --</option>
                                            <!-- จะถูกเติมด้วย Ajax -->
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group mb-3">
                                        <label for="meeting_agenda_lecture_id" class="form-label">หัวข้อย่อย</label>
                                        <select name="meeting_agenda_lecture_id" id="meeting_agenda_lecture_id" class="form-select" {{ !isset($_GET['meeting_agenda_section_id']) ? 'disabled' : '' }}>
                                            <option value="">-- เลือกทั้งหมด --</option>
                                            <!-- จะถูกเติมด้วย Ajax -->
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="form-group mb-3">
                                        <label for="keyword" class="form-label">คำค้นหา</label>
                                        <div class="input-group">
                                            <span class="input-group-text bg-transparent"><i class="bx bx-search"></i></span>
                                            <input type="text" name="keyword" id="keyword" class="form-control" placeholder="ค้นหาจากมติที่ประชุม, เรื่อง, ผู้รับผิดชอบ หรือผู้เสนอวาระ" value="{{ isset($_GET['keyword']) ? $_GET['keyword'] : '' }}">
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="task_status" class="form-label">สถานะงาน</label>
                                        <select name="task_status" id="task_status" class="form-select">
                                            <option value="">-- ทั้งหมด --</option>
                                            <option value="completed" {{ (isset($_GET['task_status']) && $_GET['task_status'] == 'completed') ? 'selected' : '' }}>ดำเนินการแล้ว</option>
                                            <option value="in_progress" {{ (isset($_GET['task_status']) && $_GET['task_status'] == 'in_progress') ? 'selected' : '' }}>อยู่ระหว่างดำเนินการ</option>
                                            <option value="not_started" {{ (isset($_GET['task_status']) && $_GET['task_status'] == 'not_started') ? 'selected' : '' }}>ยังไม่ดำเนินการ</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="start_date" class="form-label">วันที่มีมติ (จาก)</label>
                                        <input type="date" name="start_date" id="start_date" class="form-control" value="{{ isset($_GET['start_date']) ? $_GET['start_date'] : '' }}">
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group mb-3">
                                        <label for="end_date" class="form-label">วันที่มีมติ (ถึง)</label>
                                        <input type="date" name="end_date" id="end_date" class="form-control" value="{{ isset($_GET['end_date']) ? $_GET['end_date'] : '' }}">
                                    </div>
                                </div>

                                <div class="col-12 text-center mt-4">
                                    <button type="submit" class="btn btn-success px-5 py-2">
                                        <i class="bx bx-search me-1"></i> ค้นหา
                                    </button>
                                    <button type="button" id="resetBtn" class="btn btn-outline-danger px-5 py-2 ms-2">
                                        <i class="bx bx-reset me-1"></i> ล้างข้อมูล
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- ส่วนแสดงผลการค้นหา -->
    @if(isset($meeting_resolutions) && count($meeting_resolutions) > 0)
    <div class="mt-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h5 class="mb-0">
                <i class="bx bx-list-ul me-1"></i> ผลการค้นหามติการประชุม
            </h5>
            <span class="badge bg-primary rounded-pill">พบข้อมูล {{ count($meeting_resolutions) }} รายการ</span>
        </div>

        @foreach($meeting_resolutions as $key => $item)
        <div class="card mb-3 border-0 shadow-sm">
            <div class="card-body">
                <h5 class="card-title border-bottom pb-2">
                    @if(isset($_GET['keyword']) && $_GET['keyword'])
                        {!! highlightKeyword($item->meetingAgenda->meeting_agenda_title, $_GET['keyword']) !!} ครั้งที่ {{ $item->meetingAgenda->meeting_agenda_number }}/{{ $item->meetingAgenda->meeting_agenda_year }}
                    @else
                        {{ $item->meetingAgenda->meeting_agenda_title }} ครั้งที่ {{ $item->meetingAgenda->meeting_agenda_number }}/{{ $item->meetingAgenda->meeting_agenda_year }}
                    @endif
                </h5>
                <div class="row mt-2">
                    <div class="col-md-12">
                        {{-- <p>วาระที่ {{ $key+1 }} --}}
                        <p>
                            @if(isset($_GET['keyword']) && $_GET['keyword'])
                                {!! highlightKeyword($item->meetingAgendaSection->section_title, $_GET['keyword']) !!}
                            @else
                                {{ $item->meetingAgendaSection->section_title }}
                            @endif
                        </p>
                        <p>หัวข้อย่อย :
                            @if($item->meetingAgendaLecture)
                                {{ $item->meetingAgendaLecture->lecture_title }}
                            @else
                                ไม่มีหัวข้อย่อย
                            @endif
                        </p>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <p class="fw-bold mb-1">เอกสารประกอบการประชุม :
                            @if($item->document)
                            <button type="button" class="btn btn-sm btn-outline-primary" data-bs-toggle="modal" data-bs-target="#documentModal{{ $item->id }}">
                                <i class="bx bx-file"></i> เอกสารที่แนบ
                            </button>
                            @else
                            ไม่มีเอกสารแนบ
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Modal แสดงเอกสารประกอบการประชุม -->
                @if($item->document)
                <div class="modal fade" id="documentModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-xl modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header" style="background: linear-gradient(to right, #4e73df, #36b9cc);">
                                <h5 class="modal-title text-white text-center w-100">เอกสารแนบ - รายงานการประชุม</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-0" style="height: 80vh;">
                                @php
                                    $fileExtension = pathinfo($item->document, PATHINFO_EXTENSION);
                                @endphp

                                @if(in_array(strtolower($fileExtension), ['jpg', 'jpeg', 'png', 'gif']))
                                    <img src="{{ asset($item->document) }}" class="img-fluid w-100" alt="เอกสารประกอบการประชุม">
                                @elseif(strtolower($fileExtension) === 'pdf')
                                    <iframe src="{{ asset($item->document) }}" width="100%" height="100%" frameborder="0"></iframe>
                                @else
                                    <div class="p-5 text-center">
                                        <i class="bx bx-file fs-1 mb-3 text-primary"></i>
                                        <h5>ไม่สามารถแสดงตัวอย่างเอกสารได้</h5>
                                        <p class="mb-4">ประเภทเอกสาร: {{ strtoupper($fileExtension) }}</p>
                                        <a href="{{ asset($item->document) }}" class="btn btn-primary" download>
                                            <i class="bx bx-download me-1"></i> ดาวน์โหลดเอกสาร
                                        </a>
                                    </div>
                                @endif
                            </div>
                            <div class="modal-footer">
                                <a href="{{ asset($item->document) }}" class="btn btn-success" download>
                                    <i class="bx bx-download me-1"></i> ดาวน์โหลด
                                </a>
                                <button type="button" class="btn btn-danger" data-bs-dismiss="modal">ปิด</button>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <div class="row">
                    <div class="col-md-12">
                        <p class="fw-bold mb-1">ผู้เสนอวาระ :
                            @if(isset($_GET['keyword']) && $_GET['keyword'])
                                {!! highlightKeyword($item->proposer, $_GET['keyword']) !!}
                            @else
                                {{ $item->proposer }}
                            @endif
                        </p>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <p class="fw-bold mb-1">มติที่ประชุม :</p>
                        <div>
                            @if(isset($_GET['keyword']) && $_GET['keyword'])
                                {!! highlightKeyword($item->resolution_text, $_GET['keyword']) !!}
                            @else
                                {!! $item->resolution_text !!}
                            @endif
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-md-12">
                        <p class="fw-bold mb-1">การดำเนินงานที่ได้รับมอบหมาย</p>
                        <p><b>เรื่อง :</b>
                            @if(isset($_GET['keyword']) && $_GET['keyword'])
                                {!! highlightKeyword($item->task_title, $_GET['keyword']) !!}
                            @else
                                {{ $item->task_title }}
                            @endif
                        </p>
                        <p><b>ผู้รับผิดชอบ :</b>
                            @if(isset($_GET['keyword']) && $_GET['keyword'])
                                {!! highlightKeyword($item->responsible_person, $_GET['keyword']) !!}
                            @else
                                {{ $item->responsible_person }}
                            @endif
                        </p>
                        <p><b>ผลการดำเนินงาน :</b>
                            @if($item->task_status == 'completed')
                                <span class="badge bg-success">ดำเนินการแล้ว</span>
                            @elseif($item->task_status == 'in_progress')
                                <span class="badge bg-warning text-dark">อยู่ระหว่างดำเนินการ</span>
                            @else
                                <span class="badge bg-danger">ยังไม่ดำเนินการ</span>
                            @endif
                        </p>
                        <p><b>วันที่รายงานผล :</b> {{ formatThaiDate($item->report_date) }}</p>
                    </div>
                </div>

                {{-- <div class="text-end mt-3">
                    <a href="{{ route('edit.meeting.resolution', $item->id) }}" class="btn btn-sm btn-primary">
                        <i class="bx bx-edit"></i> แก้ไข
                    </a>
                    <a href="{{ route('delete.meeting.resolution', $item->id) }}" class="btn btn-sm btn-danger" onclick="return confirm('คุณต้องการลบรายการนี้ใช่หรือไม่?')">
                        <i class="bx bx-trash"></i> ลบ
                    </a>
                </div> --}}
            </div>
        </div>
        @endforeach
    </div>
    @elseif(isset($meeting_resolutions) && count($meeting_resolutions) == 0)
    <div class="alert alert-info text-center py-4 mt-4">
        <i class="bx bx-info-circle fs-3 mb-2"></i>
        <h5>ไม่พบข้อมูลตามเงื่อนไขที่ค้นหา</h5>
        <p class="mb-0">กรุณาปรับเปลี่ยนเงื่อนไขการค้นหาและลองใหม่อีกครั้ง</p>
    </div>
    @endif
</div>

<?php
// ฟังก์ชัน highlight คำค้นหา
function highlightKeyword($text, $keyword) {
    if (!$keyword) return $text;

    // แยกคำค้นหาออกเป็นคำๆ ในกรณีที่ค้นหาหลายคำ
    $keywords = explode(' ', $keyword);

    foreach ($keywords as $word) {
        if (strlen($word) < 2) continue; // ข้ามคำที่สั้นเกินไป

        $word = preg_quote($word, '/'); // ป้องกันอักขระพิเศษในคำค้นหา

        // ไฮไลท์คำค้นหาในข้อความโดยไม่คำนึงถึงตัวพิมพ์ใหญ่-เล็ก
        $text = preg_replace('/(' . $word . ')/i', '<mark style="background-color: #FFFF00; padding: 0 2px;">$1</mark>', $text);
    }

    return $text;
}
?>

<script type="text/javascript">
    $(document).ready(function() {
        // กรณีที่มีการค้นหาแล้ว และมีการกลับมาที่หน้านี้
        @if(isset($_GET['committee_category_id']) && isset($_GET['meeting_type_id']))
            var committeeId = '{{ $_GET['committee_category_id'] }}';
            var meetingTypeId = '{{ $_GET['meeting_type_id'] }}';

            if(committeeId && meetingTypeId) {
                getMeetingAgendas(committeeId, meetingTypeId, '{{ $_GET['meeting_agenda_id'] ?? '' }}');
            }

            @if(isset($_GET['meeting_agenda_id']))
                var meetingAgendaId = '{{ $_GET['meeting_agenda_id'] }}';
                if(meetingAgendaId) {
                    getMeetingSections(meetingAgendaId, '{{ $_GET['meeting_agenda_section_id'] ?? '' }}');
                }
            @endif
        @endif

        // เมื่อเลือกประเภทคณะกรรมการและประเภทการประชุม
        $('#committee_category_id, #meeting_type_id').change(function() {
            var committeeId = $('#committee_category_id').val();
            var meetingTypeId = $('#meeting_type_id').val();

            if (committeeId && meetingTypeId) {
                getMeetingAgendas(committeeId, meetingTypeId);
            } else {
                $('#meeting_agenda_id').prop('disabled', true).html('<option value="">-- เลือกทั้งหมด --</option>');
                $('#meeting_agenda_section_id').prop('disabled', true).html('<option value="">-- เลือกทั้งหมด --</option>');
            }
        });

        // เมื่อเลือกรายงานการประชุม
        $('#meeting_agenda_id').change(function() {
            var meetingAgendaId = $(this).val();

            if (meetingAgendaId) {
                getMeetingSections(meetingAgendaId);
            } else {
                $('#meeting_agenda_section_id').prop('disabled', true).html('<option value="">-- เลือกทั้งหมด --</option>');
            }
        });

        // ฟังก์ชันดึงข้อมูลรายงานการประชุม
        function getMeetingAgendas(committeeId, meetingTypeId, selectedAgendaId = '') {
            // เปิดใช้งาน dropdown รายงานการประชุม
            $('#meeting_agenda_id').prop('disabled', false);

            // ดึงข้อมูลรายงานการประชุมตามประเภทคณะกรรมการและประเภทการประชุม
            $.ajax({
                url: "{{ route('get.meeting.agendas') }}",
                type: "GET",
                data: {
                    committee_category_id: committeeId,
                    meeting_type_id: meetingTypeId
                },
                success: function(data) {
                    $('#meeting_agenda_id').empty();
                    $('#meeting_agenda_id').append('<option value="">-- เลือกทั้งหมด --</option>');
                    $.each(data, function(key, value) {
                        var selected = (selectedAgendaId == value.id) ? 'selected' : '';
                        $('#meeting_agenda_id').append('<option value="' + value.id + '" ' + selected + '>' + value.meeting_agenda_title + '</option>');
                    });

                    // ถ้ามีการเลือก agenda และมีค่า selectedAgendaId
                    if (selectedAgendaId) {
                        // $('#meeting_agenda_id').trigger('change');
                    }
                }
            });
        }

        // ฟังก์ชันดึงข้อมูลวาระการประชุม
        function getMeetingSections(meetingAgendaId, selectedSectionId = '') {
            // เปิดใช้งาน dropdown วาระการประชุม
            $('#meeting_agenda_section_id').prop('disabled', false);

            // ดึงข้อมูลวาระการประชุมตามรายงานการประชุมที่เลือก
            $.ajax({
                url: "{{ route('get.meeting.sections') }}",
                type: "GET",
                data: {
                    meeting_agenda_id: meetingAgendaId
                },
                success: function(data) {
                    $('#meeting_agenda_section_id').empty();
                    $('#meeting_agenda_section_id').append('<option value="">-- เลือกทั้งหมด --</option>');
                    $.each(data, function(key, value) {
                        var selected = (selectedSectionId == value.id) ? 'selected' : '';
                        $('#meeting_agenda_section_id').append('<option value="' + value.id + '" ' + selected + '>' + value.section_title + '</option>');
                    });
                    if (selectedSectionId) {
                        $('#meeting_agenda_section_id').trigger('change');
                    }
                }
            });
        }

        $('#meeting_agenda_section_id').change(function() {
            var sectionId = $(this).val();
            var selectedLectureId = '{{ $_GET['meeting_agenda_lecture_id'] ?? '' }}';

            if (sectionId) {
                // เปิดใช้งาน dropdown หัวข้อย่อย
                $('#meeting_agenda_lecture_id').prop('disabled', false);

                $.ajax({
                    url: '/get-meeting-agenda-lectures/' + sectionId,
                    type: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        $('#meeting_agenda_lecture_id').empty();
                        $('#meeting_agenda_lecture_id').append('<option value="">-- เลือกหัวข้อย่อย --</option>');
                        $.each(data, function(key, value) {
                            var selected = (selectedLectureId == value.id) ? 'selected' : '';
                            $('#meeting_agenda_lecture_id').append('<option value="' + value.id + '" ' + selected + '>' + value.lecture_title + '</option>');
                        });
                    }
                });
            } else {
                $('#meeting_agenda_lecture_id').empty();
                $('#meeting_agenda_lecture_id').append('<option value="">-- เลือกหัวข้อย่อย --</option>');
            }
        });

        // ล้างข้อมูลในฟอร์ม
        $('#resetBtn').click(function() {
            $('#committee_category_id').val('');
            $('#meeting_type_id').val('');
            $('#meeting_agenda_id').val('').prop('disabled', true);
            $('#meeting_agenda_section_id').val('').prop('disabled', true);
            $('#meeting_agenda_lecture_id').val('').prop('disabled', true);
            $('#keyword').val('');
            $('#task_status').val('');
            $('#start_date').val('');
            $('#end_date').val('');
        });
    });
</script>
@endsection
