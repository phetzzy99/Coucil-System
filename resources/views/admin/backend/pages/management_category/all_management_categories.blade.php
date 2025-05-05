@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">
    <div class="row profile-body">
        <div class="col-md-12 col-xl-12 middle-wrapper">
            <div class="row">
                <div class="card">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="card-title"><i class="bx bx-list-check me-2"></i>รายการหมวดด้านการบริหารทั้งหมด</h6>
                            <a href="{{ route('add.management.category') }}" class="btn btn-primary"><i class="bx bx-plus-circle me-1"></i>เพิ่มหมวดใหม่</a>
                        </div>
                        <div class="table-responsive">
                            <table id="example" class="table table-striped table-bordered" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>ลำดับ</th>
                                        <th>เลขหมวด</th>
                                        <th>ชื่อหมวด</th>
                                        <th>รายละเอียด</th>
                                        <th>การจัดการ</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($managementCategories as $key => $item)
                                    <tr>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $item->category_code }}</td>
                                        <td>{{ $item->name }}</td>
                                        <td>{{ Str::limit($item->description, 50) }}</td>
                                        <td>
                                            <a href="{{ route('edit.management.category', $item->id) }}" class="btn btn-info px-2"><i class="bx bx-edit"></i></a>
                                            <a href="{{ route('delete.management.category', $item->id) }}" class="btn btn-danger px-2" id="delete"><i class="bx bx-trash"></i></a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
