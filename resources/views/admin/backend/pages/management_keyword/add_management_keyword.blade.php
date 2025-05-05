@extends('admin.admin_dashboard')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>

<div class="page-content">
    <div class="row profile-body">
        <div class="col-md-10 col-xl-8 mx-auto">
            <div class="card">
                <div class="card-body p-4">
                    <h5 class="card-title mb-4"><i class="bx bx-plus-circle me-1"></i>เพิ่ม Keyword หมวดด้านการบริหาร</h5>
                    <form id="myForm" method="POST" action="{{ route('store.management.keyword') }}" class="forms-sample row g-3">
                        @csrf

                        <div class="form-group col-md-12 mb-3">
                            <label for="management_category_id" class="form-label fw-bold">เลือกเลขหมวด</label>
                            <select name="management_category_id" class="form-select" id="management_category_id">
                                <option value="">-- เลือกเลขหมวด --</option>
                                @foreach($managementCategories as $category)
                                <option value="{{ $category->id }}">{{ $category->category_code }} - {{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-12 mb-3">
                            <label for="keyword_title" class="form-label fw-bold">Keyword เรื่องพิจารณา</label>
                            <input type="text" name="keyword_title" class="form-control" id="keyword_title" placeholder="กรอก Keyword เรื่องพิจารณา">
                        </div>

                        <div class="form-group col-md-12 mb-4">
                            <label for="description" class="form-label fw-bold">รายละเอียด</label>
                            <textarea name="description" class="form-control" id="description" rows="5" placeholder="กรอกรายละเอียด"></textarea>
                        </div>

                        <div class="col-md-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary px-4"><i class="bx bx-save me-1"></i>บันทึก</button>
                                <a href="{{ route('all.management.keywords') }}" class="btn btn-danger px-4"><i class="bx bx-x-circle me-1"></i>ยกเลิก</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(document).ready(function (){
        $('#myForm').validate({
            rules: {
                management_category_id: {
                    required : true,
                },
                keyword_title: {
                    required : true,
                },
            },
            messages :{
                management_category_id: {
                    required : 'กรุณาเลือกเลขหมวด',
                },
                keyword_title: {
                    required : 'กรุณากรอก Keyword เรื่องพิจารณา',
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
