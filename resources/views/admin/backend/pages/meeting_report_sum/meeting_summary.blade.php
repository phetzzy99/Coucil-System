@extends('admin.admin_dashboard')
@section('admin')
<div class="page-content">
    <div class="container-fluid">
        <!-- Meeting Content -->
        <div class="card">
            <div class="card-body">
                <h5 class="card-title mb-4">รายงานการประชุม
                    <span class="text-muted">
                        ครั้งที่ {{ $meetingAgenda->meeting_agenda_number }}/{{ $meetingAgenda->meeting_agenda_year }}
                    </span>
                </h5>

                @foreach($meetingAgenda->sections as $section)
                    <div class="mb-4">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="text-primary">{{ $section->section_title }}</h6>
                            <span class="badge bg-info">
                                {{ $section->approvalDetails->count() }} การรับรอง
                            </span>
                        </div>

                        <!-- ส่วนแสดงความคิดเห็นและการรับรอง -->
                        @if(isset($approvalsBySection[$section->id]))
                            <div class="mt-3">
                                <div class="card border shadow-none">
                                    <div class="card-header bg-light">
                                        <h6 class="mb-0">การรับรองและความคิดเห็น</h6>
                                    </div>
                                    <div class="card-body">
                                        <div class="table-responsive">
                                            <table class="table table-hover">
                                                <thead>
                                                    <tr>
                                                        <th style="width: 20%">ผู้รับรอง</th>
                                                        <th style="width: 15%">สถานะ</th>
                                                        <th style="width: 45%">ความคิดเห็น</th>
                                                        <th style="width: 20%">วันที่รับรอง</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($approvalsBySection[$section->id] as $approval)
                                                        <tr>
                                                            <td>
                                                                <div class="d-flex align-items-center">
                                                                    <div class="flex-shrink-0">
                                                                        <div class="avatar avatar-xs me-2">
                                                                            <span class="avatar-title rounded-circle bg-soft-primary text-primary">
                                                                                {{ substr($approval['user']->first_name, 0, 1) }}
                                                                            </span>
                                                                        </div>
                                                                    </div>
                                                                    <div class="flex-grow-1 ms-2">
                                                                        {{ $approval['user']->prefix_name }} {{ $approval['user']->first_name }} {{ $approval['user']->last_name }}
                                                                        <div class="small text-muted">
                                                                            {{ $approval['user']->position->name ?? '' }}
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td>
                                                                @if($approval['type'] == 'no_changes')
                                                                    <span class="badge bg-success">รับรองโดยไม่มีแก้ไข</span>
                                                                @else
                                                                    <span class="badge bg-warning">รับรองโดยมีแก้ไข</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if($approval['comments'])
                                                                    <div class="comment-text">
                                                                        {{ $approval['comments'] }}
                                                                    </div>
                                                                @else
                                                                    <span class="text-muted">-</span>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <div class="small text-muted">
                                                                    {{-- {{ \Carbon\Carbon::parse($approval['created_at'])->format('d/m/Y H:i') }} --}}
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>

                                        <!-- สรุปการรับรอง -->
                                        <div class="mt-3 p-3 bg-light rounded">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>สรุปการรับรอง:</strong>
                                                    <span class="ms-2">
                                                        {{ count($approvalsBySection[$section->id]) }} การรับรอง
                                                    </span>
                                                </div>
                                                <div>
                                                    @php
                                                        $noChangesCount = collect($approvalsBySection[$section->id])
                                                            ->where('type', 'no_changes')
                                                            ->count();
                                                        $withChangesCount = collect($approvalsBySection[$section->id])
                                                            ->where('type', 'with_changes')
                                                            ->count();
                                                    @endphp
                                                    <span class="badge bg-success me-2">
                                                        ไม่มีแก้ไข: {{ $noChangesCount }}
                                                    </span>
                                                    <span class="badge bg-warning">
                                                        มีแก้ไข: {{ $withChangesCount }}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                @endforeach

            </div>
        </div>
    </div>
</div>

<style>
    .comment-text {
        max-height: 100px;
        overflow-y: auto;
        padding: 8px;
        background-color: #f8f9fa;
        border-radius: 4px;
        font-size: 0.9rem;
    }

    .table > :not(caption) > * > * {
        padding: 0.75rem;
        vertical-align: middle;
    }

    .avatar {
        width: 30px;
        height: 30px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .avatar-title {
        width: 100%;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: 500;
    }
</style>
@endsection
