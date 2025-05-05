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
                        <li class="breadcrumb-item active" aria-current="page">รายการมติการประชุมทั้งหมด</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <a href="{{ route('add.meeting.resolution') }}" class="btn btn-primary"><i class="bx bx-plus-circle"></i> เพิ่มมติการประชุม</a>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->

        <@php
            function formatThaiDate($dateString) {
                $months = [
                    'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน',
                    'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
                ];

                $date = new DateTime($dateString);
                $day = $date->format('d');
                $month = $months[$date->format('n') - 1];
                $year = $date->format('Y') + 543;

                return $day . ' ' . $month . ' ' . $year;
            }
        @endphp

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                        <thead>
                            <tr>
                                <th>ลำดับ</th>
                                {{-- <th>ประเภทคณะกรรมการ</th> --}}
                                <th>ประเภทการประชุม</th>
                                <th>รายงานการประชุม</th>
                                <th>วาระการประชุม</th>
                                {{-- <th>เรื่องที่ได้รับมอบหมาย</th> --}}
                                {{-- <th>ผู้รับผิดชอบ</th> --}}
                                <th>สถานะมติ</th>
                                <th>สถานะงาน</th>
                                <th>วันที่มีมติ</th>
                                <th>การจัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($meeting_resolutions as $key => $item)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                {{-- <td>{{ $item->committeeCategory->name }}</td> --}}
                                <td>{{ $item->meetingType->name }}</td>
                                <td>{{ $item->meetingAgenda->meeting_agenda_title }}</td>
                                <td>{{ $item->meetingAgendaSection->section_title }}</td>
                                {{-- <td>{{ $item->task_title }}</td> --}}
                                {{-- <td>{{ $item->responsible_person }}</td> --}}
                                <td>
                                    @if($item->resolution_status == 'approved')
                                        <span class="badge bg-success">อนุมัติ</span>
                                    @elseif($item->resolution_status == 'rejected')
                                        <span class="badge bg-danger">ไม่อนุมัติ</span>
                                    @else
                                        <span class="badge bg-warning">รอพิจารณา</span>
                                    @endif
                                </td>
                                <td>
                                    @if($item->task_status == 'completed')
                                        <span class="badge bg-success">ดำเนินการแล้ว</span>
                                    @elseif($item->task_status == 'in_progress')
                                        <span class="badge bg-warning">อยู่ระหว่างดำเนินการ</span>
                                    @else
                                        <span class="badge bg-danger">ยังไม่ดำเนินการ</span>
                                    @endif
                                </td>
                                <td>{{ formatThaiDate($item->resolution_date) }}</td>
                                <td>
                                    <div class="d-flex">
                                        <a href="{{ route('edit.meeting.resolution', $item->id) }}" class="btn btn-info btn-sm me-2" title="แก้ไข">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="{{ route('delete.meeting.resolution', $item->id) }}" class="btn btn-danger btn-sm" id="delete" title="ลบ">
                                            <i class="fas fa-trash-alt"></i>
                                        </a>
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

@endsection
