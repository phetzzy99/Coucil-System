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
                        <li class="breadcrumb-item active" aria-current="page">แก้ไขข้อมูลคำนำหน้า</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body p-4">
                <h5 class="mb-4">แก้ไขข้อมูลคำนำหน้า</h5>
                <form id="myForm" action="{{ route('update.prefix.name', $prefixname->id) }}" method="post"
                    class="row g-3" enctype="multipart/form-data">
                    @csrf
                    @method('POST')

                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label">Prefix Name</label>
                        <input type="text" name="prefix_name" value="{{ $prefixname->title }}"
                            placeholder="Enter Prefix Name" class="form-control" id="input1" required>
                    </div>

                    <div class="col-md-12">
                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-primary px-4">แก้ไข</button>
                            <a href="{{ route('all.prefix.name') }}" class="btn btn-danger px-4">ยกเลิก</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
