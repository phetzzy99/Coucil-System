@extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

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

                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label">ประเภทการประชุม</label>
                        <select name="meeting_type_id" class="form-select mb-3" aria-label="Default select example">
                            <option selected="" disabled>เลือกประเภทการประชุม</option>
                            @foreach ($meeting_types as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

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