{{-- @extends('admin.admin_dashboard')
@section('admin')
<script src="https://cdn.ckeditor.com/ckeditor5/latest/classic/ckeditor.js"></script>

<div class="page-content">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">แก้ไขรายงานการประชุม</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('meeting.report.summary', $meetingAgenda->id) }}">
                                    กลับไปหน้ารายงาน
                                </a>
                            </li>
                            <li class="breadcrumb-item active">แก้ไขรายงาน</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Meeting Info Card -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-2">
                            <strong>ประเภทการประชุม:</strong>
                            {{ $meetingAgenda->meeting_type->name }}
                        </p>
                        <p class="mb-2">
                            <strong>คณะกรรมการ:</strong>
                            {{ $meetingAgenda->committeeCategory->name }}
                        </p>
                        <p class="mb-2">
                            <strong>ครั้งที่:</strong>
                            {{ $meetingAgenda->meeting_agenda_number }}/{{ $meetingAgenda->meeting_agenda_year }}
                        </p>
                    </div>
                    <div class="col-md-6">
                        <p class="mb-2">
                            <strong>วันที่ประชุม:</strong>
                            {{ \Carbon\Carbon::parse($meetingAgenda->meeting_agenda_date)->format('d/m/Y') }}
                        </p>
                        <p class="mb-2">
                            <strong>เวลา:</strong>
                            {{ $meetingAgenda->meeting_agenda_time }}
                        </p>
                        <p class="mb-2">
                            <strong>สถานที่:</strong>
                            {{ $meetingAgenda->meeting_location }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <form action="{{ route('meeting.report.summary.update', $meetingAgenda->id) }}" method="POST">
            @csrf
            <div class="card">
                <div class="card-body">
                    @foreach($meetingAgenda->sections as $section)
                        <div class="mb-5 border-bottom pb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h5 class="mb-0">{{ $section->section_title }}</h5>
                                <span class="badge bg-info">
                                    {{ isset($approvalsBySection[$section->id]) ? count($approvalsBySection[$section->id]) : 0 }} การรับรอง
                                </span>
                            </div>

                            <!-- Section Content -->
                            <div class="mb-4">
                                <label class="form-label">เนื้อหาวาระ</label>
                                <textarea
                                    class="form-control @error('sections.'.$section->id.'.content') is-invalid @enderror"
                                    name="sections[{{ $section->id }}][content]"
                                    rows="4"
                                >{{ old('sections.'.$section->id.'.content', $section->description) }}</textarea>
                                @error('sections.'.$section->id.'.content')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <!-- Lectures and Items -->
                            @foreach($section->meetingAgendaLectures as $lecture)
                                <div class="ms-4 mb-4">
                                    <h6 class="mb-3">{{ $lecture->lecture_title }}</h6>
                                    <div class="mb-3">
                                        <textarea
                                            class="form-control"
                                            name="sections[{{ $section->id }}][lectures][{{ $lecture->id }}][content]"
                                            rows="3"
                                        >{{ old('sections.'.$section->id.'.lectures.'.$lecture->id.'.content', $lecture->content) }}</textarea>
                                    </div>

                                    @foreach($lecture->meetingAgendaItems as $item)
                                        <div class="ms-4 mb-3">
                                            <label class="form-label">{{ $item->item_title }}</label>
                                            <textarea
                                                class="form-control"
                                                name="sections[{{ $section->id }}][lectures][{{ $lecture->id }}][items][{{ $item->id }}][content]"
                                                rows="3"
                                            >{{ old('sections.'.$section->id.'.lectures.'.$lecture->id.'.items.'.$item->id.'.content', $item->content) }}</textarea>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach

                            <!-- Show Approvals -->
                            @if(isset($approvalsBySection[$section->id]))
                                <div class="mt-3">
                                    <h6 class="mb-3">การรับรองและความคิดเห็น</h6>
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>ผู้รับรอง</th>
                                                    <th>สถานะ</th>
                                                    <th>ความคิดเห็น</th>
                                                    <th>วันที่รับรอง</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($approvalsBySection[$section->id] as $approval)
                                                    <tr>
                                                        <td>{{ $approval['user']->prefix_name }} {{ $approval['user']->first_name }}</td>
                                                        <td>
                                                            <span class="badge bg-{{ $approval['type'] == 'no_changes' ? 'success' : 'warning' }}">
                                                                {{ $approval['type'] == 'no_changes' ? 'ไม่มีแก้ไข' : 'มีแก้ไข' }}
                                                            </span>
                                                        </td>
                                                        <td>{{ $approval['comments'] ?? '-' }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($approval['created_at'])->format('d/m/Y H:i') }}</td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            @endif
                        </div>
                    @endforeach

                    <!-- Action Buttons -->
                    <div class="mt-4">
                        <button type="submit" class="btn btn-primary me-2">บันทึกการแก้ไข</button>
                        <a href="{{ route('meeting.report.summary', $meetingAgenda->id) }}"
                            class="btn btn-secondary">ยกเลิก</a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
    $(document).ready(function() {
        // Initialize CKEditor or any rich text editor if needed
        if (typeof ClassicEditor !== 'undefined') {
            document.querySelectorAll('textarea').forEach(element => {
                ClassicEditor.create(element);
            });
        }
    });
</script>
@endpush
@endsection --}}




{{-- @extends('admin.admin_dashboard')
@section('admin')
<div class="page-content">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">แก้ไขการรับรองและความคิดเห็น</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('meeting.report.summary', $meetingAgenda->id) }}">
                                    กลับไปหน้ารายงาน
                                </a>
                            </li>
                            <li class="breadcrumb-item active">แก้ไขการรับรอง</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Meeting Info Summary -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">ข้อมูลการประชุม
                    <small class="text-muted">
                        ครั้งที่ {{ $meetingAgenda->meeting_agenda_number }}/{{ $meetingAgenda->meeting_agenda_year }}
                    </small>
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>ประเภท:</strong> {{ $meetingAgenda->meeting_type->name }}</p>
                        <p><strong>วันที่:</strong> {{ \Carbon\Carbon::parse($meetingAgenda->meeting_agenda_date)->format('d/m/Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>คณะกรรมการ:</strong> {{ $meetingAgenda->committeeCategory->name }}</p>
                        <p><strong>สถานที่:</strong> {{ $meetingAgenda->meeting_location }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <form action="{{ route('meeting.report.summary.update', $meetingAgenda->id) }}" method="POST">
            @csrf
            @foreach($meetingAgenda->sections as $section)
                @if(isset($approvalsBySection[$section->id]))
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">{{ $section->section_title }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="bg-light">
                                        <tr>
                                            <th style="width: 25%">ผู้รับรอง</th>
                                            <th style="width: 15%">สถานะ</th>
                                            <th style="width: 40%">ความคิดเห็น</th>
                                            <th style="width: 20%">ดำเนินการ</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($approvalsBySection[$section->id] as $approval)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-xs me-2">
                                                            <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                                {{ substr($approval['user']->first_name, 0, 1) }}
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">{{ $approval['user']->prefix_name }} {{ $approval['user']->first_name }}</h6>
                                                            <small class="text-muted">{{ $approval['user']->position->name ?? '' }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <select class="form-select form-select-sm"
                                                        name="approvals[{{ $section->id }}][{{ $approval['approval_id'] }}][type]">
                                                        <option value="no_changes"
                                                            {{ $approval['type'] == 'no_changes' ? 'selected' : '' }}>
                                                            รับรองโดยไม่มีแก้ไข
                                                        </option>
                                                        <option value="with_changes"
                                                            {{ $approval['type'] == 'with_changes' ? 'selected' : '' }}>
                                                            รับรองโดยมีแก้ไข
                                                        </option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <textarea class="form-control form-control-sm"
                                                        name="approvals[{{ $section->id }}][{{ $approval['approval_id'] }}][comments]"
                                                        rows="2">{{ $approval['comments'] }}</textarea>
                                                </td>
                                                <td class="text-center">
                                                    <button type="button" class="btn btn-danger btn-sm delete-approval"
                                                        data-approval-id="{{ $approval['approval_id'] }}"
                                                        data-section-id="{{ $section->id }}">
                                                        <i class="bx bx-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach

            <!-- Action Buttons -->
            <div class="d-flex justify-content-between">
                <a href="{{ route('meeting.report.summary', $meetingAgenda->id) }}"
                    class="btn btn-secondary">
                    ยกเลิก
                </a>
                <button type="submit" class="btn btn-primary">
                    บันทึกการแก้ไข
                </button>
            </div>
        </form>
    </div>
</div>


<script>
$(document).ready(function() {
    // Delete Approval Confirmation
    $('.delete-approval').click(function() {
        const approvalId = $(this).data('approval-id');
        const sectionId = $(this).data('section-id');

        Swal.fire({
            title: 'ยืนยันการลบ?',
            text: "คุณต้องการลบการรับรองนี้ใช่หรือไม่?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'ใช่, ลบ',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                // Send delete request
                $.ajax({
                    url: ''.replace(':id', approvalId),
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}',
                        section_id: sectionId
                    },
                    success: function(response) {
                        Swal.fire(
                            'สำเร็จ!',
                            'ลบการรับรองเรียบร้อยแล้ว',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Error!',
                            'เกิดข้อผิดพลาดในการลบข้อมูล',
                            'error'
                        );
                    }
                });
            }
        });
    });
});
</script>


@endsection --}}

{{-- @extends('admin.admin_dashboard')
@section('admin')
<div class="page-content">
    <div class="container-fluid">
        <!-- Breadcrumb -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="page-title-box d-flex align-items-center justify-content-between">
                    <h4 class="mb-0">แก้ไขการรับรองและความคิดเห็น</h4>
                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item">
                                <a href="{{ route('meeting.report.summary.index') }}">
                                    กลับไปหน้ารายงาน
                                </a>
                            </li>
                            <li class="breadcrumb-item active">แก้ไขการรับรอง</li>
                        </ol>
                    </div>
                </div>
            </div>
        </div>

        <!-- Meeting Info Summary -->
        <div class="card mb-4">
            <div class="card-header bg-light">
                <h5 class="mb-0">ข้อมูลการประชุม
                    <small class="text-muted">
                        ครั้งที่ {{ $meetingAgenda->meeting_agenda_number }}/{{ $meetingAgenda->meeting_agenda_year }}
                    </small>
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>ประเภท:</strong> {{ $meetingAgenda->meeting_type->name }}</p>
                        <p><strong>วันที่:</strong> {{ \Carbon\Carbon::parse($meetingAgenda->meeting_agenda_date)->format('d/m/Y') }}</p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>คณะกรรมการ:</strong> {{ $meetingAgenda->committeeCategory->name }}</p>
                        <p><strong>สถานที่:</strong> {{ $meetingAgenda->meeting_location }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Form -->
        <form action="{{ route('meeting.report.summary.update', $meetingAgenda->id) }}" method="POST">
            @csrf
            @foreach($meetingAgenda->sections as $section)
                @if(isset($approvalsBySection[$section->id]))
                    <div class="card mb-4">
                        <div class="card-header bg-light">
                            <h5 class="mb-0">{{ $section->section_title }}</h5>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead class="bg-light">
                                        <tr>
                                            <th style="width: 25%">ผู้รับรอง</th>
                                            <th style="width: 15%">สถานะ</th>
                                            <th style="width: 40%">ความคิดเห็น</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($approvalsBySection[$section->id] as $approval)
                                            <tr>
                                                <td>
                                                    <div class="d-flex align-items-center">
                                                        <div class="avatar-xs me-2">
                                                            <span class="avatar-title rounded-circle bg-primary-subtle text-primary">
                                                                <i class="bi bi-person"></i>
                                                            </span>
                                                        </div>
                                                        <div>
                                                            <h6 class="mb-0">{{ $approval['user']->prefix_name }} {{ $approval['user']->first_name }}</h6>
                                                            <small class="text-muted">{{ $approval['user']->position->name ?? '' }}</small>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <select class="form-select form-select-sm"
                                                        name="approvals[{{ $section->id }}][{{ $approval['approval_id'] }}][type]">
                                                        <option value="no_changes"
                                                            {{ $approval['type'] == 'no_changes' ? 'selected' : '' }}>
                                                            รับรองโดยไม่มีแก้ไข
                                                        </option>
                                                        <option value="with_changes"
                                                            {{ $approval['type'] == 'with_changes' ? 'selected' : '' }}>
                                                            รับรองโดยมีแก้ไข
                                                        </option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <textarea class="form-control form-control-sm"
                                                        name="approvals[{{ $section->id }}][{{ $approval['approval_id'] }}][comments]"
                                                        rows="2">{{ $approval['comments'] }}</textarea>
                                                </td>

                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach

            <!-- Action Buttons -->
            <div class="d-flex justify-content-between">
                <a href="{{ route('meeting.report.summary.index') }}"
                    class="btn btn-secondary">
                    ยกเลิก
                </a>
                <button type="submit" class="btn btn-primary">
                    บันทึกการแก้ไข
                </button>
            </div>
        </form>
    </div>
</div>

<script>
$(document).ready(function() {
    // Delete Approval Confirmation
    $('.delete-approval').click(function() {
        const approvalId = $(this).data('approval-id');
        const sectionId = $(this).data('section-id');

        Swal.fire({
            title: 'ยืนยันการลบ?',
            text: "คุณต้องการลบการรับรองนี้ใช่หรือไม่?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'ใช่, ลบ',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                // Send delete request
                $.ajax({
                    url: ''.replace(':id', approvalId),
                    type: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}',
                        section_id: sectionId
                    },
                    success: function(response) {
                        Swal.fire(
                            'สำเร็จ!',
                            'ลบการรับรองเรียบร้อยแล้ว',
                            'success'
                        ).then(() => {
                            location.reload();
                        });
                    },
                    error: function(xhr) {
                        Swal.fire(
                            'Error!',
                            'เกิดข้อผิดพลาดในการลบข้อมูล',
                            'error'
                        );
                    }
                });
            }
        });
    });
});
</script>

@endsection --}}

@extends('admin.admin_dashboard')
@section('admin')
<div class="page-content">
    <div class="container-fluid">
        <!-- ส่วน header และข้อมูลการประชุมเดิม -->

        <!-- Admin Approval Section -->
        <div class="card mb-4">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">การรับรองรายงานการประชุมโดย Admin</h5>
                <span class="badge bg-light text-dark">
                    สถานะ:
                    @if($meetingAgenda->is_admin_approved)
                        <span class="text-success">รับรองแล้ว</span>
                    @else
                        <span class="text-warning">รอการรับรอง</span>
                    @endif
                </span>
            </div>
            <div class="card-body">
                <form id="adminApprovalForm" action="" method="POST">
                    @csrf
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label class="form-label">บันทึกการรับรอง (ถ้ามี)</label>
                                <textarea
                                    name="admin_approval_note"
                                    class="form-control"
                                    rows="3"
                                    {{ $meetingAgenda->is_admin_approved ? 'readonly' : '' }}
                                >{{ $meetingAgenda->admin_approval_note }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            @if($meetingAgenda->is_admin_approved)
                                <div class="text-muted">
                                    <small>
                                        รับรองเมื่อ: {{ $meetingAgenda->admin_approved_at->format('d/m/Y H:i') }}
                                        โดย: {{ optional($meetingAgenda->adminApprovedBy)->first_name }}
                                    </small>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6 text-end">
                            @if(!$meetingAgenda->is_admin_approved)
                                <button type="button" class="btn btn-success" onclick="confirmAdminApproval()">
                                    <i class="bx bx-check me-1"></i> รับรองรายงานการประชุม
                                </button>
                            @else
                                <button type="button" class="btn btn-warning" onclick="cancelAdminApproval()">
                                    <i class="bx bx-x me-1"></i> ยกเลิกการรับรอง
                                </button>
                            @endif
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Original Edit Form Content -->
        <!-- ... โค้ดส่วนแก้ไขการรับรองและความคิดเห็นเดิม ... -->

    </div>
</div>


<script>
// เพิ่ม JavaScript สำหรับการรับรองของ Admin
function confirmAdminApproval() {
    Swal.fire({
        title: 'ยืนยันการรับรอง?',
        text: "คุณต้องการรับรองรายงานการประชุมนี้ใช่หรือไม่?",
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'ใช่, รับรอง',
        cancelButtonText: 'ยกเลิก'
    }).then((result) => {
        if (result.isConfirmed) {
            document.getElementById('adminApprovalForm').submit();
        }
    });
}

function cancelAdminApproval() {
    Swal.fire({
        title: 'ยืนยันการยกเลิกการรับรอง?',
        text: "คุณต้องการยกเลิกการรับรองรายงานการประชุมนี้ใช่หรือไม่?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'ใช่, ยกเลิกการรับรอง',
        cancelButtonText: 'ไม่'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '',
                type: 'POST',
                data: {
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    Swal.fire(
                        'สำเร็จ!',
                        'ยกเลิกการรับรองเรียบร้อยแล้ว',
                        'success'
                    ).then(() => {
                        location.reload();
                    });
                },
                error: function(xhr) {
                    Swal.fire(
                        'Error!',
                        'เกิดข้อผิดพลาดในการยกเลิกการรับรอง',
                        'error'
                    );
                }
            });
        }
    });
}
</script>

@endsection
