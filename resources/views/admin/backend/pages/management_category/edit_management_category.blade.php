@extends('admin.admin_dashboard')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <div class="row profile-body">
        <div class="col-md-12 col-xl-12 middle-wrapper">
            <div class="row">
                <div class="card">
                    <div class="card-body">
                        <h6 class="card-title">แก้ไขหมวดด้านการบริหาร</h6>
                        <form id="myForm" method="POST" action="{{ route('update.management.category') }}" class="forms-sample">
                            @csrf

                            <input type="hidden" name="id" value="{{ $managementCategory->id }}">

                            <div class="form-group mb-3">
                                <label for="category_code" class="form-label">เลขหมวด</label>
                                <input type="text" name="category_code" class="form-control" id="category_code" value="{{ $managementCategory->category_code }}">
                            </div>

                            <div class="form-group mb-3">
                                <label for="name" class="form-label">ชื่อหมวด</label>
                                <input type="text" name="name" class="form-control" id="name" value="{{ $managementCategory->name }}">
                            </div>

                            <div class="form-group mb-3">
                                <label for="description" class="form-label">รายละเอียด</label>
                                <textarea name="description" class="form-control" id="description" rows="4">{{ $managementCategory->description }}</textarea>
                            </div>

                            <button type="submit" class="btn btn-primary me-2">อัปเดต</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function (){
        $('#myForm').validate({
            rules: {
                category_code: {
                    required : true,
                },
                name: {
                    required : true,
                },
            },
            messages :{
                category_code: {
                    required : 'กรุณากรอกเลขหมวด',
                },
                name: {
                    required : 'กรุณากรอกชื่อหมวด',
                },
            },
            errorElement : 'span',
            errorPlacement: function (error,element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight : function(element, errorClass, validClass){
                $(element).addClass('is-invalid');
            },
            unhighlight : function(element, errorClass, validClass){
                $(element).removeClass('is-invalid');
            },
        });
    });
</script>

@endsection
