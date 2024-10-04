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
                        <li class="breadcrumb-item active" aria-current="page">แก้ไขประเภทการประชุม</li>
                    </ol>
                </nav>
            </div>

        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body p-4">
                <h5 class="mb-4">แก้ไขประเภทการประชุม</h5>
                <form id="myForm" action="{{ route('update.meeting.type') }}" method="post" class="row g-3"
                    enctype="multipart/form-data">
                    @csrf

                    <input type="hidden" name="id" value="{{ $meeting_type->id }}">

                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label">ชื่อประเภทการประชุม</label>
                        <input type="text" name="name" placeholder="เพิ่มประเภทการประชุม" class="form-control" id="input1" value="{{ $meeting_type->name }}">
                    </div>

                    <div class="form-group col-md-12">
                        <label for="input1" class="form-label">รายละเอียด</label>
                        <textarea name="description" class="form-control" rows="10" c>{!! $meeting_type->description !!}</textarea>
                    </div>


                    <div class="col-md-12">
                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-primary px-4">บันทึก</button>
                            <a href="{{ route('all.meeting.type') }}" class="btn btn-danger px-4">ยกเลิก</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
