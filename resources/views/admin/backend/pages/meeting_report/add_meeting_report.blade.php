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
                        <li class="breadcrumb-item active" aria-current="page">เพิ่มรายงานการประชุม</li>
                    </ol>
                </nav>
            </div>

        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body p-4">
                <h5 class="mb-4">เพิ่มรายงานการประชุม</h5>
                <form id="myForm" action="{{ route('store.meeting.report') }}" method="post" class="row g-3"
                    enctype="multipart/form-data">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="form-group col-md-8">
                        <label for="input1" class="form-label">ประเภทคณะกรรมการ</label>
                        <select name="committee_category_id" class="form-select mb-3" aria-label="Default select example">
                            <option selected="" disabled>เลือกประเภทคณะกรรมการ</option>
                            @foreach ($committees as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-8">
                        <label for="input1" class="form-label">ประเภทการประชุม</label>
                        <select name="meeting_type_id" class="form-select mb-3" aria-label="Default select example">
                            <option selected="" disabled>เลือกประเภทการประชุม</option>
                            @foreach ($meeting_types as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label">ชื่อรายงานการประชุม</label>
                        <input type="text" name="title" class="form-control" id="input1" value="{{ old('title') }}">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label">ครั้งที่</label>
                        <input type="text" name="meeting_no" class="form-control" id="input1" value="{{ old('meeting_no') }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label for="input1" class="form-label">วันที่</label>
                        <input type="date" name="date" class="form-control" id="input1" value="{{ old('date') }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label for="input1" class="form-label">เวลา</label>
                        <input type="time" name="time" class="form-control" id="input1" value="{{ old('time') }}">
                    </div>

                    <div class="form-group col-md-4">
                        <label for="input1" class="form-label">ปี </label>
                        <input type="text" name="year" class="form-control" id="input1" value="{{ old('year') }}">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label">สถานที่</label>
                        <input type="text" name="location" class="form-control" id="input1" value="{{ old('location') }}">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="input_pdf" class="form-label">PDF File</label>
                        <input type="file" name="pdf" class="form-control" id="input_pdf" accept=".pdf">
                        <span class="text-danger" id="pdfError"></span>
                    </div>

                    <div class="form-group col-md-12">
                        <label for="input1" class="form-label">รายละเอียด</label>
                        <textarea name="description" class="form-control" rows="10">{{ old('description') }}</textarea>
                    </div>


                    <div class="col-md-12">
                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-primary px-4">บันทึก</button>
                            <a href="{{ route('all.meeting.report') }}" class="btn btn-danger px-4">ยกเลิก</a>
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
                    title: {
                        required: true,
                    },
                    committee_category_id: {
                        required: true,
                    },

                },
                messages: {
                    title: {
                        required: 'Please Enter Category Name',
                    },
                    committee_category_id: {
                        required: 'Please Select Category Name',
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

    <script>
        $("#input_pdf").change(function() {
            var file = $(this).val().split('.').pop().toLowerCase();
            if (file != "pdf") {
                $('#pdfError').html('Please select pdf file');
                $(this).val('');
            } else {
                $('#pdfError').html('');
            }
        });
    </script>

@endsection
