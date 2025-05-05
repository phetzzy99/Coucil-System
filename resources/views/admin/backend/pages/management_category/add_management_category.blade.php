@extends('admin.admin_dashboard')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <div class="row profile-body">
        <div class="col-md-12 col-xl-12 middle-wrapper">
            <div class="row">
                <div class="card">
                    <div class="card-body p-4">
                        <div class="text-center mb-4">
                            <h5 class="card-title"><i class="bx bx-folder-plus me-2"></i>เพิ่มหมวดด้านการบริหาร</h5>
                            <hr>
                        </div>
                        <form id="myForm" method="POST" action="{{ route('store.management.category') }}" class="forms-sample row g-3">
                            @csrf

                            <div class="form-group col-md-6 mb-3">
                                <label for="category_code" class="form-label fw-bold">เลขหมวด</label>
                                <input type="text" name="category_code" class="form-control" id="category_code" placeholder="กรอกเลขหมวด">
                            </div>

                            <div class="form-group col-md-6 mb-3">
                                <label for="name" class="form-label fw-bold">ชื่อหมวด</label>
                                <input type="text" name="name" class="form-control" id="name" placeholder="กรอกชื่อหมวด">
                            </div>

                            <div class="form-group col-md-12 mb-4">
                                <label for="description" class="form-label fw-bold">รายละเอียด</label>
                                <textarea name="description" class="form-control" id="description" rows="5" placeholder="กรอกรายละเอียด"></textarea>
                            </div>

                            <div class="col-md-12">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary px-4"><i class="bx bx-save me-1"></i>บันทึก</button>
                                    <a href="{{ route('all.management.categories') }}" class="btn btn-danger px-4"><i class="bx bx-x-circle me-1"></i>ยกเลิก</a>
                                </div>
                            </div>
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
