@extends('admin.admin_dashboard')
@section('admin')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">รายงานการประชุม ทั้งหมด</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <a href="{{ route('add.meeting.report') }}" class="btn btn-primary">เพิ่มรายงานการประชุม </a>

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
                                <th>ลำดับ</th>
                                <th>คณะกรรมการ</th>
                                <th>ประภทการประชุม</th>
                                <th>ชื่อรายงานการประชุม</th>
                                <th>รายละเอียด</th>
                                <th>ครั้งที่</th>
                                <th>วันที่</th>
                                <th>เวลา</th>
                                <th>ปี</th>
                                <th>pdf</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($meeting_reports as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->committee_category->name }}</td>
                                    <td>{{ $item->meeting_type->name }}</td>
                                    <td>{{ $item->title }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td>{{ $item->meeting_no }}</td>
                                    <td>{{ $item->date }}</td>
                                    <td>{{ $item->time }}</td>
                                    <td>{{ $item->year }}</td>
                                    <td>
                                        @if ($item->pdf)
                                            <a href="{{ asset($item->pdf) }}" target="_blank" class="badge bg-success">ดูไฟล์ PDF</a>
                                        @else
                                            <span class="badge bg-danger">ไม่มีไฟล์ PDF</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="" class="btn btn-info px-5">แก้ไข </a>
                                        <a href="" class="btn btn-danger px-5" id="delete">ลบ </a>
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>

                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        function updateStatus(id) {
            if (id) {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('update.status.regulation.meeting', ':id') }}".replace(':id', id),
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status == 1) {
                            $('#status' + id).prop('checked', true);
                        } else {
                            $('#status' + id).prop('checked', false);
                        }
                        // แสดงข้อความสำเร็จ
                        toastr.success(response.message);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        // แสดงข้อความผิดพลาด
                        toastr.error('An error occurred while updating the status.');
                    }
                });
            } else {
                toastr.error('Missing required parameter: id');
            }
        }
    </script>
@endsection
