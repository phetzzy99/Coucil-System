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
                        <li class="breadcrumb-item active" aria-current="page">ระเบียบวาระการประชุม</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <a href="{{ route('add.meeting.agenda') }}" class="btn btn-primary">เพิ่มระเบียบวาระการประชุม</a>
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
                                <th>ประเภทการประชุม</th>
                                <th>ชื่อวาระการประชุม</th>
                                <th>ครั้งที่</th>
                                <th>ปี</th>
                                <th>วันที่</th>
                                <th>สร้างโดย</th>
                                <th>status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($meeting_agendas as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->meeting_type->name }}</td>
                                    <td>{{ $item->meeting_agenda_title }}</td>
                                    <td>{{ $item->meeting_agenda_number }}</td>
                                    <td>{{ $item->meeting_agenda_year }}</td>
                                    <td>{{ $item->meeting_agenda_date }}</td>
                                    <td>{{ $item->user->username }}</td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input" type="checkbox" id="status{{ $item->id }}"
                                                {{ $item->status == 1 ? 'checked' : '' }}
                                                onchange="updateStatus({{ $item->id }})">
                                            <label class="form-check-label" for="status{{ $item->id }}">
                                            </label>
                                        </div>                                    </td>
                                    <td>
                                        <a href="{{ route('edit.meeting.agenda', $item->id) }}"
                                            class="btn btn-info px-2"><i class="bx bx-edit"></i></a>
                                        <a href="{{ route('delete.meeting.agenda', $item->id) }}"
                                            class="btn btn-danger px-2" id="delete"><i class="bx bx-trash"></i></a>
                                        <a href="{{ route('add.meeting.agenda.lecture', $item->id) }}"
                                            class="btn btn-warning px-2" title="MeetingAgendaLecture"><i class="lni lni-list"></i></a>
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
                    url: "{{ route('update.status.meeting.agenda', ':id') }}".replace(':id', id),
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
