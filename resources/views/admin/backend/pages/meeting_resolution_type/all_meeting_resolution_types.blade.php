@extends('admin.admin_dashboard')
@section('admin')
{{-- เพิ่มส่วน Script และ CSS สำหรับ Chart.js หากยังไม่มี --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .dashboard-card {
        transition: transform 0.2s;
    }
    .dashboard-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
</style>

<div class="page-content">
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                    </li>
                    <li class="breadcrumb-item active" aria-current="page">รายงานมติที่ประชุม</li>
                </ol>
            </nav>
        </div>
        <div class="ms-auto">
            <div class="btn-group">
                <a href="{{ route('add.meeting.resolution.type') }}" class="btn btn-primary px-3"><i class="bx bx-plus-circle me-1"></i>เพิ่มรายงานมติ</a>
                 <a href="{{ route('search.meeting.resolution.types') }}" class="btn btn-outline-primary px-3"><i class="bx bx-search me-1"></i>ค้นหามติ</a>
            </div>
        </div>
    </div>
    {{-- ส่วน Dashboard --}}
    <div class="row mb-4">
        {{-- คำนวณข้อมูลสรุป --}}
        @php
            $totalResolutions = $resolutionTypes->count();
            $completedCount = $resolutionTypes->where('task_status', 'completed')->count();
            $inProgressCount = $resolutionTypes->where('task_status', 'in_progress')->count();
            $notStartedCount = $resolutionTypes->where('task_status', 'not_started')->count();
            $resolutionsByCommittee = $resolutionTypes->groupBy('committeeCategory.name');
            $resolutionsByMeetingType = $resolutionTypes->groupBy('meetingType.name');
        @endphp

        {{-- การ์ดสรุป --}}
        <div class="col-md-3">
            <div class="card radius-10 border-primary border-start border-0 border-4 dashboard-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">มติทั้งหมด</p>
                            <h4 class="my-1 text-primary">{{ $totalResolutions }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-primary text-white ms-auto"><i class='bx bxs-archive'></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card radius-10 border-success border-start border-0 border-4 dashboard-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">ดำเนินการแล้ว</p>
                            <h4 class="my-1 text-success">{{ $completedCount }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-success text-white ms-auto"><i class='bx bxs-check-circle'></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card radius-10 border-warning border-start border-0 border-4 dashboard-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">กำลังดำเนินการ</p>
                            <h4 class="my-1 text-warning">{{ $inProgressCount }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-warning text-white ms-auto"><i class='bx bxs-time-five'></i></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card radius-10 border-danger border-start border-0 border-4 dashboard-card">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="mb-0 text-secondary">ยังไม่ดำเนินการ</p>
                            <h4 class="my-1 text-danger">{{ $notStartedCount }}</h4>
                        </div>
                        <div class="widgets-icons-2 rounded-circle bg-gradient-danger text-white ms-auto"><i class='bx bxs-error-circle'></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ส่วนกราฟ --}}
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">สัดส่วนสถานะการดำเนินการ</h6>
                    <canvas id="statusPieChart"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">จำนวนมติตามคณะกรรมการ</h6>
                    <canvas id="committeeBarChart"></canvas>
                </div>
            </div>
        </div>
         <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <h6 class="card-title">จำนวนมติตามประเภทการประชุม</h6>
                    <canvas id="meetingTypeBarChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    {{-- สิ้นสุดส่วน Dashboard --}}


    <div class="card">
        <div class="card-body">
             <h5 class="card-title">รายการมติที่ประชุมทั้งหมด</h5>
            <hr/>
            <div class="table-responsive">
                <table id="resolutionTypesTable" class="table table-striped table-bordered" style="width:100%">
                    <thead>
                        <tr>
                            <th>ลำดับ</th>
                            <th>ประเภทคณะกรรมการ</th>
                            <th>ประเภทการประชุม</th>
                            <th>ครั้งที่/ปี</th>
                            <th>วันที่ประชุม</th>
                            <th>เรื่องพิจารณา</th>
                            <th>วาระการประชุม</th>
                            <th>สถานะ</th>
                            <th>เอกสาร</th>
                            <th>การจัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($resolutionTypes as $key => $item)
                            <tr>
                                <td class="text-center">{{ $key + 1 }}</td>
                                <td>{{ $item->managementCategory->name ?? 'N/A' }}</td>
                                <td>{{ $item->meetingType->name ?? 'N/A' }}</td>
                                <td class="text-center">{{ $item->meeting_no }}/{{ $item->meeting_year }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->meeting_date)->format('j M Y') }}</td>
                                <td>{{ $item->name }}</td>
                                <td>{{ $item->agenda_title }}</td>
                                <td class="text-center">
                                    @if($item->task_status == 'completed')
                                        <span class="badge bg-success">ดำเนินการแล้ว</span>
                                    @elseif($item->task_status == 'in_progress')
                                        <span class="badge bg-warning text-dark">อยู่ระหว่างดำเนินการ</span>
                                    @else
                                        <span class="badge bg-danger">ยังไม่ดำเนินการ</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($item->document)
                                        <a href="{{ asset($item->document) }}" target="_blank" class="btn btn-outline-primary btn-sm">
                                         <i class="bx bx-file"></i> ดูเอกสาร</a>
                                    @else
                                        <span class="badge bg-secondary">ไม่มีเอกสาร</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('edit.meeting.resolution.type', $item->id) }}" class="btn btn-info btn-sm" title="แก้ไข"><i class="bx bx-edit"></i></a>
                                    <a href="{{ route('delete.meeting.resolution.type', $item->id) }}" class="btn btn-danger btn-sm" id="delete" title="ลบ"><i class="bx bx-trash"></i></a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Script สำหรับสร้างกราฟ --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    // ข้อมูลสำหรับกราฟสถานะ
    const statusData = {
        labels: ['ดำเนินการแล้ว', 'กำลังดำเนินการ', 'ยังไม่ดำเนินการ'],
        datasets: [{
            label: 'สถานะการดำเนินการ',
            data: [{{ $completedCount }}, {{ $inProgressCount }}, {{ $notStartedCount }}],
            backgroundColor: [
                'rgba(40, 167, 69, 0.7)', // Success
                'rgba(255, 193, 7, 0.7)',  // Warning
                'rgba(220, 53, 69, 0.7)'   // Danger
            ],
            borderColor: [
                'rgba(40, 167, 69, 1)',
                'rgba(255, 193, 7, 1)',
                'rgba(220, 53, 69, 1)'
            ],
            borderWidth: 1
        }]
    };

    // สร้างกราฟวงกลมสถานะ
    const statusCtx = document.getElementById('statusPieChart').getContext('2d');
    new Chart(statusCtx, {
        type: 'pie',
        data: statusData,
        options: {
            responsive: true,
            plugins: {
                legend: {
                    position: 'top',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            if (context.parsed !== null) {
                                label += context.parsed;
                            }
                            return label;
                        }
                    }
                }
            }
        },
    });

    // ข้อมูลสำหรับกราฟคณะกรรมการ
    const committeeLabels = {!! json_encode($resolutionsByCommittee->keys()) !!};
    const committeeDataValues = {!! json_encode($resolutionsByCommittee->map->count()->values()) !!};
    const committeeData = {
        labels: committeeLabels,
        datasets: [{
            label: 'จำนวนมติ',
            data: committeeDataValues,
            backgroundColor: 'rgba(54, 162, 235, 0.7)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    };

     // สร้างกราฟแท่งคณะกรรมการ
    const committeeCtx = document.getElementById('committeeBarChart').getContext('2d');
    new Chart(committeeCtx, {
        type: 'bar',
        data: committeeData,
        options: {
            indexAxis: 'y', // ทำให้เป็นกราฟแท่งแนวนอน
            scales: {
                x: {
                    beginAtZero: true,
                     ticks: {
                        stepSize: 1 // กำหนดให้แกน x แสดงเฉพาะจำนวนเต็ม
                    }
                }
            },
            responsive: true,
            plugins: {
                legend: {
                    display: false // ซ่อน legend เพราะมีแค่ชุดข้อมูลเดียว
                }
            }
        }
    });


    // ข้อมูลสำหรับกราฟประเภทการประชุม
    const meetingTypeLabels = {!! json_encode($resolutionsByMeetingType->keys()) !!};
    const meetingTypeDataValues = {!! json_encode($resolutionsByMeetingType->map->count()->values()) !!};
    const meetingTypeData = {
        labels: meetingTypeLabels,
        datasets: [{
            label: 'จำนวนมติ',
            data: meetingTypeDataValues,
             backgroundColor: 'rgba(75, 192, 192, 0.7)',
             borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1
        }]
    };

     // สร้างกราฟแท่งประเภทการประชุม
    const meetingTypeCtx = document.getElementById('meetingTypeBarChart').getContext('2d');
    new Chart(meetingTypeCtx, {
        type: 'bar',
        data: meetingTypeData,
        options: {
             indexAxis: 'y', // ทำให้เป็นกราฟแท่งแนวนอน
            scales: {
                 x: {
                    beginAtZero: true,
                     ticks: {
                        stepSize: 1 // กำหนดให้แกน x แสดงเฉพาะจำนวนเต็ม
                    }
                }
            },
            responsive: true,
             plugins: {
                legend: {
                    display: false
                }
            }
        }
    });

    // ตั้งค่า DataTable
     $('#resolutionTypesTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Thai.json"
            },
             "pageLength": 10, // จำนวนรายการต่อหน้าเริ่มต้น
             "lengthMenu": [[10, 25, 50, -1], [10, 25, 50, "ทั้งหมด"]], // ตัวเลือกจำนวนรายการต่อหน้า
             "order": [[0, "asc"]], // เรียงลำดับเริ่มต้นตามคอลัมน์แรก (ลำดับ) จากน้อยไปมาก
              "columnDefs": [
                { "orderable": false, "targets": [8, 9] } // ปิดการเรียงลำดับสำหรับคอลัมน์เอกสารและการจัดการ
            ]
        });

});
</script>
@endsection
