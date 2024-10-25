@extends('admin.admin_dashboard')
@section('admin')
    <div class="page-content">
        <div class="container-fluid">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-4">รายงานการประชุมที่รับรองแล้ว</h5>

                    <div class="table-responsive">
                        <table id="approved-reports-table" class="table table-bordered dt-responsive nowrap" style="width:100%">
                            <thead>
                                <tr>
                                    <th>ประเภทการประชุม</th>
                                    <th>ครั้งที่/ปี</th>
                                    <th>วันที่ประชุม</th>
                                    <th>ผู้รับรอง</th>
                                    <th>วันที่รับรอง</th>
                                    <th>การจัดการ</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($approvedReports as $report)
                                    <tr>
                                        <td>{{ $report->meeting_type->name }}</td>
                                        <td>{{ $report->meeting_agenda_number }}/{{ $report->meeting_agenda_year }}</td>
                                        <td>{{ \Carbon\Carbon::parse($report->meeting_agenda_date)->format('d/m/Y') }}</td>
                                        <td>
                                            {{ $report->adminApprovedBy->first_name }}
                                            {{ $report->adminApprovedBy->last_name }}
                                        </td>
                                        <td>{{ \Carbon\Carbon::parse($report->admin_approved_at)->format('d/m/Y H:i') }}
                                        </td>
                                        <td>
                                            <a href="{{ route('list.approved.meeting.reports', $report->id) }}"
                                                class="btn btn-info btn-sm">
                                                <i class="fas fa-eye"></i> ดูรายละเอียด
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <script>
                        $(document).ready(function () {
                            $('#approved-reports-table').DataTable({
                                "pageLength": 25,
                                "lengthMenu": [
                                    [10, 25, 50, -1],
                                    [10, 25, 50, "All"]
                                ],
                                "language": {
                                    "url": "//cdn.datatables.net/plug-ins/1.10.25/i18n/Thai.json"
                                },
                                "columnDefs": [{
                                    "targets": [5],
                                    "orderable": false
                                }]
                            });
                        });
                    </script>
                </div>
            </div>
        </div>
    </div>
@endsection

