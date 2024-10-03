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
                        <li class="breadcrumb-item active" aria-current="page">เพิ่มระเบียบ</li>
                    </ol>
                </nav>
            </div>

        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body p-4">
                <h5 class="mb-4">เพิ่มระเบียบ</h5>
                <form id="myForm" action="{{ route('store.regulation.meeting') }}" method="post" class="row g-3"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="form-group col-md-8">
                        <label for="input1" class="form-label">Regulation Category Name</label>
                        <select name="regulation_category_id" class="form-select mb-3" aria-label="Default select example">
                            <option selected="" disabled>เลือกประเภทข้อบังคับ</option>
                            @foreach ($categories as $item)
                                <option value="{{ $item->id }}">{{ $item->regulation_category_name }}</option>
                            @endforeach

                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label">Regulation Title</label>
                        <input type="text" name="regulation_title" class="form-control" id="input1" value="{{ old('regulation_title') }}">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="input_pdf" class="form-label">PDF File</label>
                        <input type="file" name="regulation_pdf" class="form-control" id="input_pdf" accept=".pdf">
                        <span class="text-danger" id="pdfError"></span>
                    </div>

                    <div class="form-group col-md-12">
                        <label for="input1" class="form-label">Description</label>
                        <textarea name="description" class="form-control" rows="10">{{ old('description') }}</textarea>
                    </div>



                    <div class="col-md-12">
                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                            <a href="{{ route('all.rule.meeting') }}" class="btn btn-danger px-4">Back</a>
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
                    regulation_title: {
                        required: true,
                    },
                    regulation_category_id: {
                        required: true,
                    },

                },
                messages: {
                    regulation_title: {
                        required: 'Please Enter Category Name',
                    },
                    regulation_category_id: {
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
