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
                        <li class="breadcrumb-item active" aria-current="page">เพิ่มระเบียบวาระการประชุม</li>
                    </ol>
                </nav>
            </div>

        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body p-4">
                <h5 class="mb-4">เพิ่มระเบียบวาระการประชุม</h5>
                <form id="myForm" action="{{ route('store.meeting.agenda') }}" method="post" class="row g-3"
                    enctype="multipart/form-data">
                    @csrf

                    <fieldset style="border: 1px solid #007bff; border-radius: 5px;" class="p-2">
                        <legend>ประเภทการประชุม</legend>
                        <div class="form-group col-md-6">
                            <label for="input1" class="form-label">ประเภทการประชุม</label>
                            <select name="meeting_type_id" class="form-select mb-3" aria-label="Default select example">
                                <option selected="" disabled>เลือกประเภทการประชุม</option>
                                @foreach ($meeting_types as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <legend>ประเภทคณะกรรมการ</legend>
                        <div class="form-group col-md-6">
                            <label for="input1" class="form-label">ประเภทคณะกรรมการ</label>
                            <select name="committee_category_id" class="form-select mb-3"
                                aria-label="Default select example">
                                <option selected="" disabled>เลือกประเภทคณะกรรมการ</option>
                                @foreach ($committee_categories as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <legend>รูปแบบการประชุม</legend>
                        <div class="form-group col-md-6">
                            <label for="input1" class="form-label">รูปแบบการประชุม</label>
                            <select name="meeting_format_id" class="form-select mb-3" aria-label="Default select example">
                                <option selected="" disabled>เลือกรูปแบบการประชุม</option>
                                @foreach ($meeting_format as $item)
                                    <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </fieldset>

                    <div></div>

                    <fieldset style="border: 1px solid #007bff; border-radius: 5px;" class="p-2">
                        <p class="mb-0 text-center text-secondary fst-italic">* ข้อบังคับ / ระเบียบ</p>
                        <div class="form-group col-md-6">
                            <label for="input1" class="form-label">ข้อบังคับ</label>
                            <select name="rule_of_meeting_id" class="form-select mb-3" aria-label="Default select example">
                                <option selected="" disabled>เลือกกฎการประชุม</option>
                                @foreach ($rule_of_meetings as $item)
                                    <option value="{{ $item->id }}">{{ $item->title }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="input1" class="form-label">ระเบียบ</label>
                            <select name="regulation_meeting_id" class="form-select mb-3"
                                aria-label="Default select example">
                                <option selected="" disabled>เลือกระเบียบการประชุม</option>
                                @foreach ($regulation_meetings as $item)
                                    <option value="{{ $item->id }}">{{ $item->regulation_title }}</option>
                                @endforeach
                            </select>
                        </div>

                    </fieldset>

                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label">ชื่อระเบียบวาระการประชุม</label>
                        <input type="text" name="meeting_agenda_title" class="form-control" id="input1" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label">เลขที่ระเบียบวาระการประชุม</label>
                        <input type="text" name="meeting_agenda_number" class="form-control" id="input1" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label">ปีระเบียบวาระการประชุม</label>
                        <input type="text" name="meeting_agenda_year" class="form-control" id="input1" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label">วันที่วาระการประชุม</label>
                        <input type="date" name="meeting_agenda_date" class="form-control" id="input1" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label">เวลาระเบียบวาระการประชุม</label>
                        <input type="time" name="meeting_agenda_time" class="form-control" id="input1" required>
                    </div>

                    <div class="form-group col-md-12">
                        <label for="input1" class="form-label">สถานที่ระเบียบวาระการประชุม</label>
                        <input type="text" name="meeting_location" class="form-control" id="input1" required>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="approval_deadline_date" class="form-label">วันที่สิ้นสุดการรับรอง</label>
                            <input type="date" name="approval_deadline_date"
                                   class="form-control @error('approval_deadline_date') is-invalid @enderror"
                                   value="{{ old('approval_deadline_date', isset($meeting_agenda) ? $meeting_agenda->approval_deadline?->format('Y-m-d') : '') }}"
                                   required>
                            @error('approval_deadline_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="approval_deadline_time" class="form-label">เวลาสิ้นสุดการรับรอง</label>
                            <input type="time" name="approval_deadline_time"
                                   class="form-control @error('approval_deadline_time') is-invalid @enderror"
                                   value="{{ old('approval_deadline_time', isset($meeting_agenda) ? $meeting_agenda->approval_deadline?->format('H:i') : '') }}"
                                   required>
                            @error('approval_deadline_time')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>


                    <div class="form-group col-md-12">
                        <label for="input1" class="form-label">รายละเอียดระเบียบวาระการประชุม</label>
                        <textarea name="description" class="form-control" rows="10"></textarea>
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

    <script>
        CKEDITOR.replace('description');
    </script>


    <script type="text/javascript">
        $(document).ready(function() {
            $('#myForm').validate({
                rules: {
                    meeting_type_id: {
                        required: true,
                    },
                    committee_category_id: {
                        required: true,
                    },
                    meeting_format_id: {
                        required: true,
                    },
                    rule_of_meeting_id: {
                        required: true,
                    },
                    regulation_meeting_id: {
                        required: true,
                    },

                },
                messages: {
                    meeting_type_id: {
                        required: 'Please Enter Category Name',
                    },
                    committee_category_id: {
                        required: 'Please Enter Category Name',
                    },
                    meeting_format_id: {
                        required: 'Please Enter Category Name',
                    },
                    rule_of_meeting_id: {
                        required: 'Please Enter Category Name',
                    },
                    regulation_meeting_id: {
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
