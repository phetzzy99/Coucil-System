{{-- @extends('admin.admin_dashboard')
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
                        <li class="breadcrumb-item active" aria-current="page">สรุปรายงานการประชุม</li>
                    </ol>
                </nav>
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
                                <th>สถานะการรับรอง</th>
                                <th>สถานะการรับรอง (Admin)</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($meetingAgendas as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->meeting_type->name }}</td>
                                    <td>{{ $item->meeting_agenda_title }}</td>
                                    <td>{{ $item->meeting_agenda_number }}</td>
                                    <td>{{ $item->meeting_agenda_year }}</td>
                                    <td>{{ \Carbon\Carbon::parse($item->meeting_agenda_date)->format('d/m/Y') }}</td>
                                    <td>
                                        @php
                                            $totalApprovals = $item->approvals->count();
                                            $completedApprovals = $item->approvals->where('status', 'approved')->count();
                                            $percentage = $totalApprovals > 0 ? ($completedApprovals / $totalApprovals) * 100 : 0;
                                        @endphp
                                        <div class="progress">
                                            <div class="progress-bar bg-success" role="progressbar"
                                                style="width: {{ $percentage }}%"
                                                aria-valuenow="{{ $percentage }}"
                                                aria-valuemin="0"
                                                aria-valuemax="100">
                                                {{ number_format($percentage, 0) }}%
                                            </div>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        @if($item->is_admin_approved)
                                            <span class="badge bg-success">รับรองแล้ว</span>
                                            <br>
                                            <small>{{ \Carbon\Carbon::parse($item->admin_approved_at)->format('d/m/Y H:i') }}</small>
                                        @else
                                            <span class="badge bg-warning">รอการรับรอง</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('meeting.report.summary', $item->id) }}"
                                                class="btn btn-info btn-sm"
                                                title="ดูรายละเอียด">
                                                <i class="bx bx-show"></i>
                                            </a>
                                            <a href="{{ route('meeting.report.summary.edit', $item->id) }}"
                                                class="btn btn-warning btn-sm"
                                                title="แก้ไข">
                                                <i class="bx bx-edit"></i>
                                            </a>
                                            @if(!$item->is_admin_approved)
                                                <button type="button"
                                                    class="btn btn-success btn-sm"
                                                    title="รับรอง"
                                                    onclick="confirmApproval({{ $item->id }})">
                                                    <i class="bx bx-check"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal สำหรับยืนยันการรับรอง -->
    <div class="modal fade" id="approvalModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">ยืนยันการรับรองรายงานการประชุม</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p>คุณต้องการรับรองรายงานการประชุมนี้ใช่หรือไม่?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="button" class="btn btn-success" onclick="approveReport()">ยืนยันการรับรอง</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let selectedReportId = null;

        function confirmApproval(id) {
            selectedReportId = id;
            $('#approvalModal').modal('show');
        }

        function approveReport() {
            if (selectedReportId) {
                $.ajax({
                    type: 'POST',
                    url: "{{ route('meeting.report.summary.admin.approve', '') }}/" + selectedReportId,
                    data: {
                        _token: "{{ csrf_token() }}"
                    },
                    success: function(response) {
                        toastr.success('รับรองรายงานการประชุมเรียบร้อยแล้ว');
                        $('#approvalModal').modal('hide');
                        setTimeout(() => {
                            window.location.reload();
                        }, 1500);
                    },
                    error: function(xhr) {
                        toastr.error('เกิดข้อผิดพลาดในการรับรองรายงานการประชุม');
                    }
                });
            }
        }

        $(document).ready(function() {
            // DataTable initialization
            $('#example').DataTable({
                "processing": true,
                "language": {
                    "sProcessing": "กำลังดำเนินการ...",
                    "sLengthMenu": "แสดง _MENU_ รายการ",
                    "sZeroRecords": "ไม่พบข้อมูล",
                    "sInfo": "แสดง _START_ ถึง _END_ จาก _TOTAL_ รายการ",
                    "sInfoEmpty": "แสดง 0 ถึง 0 จาก 0 รายการ",
                    "sInfoFiltered": "(กรองข้อมูล _MAX_ ทุกรายการ)",
                    "sInfoPostFix": "",
                    "sSearch": "ค้นหา:",
                    "sUrl": "",
                    "oPaginate": {
                        "sFirst": "เริ่มต้น",
                        "sPrevious": "ก่อนหน้า",
                        "sNext": "ถัดไป",
                        "sLast": "สุดท้าย"
                    }
                }
            });
        });
    </script>
@endsection --}}

@extends('admin.admin_dashboard')
@section('admin')
<div class="page-content">
    <!-- Header -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item active" aria-current="page">สรุปรายงานการประชุม</li>
                </ol>
            </nav>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card radius-10 border-primary border-start border-0 border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <h5 class="mb-0 text-primary">{{ $meetingAgendas->count() }}</h5>
                            <p class="mb-0">รายงานทั้งหมด</p>
                        </div>
                        <div class="ms-auto"><i class="bx bx-file fs-3 text-primary"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card radius-10 border-success border-start border-0 border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <h5 class="mb-0 text-success">
                                {{ $meetingAgendas->where('is_admin_approved', true)->count() }}
                            </h5>
                            <p class="mb-0">รับรองแล้ว</p>
                        </div>
                        <div class="ms-auto"><i class="bx bx-check-circle fs-3 text-success"></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card radius-10 border-warning border-start border-0 border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <h5 class="mb-0 text-warning">
                                {{ $meetingAgendas->where('is_admin_approved', false)->count() }}
                            </h5>
                            <p class="mb-0">รอการรับรอง</p>
                        </div>
                        <div class="ms-auto"><i class="bx bx-time fs-3 text-warning"></i></div>
                    </div>
                </div>
            </div>
        </div>
        {{-- <div class="col-md-3">
            <div class="card radius-10 border-info border-start border-0 border-4">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <h5 class="mb-0 text-info">
                                {{ number_format($averageApprovalTime, 1) }}
                            </h5>
                            <p class="mb-0">วันเฉลี่ยในการรับรอง</p>
                        </div>
                        <div class="ms-auto"><i class="bx bx-calendar fs-3 text-info"></i></div>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>

    <!-- Main Table -->
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0">รายการรายงานการประชุม</h5>
                {{-- <div>
                    <button class="btn btn-outline-primary btn-sm" onclick="exportReport()">
                        <i class="bx bx-export me-1"></i> ส่งออกข้อมูล
                    </button>
                </div> --}}
            </div>

            <div class="table-responsive">
                <table id="reportTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th style="width: 5%">ลำดับ</th>
                            <th style="width: 15%">ประเภทการประชุม</th>
                            <th style="width: 20%">ชื่อวาระการประชุม</th>
                            <th style="width: 10%">ครั้งที่/ปี</th>
                            <th style="width: 10%">วันที่</th>
                            {{-- <th style="width: 15%">สถานะการรับรอง</th> --}}
                            <th style="width: 15%">สถานะ Admin</th>
                            <th style="width: 10%">ดำเนินการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($meetingAgendas as $key => $item)
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td>{{ $item->meeting_type->name }}</td>
                                <td>{{ $item->meeting_agenda_title }}</td>
                                <td class="text-center">
                                    {{ $item->meeting_agenda_number }}/{{ $item->meeting_agenda_year }}
                                </td>
                                <td>{{ \Carbon\Carbon::parse($item->meeting_agenda_date)->format('d/m/Y') }}</td>
                                {{-- <td>
                                    @php
                                        $totalApprovals = $item->approvals->count();
                                        $completedApprovals = $item->approvals->where('status', 'approved')->count();
                                        $percentage = $totalApprovals > 0 ? ($completedApprovals / $totalApprovals) * 100 : 0;
                                        $progressClass = $percentage == 100 ? 'bg-success' : ($percentage >= 50 ? 'bg-warning' : 'bg-danger');
                                    @endphp
                                    <div class="d-flex align-items-center">
                                        <div class="progress flex-grow-1" style="height: 8px;">
                                            <div class="progress-bar {{ $progressClass }}"
                                                role="progressbar"
                                                style="width: {{ $percentage }}%">
                                            </div>
                                        </div>
                                        <span class="ms-2">{{ number_format($percentage, 0) }}%</span>
                                    </div>
                                    <small class="text-muted">
                                        ({{ $completedApprovals }}/{{ $totalApprovals }} ท่าน)
                                    </small>
                                </td> --}}
                                <td class="text-center">
                                    @if($item->is_admin_approved)
                                        <div class="d-flex flex-column">
                                            <span class="badge bg-success mb-1">รับรองแล้ว</span>
                                            <small>
                                                {{ \Carbon\Carbon::parse($item->admin_approved_at)->format('d/m/Y H:i') }}
                                                <br>
                                                โดย: {{ optional($item->adminApprovedBy)->first_name }}
                                            </small>
                                        </div>
                                    @else
                                        <span class="badge bg-warning">รอการรับรอง</span>
                                    @endif
                                </td>
                                <td>
                                    <!-- แก้ไขเฉพาะส่วน Action ในตาราง -->
                                        <div class="btn-group">
                                            <!-- ปุ่มดูรายละเอียด -->
                                            <a href="{{ route('meeting.report.summary', $item->id) }}"
                                                class="btn btn-info btn-sm"
                                                data-bs-toggle="tooltip"
                                                title="ดูรายละเอียด">
                                                <i class="bx bx-show"></i>
                                            </a>

                                            <!-- ปุ่มแก้ไขการรับรอง -->
                                            <a href="{{ route('meeting.report.summary.edit', $item->id) }}"
                                                class="btn btn-warning btn-sm"
                                                data-bs-toggle="tooltip"
                                                title="แก้ไขการรับรอง">
                                                <i class="bx bx-edit"></i>
                                            </a>

                                            <!-- ปุ่มรับรองโดย Admin -->
                                            @if(!$item->is_admin_approved)
                                                <button type="button"
                                                    class="btn btn-success btn-sm"
                                                    onclick="showApprovalModal({{ $item->id }})"
                                                    data-bs-toggle="tooltip"
                                                    title="รับรองโดย Admin">
                                                    <i class="bx bx-check-double"></i>
                                                </button>
                                            @else
                                                <!-- แสดงสถานะเมื่อรับรองแล้ว -->
                                                <button type="button"
                                                    class="btn btn-secondary btn-sm"
                                                    disabled
                                                    data-bs-toggle="tooltip"
                                                    title="รับรองแล้วโดย: {{ optional($item->adminApprovedBy)->first_name }}
                                                           เมื่อ: {{ \Carbon\Carbon::parse($item->admin_approved_at)->format('d/m/Y H:i') }}">
                                                    <i class="bx bx-check-double"></i>
                                                </button>
                                                <!-- ปุ่มยกเลิกการรับรอง -->
                                                <button type="button"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="cancelApproval({{ $item->id }})"
                                                    data-bs-toggle="tooltip"
                                                    title="ยกเลิกการรับรอง">
                                                    <i class="bx bx-x"></i>
                                                </button>
                                            @endif
                                        </div>
                                    {{-- <div class="dropdown">
                                        <button class="btn btn-sm btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                            <i class="bx bx-dots-vertical-rounded"></i>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('meeting.report.summary', $item->id) }}">
                                                    <i class="bx bx-show me-2"></i> ดูรายละเอียด
                                                </a>
                                            </li>
                                            @if($percentage == 100 && !$item->is_admin_approved)
                                                <li>
                                                    <a class="dropdown-item" href="#" onclick="showApprovalModal({{ $item->id }})">
                                                        <i class="bx bx-check me-2"></i> รับรองรายงาน
                                                    </a>
                                                </li>
                                            @endif
                                            @if($item->is_admin_approved)
                                                <li>
                                                    <a class="dropdown-item text-danger" href="#" onclick="cancelApproval({{ $item->id }})">
                                                        <i class="bx bx-x me-2"></i> ยกเลิกการรับรอง
                                                    </a>
                                                </li>
                                            @endif
                                        </ul>
                                    </div> --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal การรับรองโดย Admin -->
<div class="modal fade" id="approvalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">รับรองรายงานการประชุม</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="approvalForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label required">บันทึกการรับรอง</label>
                        <textarea name="admin_approval_note" class="form-control" rows="3" required></textarea>
                        <div class="form-text">โปรดระบุบันทึกหรือความคิดเห็นประกอบการรับรอง</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                    <button type="submit" class="btn btn-success">
                        <i class="bx bx-check me-1"></i> ยืนยันการรับรอง
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Initialize DataTable
        $('#reportTable').DataTable({
            "order": [[4, "desc"]],
            "pageLength": 25,
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Thai.json"
            }
        });
    });

    // ฟังก์ชันแสดง Modal รับรอง
    function showApprovalModal(id) {
        const form = document.getElementById('approvalForm');
        form.action = `{{ url('/meeting/report') }}/${id}/admin-approve`;
        new bootstrap.Modal(document.getElementById('approvalModal')).show();
    }

    // จัดการการส่งฟอร์มรับรอง
    $.ajax({
        url: form.action,
        method: 'POST',
        data: new FormData(form),
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    title: 'สำเร็จ!',
                    text: 'รับรองรายงานการประชุมเรียบร้อยแล้ว',
                    icon: 'success',
                    timer: 1500
                }).then(() => {
                    window.location.reload();
                });
            }
        },
        error: function(xhr) {
            Swal.fire({
                title: 'เกิดข้อผิดพลาด!',
                text: xhr.responseJSON?.message || 'ไม่สามารถรับรองรายงานได้',
                icon: 'error'
            });
            submitBtn.disabled = false;
        }
    });

    // ฟังก์ชันยกเลิกการรับรอง
    function cancelApproval(id) {
        Swal.fire({
            title: 'ยืนยันการยกเลิก?',
            text: "คุณต้องการยกเลิกการรับรองรายงานนี้ใช่หรือไม่?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'ใช่, ยกเลิก',
            cancelButtonText: 'ไม่'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: `{{ url('meeting/report') }}/${id}/admin-cancel`,
                    type: 'POST',
                    data: { _token: '{{ csrf_token() }}' },
                    success: function(response) {
                        toastr.success('ยกเลิกการรับรองเรียบร้อยแล้ว');
                        setTimeout(() => window.location.reload(), 1500);
                    },
                    error: function(xhr) {
                        toastr.error('เกิดข้อผิดพลาดในการยกเลิกการรับรอง');
                    }
                });
            }
        });
    }

    // function exportReport() {
    //     // Add export functionality here
    //     toastr.info('กำลังเตรียมไฟล์สำหรับดาวน์โหลด...');
    // }
</script>

<style>
    .progress {
        background-color: #f0f0f0;
    }
    .required:after {
        content: " *";
        color: red;
    }
    .dropdown-item i {
        width: 1rem;
    }
</style>

@endsection
