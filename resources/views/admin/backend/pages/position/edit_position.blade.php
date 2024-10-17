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
                        <li class="breadcrumb-item active" aria-current="page">แก้ไขข้อบังคับ</li>
                    </ol>
                </nav>
            </div>

        </div>
        <!--end breadcrumb-->

            <div class="card">
                <div class="card-body p-4">
                    <h5 class="mb-4">แก้ไขข้อมูลตำแหน่ง</h5>
                    <form id="myForm" action="{{ route('update.position', $editPosition->id) }}" method="POST" class="row g-3" enctype="multipart/form-data">
                        @csrf
                        @method('POST')

                        <div class="form-group col-md-6">
                            <label for="name" class="form-label">ชื่อตำแหน่ง</label>
                            <input type="text" class="form-control" name="name" value="{{ $editPosition->name }}" placeholder="ชื่อตำแหน่ง" id="name" required>
                        </div>

                        <div class="col-md-12">
                            <div class="d-md-flex d-grid align-items-center gap-3">
                                <button type="submit" class="btn btn-primary px-4">แก้ไข</button>
                                <a href="{{ route('all.position') }}" class="btn btn-danger px-4">ยกเลิก</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

@endsection
