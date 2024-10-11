@extends('admin.admin_dashboard')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<div class="page-content">
    <!--breadcrumb-->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">เพิ่มข้อมูลการประชุม</li>
                </ol>
            </nav>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="card">
        <div class="card-body p-4">
            <h5 class="mb-4">เพิ่มข้อมูลการประชุม</h5>
            <form id="meetingForm" action="{{ route('store.meeting') }}" method="post" class="row g-3" enctype="multipart/form-data">
                @csrf

                <div class="form-group col-md-6">
                    <label for="meeting_type_id" class="form-label">ประเภทการประชุม</label>
                    <select name="meeting_type_id" class="form-select mb-3" required>
                        <option value="" selected disabled>เลือกประเภทการประชุม</option>
                        @foreach ($meetingTypes as $type)
                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label for="committee_category_id" class="form-label">ประเภทคณะกรรมการ</label>
                    <select name="committee_category_id" class="form-select mb-3" required>
                        <option value="" selected disabled>เลือกประเภทคณะกรรมการ</option>
                        @foreach ($committeeCategories as $category)
                            <option value="{{ $category->id }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label for="meeting_format_id" class="form-label">รูปแบบการประชุม</label>
                    <select name="meeting_format_id" class="form-select mb-3" required>
                        <option value="" selected disabled>เลือกรูปแบบการประชุม</option>
                        @foreach ($meetingFormats as $format)
                            <option value="{{ $format->id }}">{{ $format->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label for="meeting_agenda_id" class="form-label">ระเบียบวาระการประชุม</label>
                    <select name="meeting_agenda_id" class="form-select mb-3" required>
                        <option value="" selected disabled>เลือกระเบียบวาระการประชุม</option>
                        @foreach ($meetingAgendas as $agenda)
                            <option value="{{ $agenda->id }}">{{ $agenda->meeting_agenda_title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label for="rule_of_meeting_id" class="form-label">กฎการประชุม</label>
                    <select name="rule_of_meeting_id" class="form-select mb-3" required>
                        <option value="" selected disabled>เลือกกฎการประชุม</option>
                        @foreach ($ruleOfMeetings as $rule)
                            <option value="{{ $rule->id }}">{{ $rule->title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label for="regulation_meeting_id" class="form-label">ระเบียบการประชุม</label>
                    <select name="regulation_meeting_id" class="form-select mb-3" required>
                        <option value="" selected disabled>เลือกระเบียบการประชุม</option>
                        @foreach ($regulationMeetings as $regulation)
                            <option value="{{ $regulation->id }}">{{ $regulation->regulation_title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label for="meeting_agenda_section_id" class="form-label">หมวดวาระการประชุม</label>
                    <select name="meeting_agenda_section_id" class="form-select mb-3" required>
                        <option value="" selected disabled>เลือกหมวดวาระการประชุม</option>
                        @foreach ($meetingAgendaSections as $section)
                            <option value="{{ $section->id }}">{{ $section->section_title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label for="meeting_agenda_lecture_id" class="form-label">หัวข้อวาระการประชุม</label>
                    <select name="meeting_agenda_lecture_id" class="form-select mb-3" required>
                        <option value="" selected disabled>เลือกหัวข้อวาระการประชุม</option>
                        @foreach ($meetingAgendaLectures as $lecture)
                            <option value="{{ $lecture->id }}">{{ $lecture->lecture_title }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group col-md-6">
                    <label for="meeting_agenda_item_id" class="form-label">รายการวาระการประชุม</label>
                    <select name="meeting_agenda_item_id" class="form-select mb-3" required>
                        <option value="" selected disabled>เลือกรายการวาระการประชุม</option>
                        @foreach ($meetingAgendaItems as $item)
                            <option value="{{ $item->id }}">{{ $item->item_title }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- <div class="form-group col-md-6">
                    <label for="title" class="form-label">ชื่อการประชุม</label>
                    <input type="text" name="title" class="form-control" required>
                </div> --}}

                {{-- <div class="form-group col-md-12">
                    <label for="description" class="form-label">รายละเอียดการประชุม</label>
                    <textarea name="description" class="form-control" rows="4"></textarea>
                </div> --}}

                <div class="col-md-12">
                    <div class="d-md-flex d-grid align-items-center gap-3">
                        <button type="submit" class="btn btn-primary px-4">บันทึก</button>
                        <a href="" class="btn btn-danger px-4">ยกเลิก</a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
$(document).ready(function() {
    $('#meetingForm').validate({
        rules: {
            meeting_type_id: { required: true },
            committee_category_id: { required: true },
            meeting_format_id: { required: true },
            meeting_agenda_id: { required: true },
            rule_of_meeting_id: { required: true },
            regulation_meeting_id: { required: true },
            meeting_agenda_section_id: { required: true },
            meeting_agenda_lecture_id: { required: true },
            meeting_agenda_item_id: { required: true },
            title: { required: true },
        },
        messages: {
            meeting_type_id: { required: 'กรุณาเลือกประเภทการประชุม' },
            committee_category_id: { required: 'กรุณาเลือกประเภทคณะกรรมการ' },
            meeting_format_id: { required: 'กรุณาเลือกรูปแบบการประชุม' },
            meeting_agenda_id: { required: 'กรุณาเลือกระเบียบวาระการประชุม' },
            rule_of_meeting_id: { required: 'กรุณาเลือกกฎการประชุม' },
            regulation_meeting_id: { required: 'กรุณาเลือกระเบียบการประชุม' },
            meeting_agenda_section_id: { required: 'กรุณาเลือกหมวดวาระการประชุม' },
            meeting_agenda_lecture_id: { required: 'กรุณาเลือกหัวข้อวาระการประชุม' },
            meeting_agenda_item_id: { required: 'กรุณาเลือกรายการวาระการประชุม' },
            title: { required: 'กรุณากรอกชื่อการประชุม' },
        },
        errorElement: 'span',
        errorPlacement: function(error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function(element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function(element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
    });
});
</script>
@endsection
