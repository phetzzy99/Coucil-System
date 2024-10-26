@extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>

    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">แก้ไขระเบียบวาระการประชุม</li>
                    </ol>
                </nav>
            </div>

        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body p-4">
                <h5 class="mb-4">แก้ไขระเบียบวาระการประชุม</h5>
                <form id="myForm" action="{{ route('update.meeting.agenda') }}" method="post"
                    class="row g-3" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $meeting_agenda->id }}">
                    {{-- @method('POST') --}}

                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label">ประเภทการประชุม</label>
                        <select name="meeting_type_id" class="form-select mb-3" aria-label="Default select example">
                            <option selected="" disabled>เลือกประเภทการประชุม</option>
                            @foreach ($meeting_types as $item)
                                <option value="{{ $item->id }}" @if ($meeting_agenda->meeting_type_id == $item->id) selected @endif>
                                    {{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="committee_category_id" class="form-label">หมวดหมู่คณะกรรมการ</label>
                        <select name="committee_category_id" class="form-select" required>
                            @foreach($committee_categories as $category)
                                <option value="{{ $category->id }}" {{ $meeting_agenda->committee_category_id == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="meeting_format_id" class="form-label">รูปแบบการประชุม</label>
                        <select name="meeting_format_id" class="form-select" required>
                            @foreach($meeting_formats as $format)
                                <option value="{{ $format->id }}" {{ $meeting_agenda->meeting_format_id == $format->id ? 'selected' : '' }}>
                                    {{ $format->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="regulation_meeting_id" class="form-label">ระเบียบการประชุม</label>
                        <select name="regulation_meeting_id" class="form-select" required>
                            @foreach($regulation_meetings as $regulation)
                                <option value="{{ $regulation->id }}" {{ $meeting_agenda->regulation_meeting_id == $regulation->id ? 'selected' : '' }}>
                                    {{ $regulation->regulation_title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="rule_of_meeting_ids" class="form-label">ข้อบังคับการประชุม</label>
                        <select name="rule_of_meeting_ids[]" class="form-select" id="small-bootstrap-class-multiple-field" multiple required>
                            @foreach($rule_of_meetings as $rule)
                                <option value="{{ $rule->id }}" {{ in_array($rule->id, $meeting_agenda->ruleOfMeeting->pluck('id')->toArray()) ? 'selected' : '' }}>
                                    {{ $rule->title }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label">ชื่อระเบียบวาระการประชุม</label>
                        <input type="text" name="meeting_agenda_title" class="form-control" id="input1"
                            value="{{ $meeting_agenda->meeting_agenda_title }}" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label">เลขที่ระเบียบวาระการประชุม</label>
                        <input type="text" name="meeting_agenda_number" class="form-control" id="input1"
                            value="{{ $meeting_agenda->meeting_agenda_number }}" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label">ปีระเบียบวาระการประชุม</label>
                        <input type="text" name="meeting_agenda_year" class="form-control" id="input1"
                            value="{{ $meeting_agenda->meeting_agenda_year }}" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label">วันที่วาระการประชุม</label>
                        <input type="date" name="meeting_agenda_date" class="form-control" id="input1"
                            value="{{ $meeting_agenda->meeting_agenda_date }}" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label">เวลาระเบียบวาระการประชุม</label>
                        <input type="time" name="meeting_agenda_time" class="form-control" id="input1"
                            value="{{ $meeting_agenda->meeting_agenda_time }}" required>
                    </div>

                    <div class="form-group col-md-12">
                        <label for="input1" class="form-label">สถานที่ระเบียบวาระการประชุม</label>
                        <input type="text" name="meeting_location" class="form-control" id="input1"
                            value="{{ $meeting_agenda->meeting_location }}" required>
                    </div>

                    <fieldset class="border border-primary rounded p-2 mt-3">
                        <legend class="float-none w-auto">กำหนดการรับรอง</legend>
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="approval_deadline_date" class="form-label">วันที่สิ้นสุดการรับรอง</label>
                                    <input type="date" name="approval_deadline_date"
                                            class="form-control @error('approval_deadline_date') is-invalid @enderror"
                                            value="{{ old('approval_deadline_date', isset($meeting_agenda->approval_deadline) ? date('Y-m-d', strtotime($meeting_agenda->approval_deadline)) : '') }}"
                                            required>
                                    @error('approval_deadline_date')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="approval_deadline_time" class="form-label">เวลาสิ้นสุดการรับรอง</label>
                                    <input type="time" name="approval_deadline_time"
                                            class="form-control @error('approval_deadline_time') is-invalid @enderror"
                                            value="{{ old('approval_deadline_time', isset($meeting_agenda->approval_deadline) ? date('H:i', strtotime($meeting_agenda->approval_deadline)) : '') }}"
                                            required>
                                    @error('approval_deadline_time')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        </div>
                    </fieldset>

                    <div class="form-group col-md-12">
                        <label for="input1" class="form-label">รายละเอียดระเบียบวาระการประชุม</label>
                        <textarea name="description" class="form-control" rows="10">{{ $meeting_agenda->description }}</textarea>
                    </div>

                    <div class="col-md-12">
                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-primary px-4">บันทึก</button>
                            <a href="{{ route('all.meeting.agenda') }}" class="btn btn-danger px-4">ยกเลิก</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            $('#myForm').validate({
                rules: {
                    meeting_type_id: {
                        required: true,
                    },
                },
                messages: {
                    meeting_type_id: {
                        required: 'Please Enter Category Name',
                    },


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

