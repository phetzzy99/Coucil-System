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
                    <li class="breadcrumb-item"><a href="{{ route('search.meeting.resolution') }}">สืบค้นข้อมูลมติการประชุม</a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">ผลการค้นหา</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <a href="{{ route('search.meeting.resolution') }}" class="btn btn-outline-primary">
                <i class="bx bx-search me-1"></i> ค้นหาใหม่
            </a>
        </div>
    </div>
    <!--end breadcrumb-->

    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="mb-0">
                    <i class="bx bx-list-ul me-1"></i> ผลการค้นหามติการประชุม
                </h5>
                <span class="badge bg-primary rounded-pill fs-6">พบ {{ count($meeting_resolutions) }} รายการ</span>
            </div>

            @if(count($meeting_resolutions) > 0)
            <div class="table-responsive">
                <table id="resolutionTable" class="table table-striped table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th class="text-center" width="5%">#</th>
                            <th width="20%">รายงานการประชุม</th>
                            <th width="15%">วาระการประชุม</th>
                            <th width="20%">เรื่องที่ได้รับมอบหมาย</th>
                            <th width="15%">ผู้รับผิดชอบ</th>
                            <th width="10%">สถานะงาน</th>
                            <th width="10%">วันที่มีมติ</th>
                            <th width="5%">ดูรายละเอียด</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($meeting_resolutions as $key => $item)
                        <tr>
                            <td class="text-center">{{ $key+1 }}</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="font-weight-bold">
                                        {{ $item->meetingAgenda->meeting_agenda_title }}
                                    </div>
                                </div>
                            </td>
                            <td>{{ $item->meetingAgendaSection->section_title }}</td>
                            <td>{{ $item->task_title }}</td>
                            <td>{{ $item->responsible_person }}</td>
                            <td>
                                @if($item->task_status == 'completed')
                                    <span class="badge bg-success rounded-pill">ดำเนินการแล้ว</span>
                                @elseif($item->task_status == 'in_progress')
                                    <span class="badge bg-warning rounded-pill">อยู่ระหว่างดำเนินการ</span>
                                @else
                                    <span class="badge bg-danger rounded-pill">ยังไม่ดำเนินการ</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($item->resolution_date)->format('d/m/Y') }}</td>
                            <td>
                                <button type="button" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#resolutionModal{{ $item->id }}">
                                    <i class="bx bx-show"></i>
                                </button>
                            </td>
                        </tr>

                        <!-- Modal แสดงรายละเอียด -->
                        <div class="modal fade" id="resolutionModal{{ $item->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg modal-dialog-scrollable">
                                <div class="modal-content">
                                    <div class="modal-header" style="background: linear-gradient(to right, #4e73df, #36b9cc);">
                                        <h5 class="modal-title text-white">รายละเอียดมติการประชุม</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div class="card border-0 shadow-none mb-3">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0">ข้อมูลการประชุม</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row mb-2">
                                                    <div class="col-md-4 fw-bold">ประเภทคณะกรรมการ:</div>
                                                    <div class="col-md-8">{{ $item->committeeCategory->committee_category_name }}</div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-4 fw-bold">ประเภทการประชุม:</div>
                                                    <div class="col-md-8">{{ $item->meetingType->meeting_type_name }}</div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-4 fw-bold">รายงานการประชุม:</div>
                                                    <div class="col-md-8">{{ $item->meetingAgenda->meeting_agenda_title }}</div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-4 fw-bold">วาระการประชุม:</div>
                                                    <div class="col-md-8">{{ $item->meetingAgendaSection->section_title }}</div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-4 fw-bold">ผู้เสนอวาระ:</div>
                                                    <div class="col-md-8">{{ $item->proposer }}</div>
                                                </div>
                                                @if($item->document)
                                                <div class="row mb-2">
                                                    <div class="col-md-4 fw-bold">เอกสารประกอบ:</div>
                                                    <div class="col-md-8">
                                                        <a href="{{ asset($item->document) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                                            <i class="bx bx-file"></i> ดูเอกสาร
                                                        </a>
                                                    </div>
                                                </div>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="card border-0 shadow-none mb-3">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0">มติที่ประชุม</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row mb-2">
                                                    <div class="col-md-12">
                                                        {!! $item->resolution_text !!}
                                                    </div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-4 fw-bold">วันที่มีมติ:</div>
                                                    <div class="col-md-8">{{ \Carbon\Carbon::parse($item->resolution_date)->format('d/m/Y') }}</div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-4 fw-bold">สถานะมติ:</div>
                                                    <div class="col-md-8">
                                                        @if($item->resolution_status == 'approved')
                                                            <span class="badge bg-success">อนุมัติ</span>
                                                        @elseif($item->resolution_status == 'rejected')
                                                            <span class="badge bg-danger">ไม่อนุมัติ</span>
                                                        @else
                                                            <span class="badge bg-warning">รอพิจารณา</span>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="card border-0 shadow-none">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0">การดำเนินการที่ได้รับมอบหมาย</h6>
                                            </div>
                                            <div class="card-body">
                                                <div class="row mb-2">
                                                    <div class="col-md-4 fw-bold">เรื่อง:</div>
                                                    <div class="col-md-8">{{ $item->task_title }}</div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-4 fw-bold">ผู้รับผิดชอบ:</div>
                                                    <div class="col-md-8">{{ $item->responsible_person }}</div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-4 fw-bold">ผลการดำเนินงาน:</div>
                                                    <div class="col-md-8">
                                                        @if($item->task_status == 'completed')
                                                            <span class="badge bg-success">ดำเนินการแล้ว</span>
                                                        @elseif($item->task_status == 'in_progress')
                                                            <span class="badge bg-warning">อยู่ระหว่างดำเนินการ</span>
                                                        @else
                                                            <span class="badge bg-danger">ยังไม่ดำเนินการ</span>
                                                        @endif
                                                    </div>
                                                </div>
                                                <div class="row mb-2">
                                                    <div class="col-md-4 fw-bold">วันที่รายงานผล:</div>
                                                    <div class="col-md-8">{{ \Carbon\Carbon::parse($item->report_date)->format('d/m/Y') }}</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <a href="{{ route('edit.meeting.resolution', $item->id) }}" class="btn btn-primary">
                                            <i class="bx bx-edit"></i> แก้ไข
                                        </a>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ปิด</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
            <div class="alert alert-info text-center py-4">
                <i class="bx bx-info-circle fs-3 mb-2"></i>
                <h5>ไม่พบข้อมูลตามเงื่อนไขที่ค้นหา</h5>
                <p class="mb-0">กรุณาปรับเปลี่ยนเงื่อนไขการค้นหาและลองใหม่อีกครั้ง</p>
            </div>
            @endif
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('#resolutionTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Thai.json"
            },
            "pageLength": 10,
            "ordering": true,
            "responsive": true
        });
    });
</script>
@endsection
