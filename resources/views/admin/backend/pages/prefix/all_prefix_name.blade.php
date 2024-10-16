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
                        <li class="breadcrumb-item active" aria-current="page">ข้อมูลทั้งหมด</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <a href="{{ route('add.prefix.name') }}" class="btn btn-primary  ">เพิ่มข้อมูล </a>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>ลําดับ</th>
                                <th>คํานําหน้า</th>
                                <th>การดำเนินการ</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($prefixname as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>
                                        <a href="{{ route('edit.prefix.name', $item->id) }}" class="btn btn-info px-5">แก้ไข </a>
                                        <a href="{{ route('delete.prefix.name', $item->id) }}" class="btn btn-danger px-5"
                                            id="delete">ลบ </a>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
