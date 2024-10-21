@extends('admin.admin_dashboard')

@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="card mb-4 shadow-sm">
                <div class="card-header bg-white py-3">
                    <h5 class="mb-0" style="text-align: center">รับรองรายการการประชุม</h5>
                </div>
                <div class="card-body p-3">
                    <div class="row row-cols-1 row-cols-md-3 g-4">
                        @foreach ($my_meetings as $item)
                        <div class="col">
                            <div class="card h-100 border-0">
                                <div class="d-flex flex-column justify-content-center">
                                    <img src="{{ asset('uploads/no_image.jpg') }}" class="rounded-circle mx-auto d-block"
                                        width="90" height="90" alt="...">
                                </div>
                                <div class="card-body p-2">
                                    <h5 class="card-title">
                                        <a href="{{ route('meeting.detail', $item->id) }}" class="text-decoration-none text-dark">
                                            {{ $item->meeting_agenda_title }}
                                        </a>
                                    </h5>
                                    <h6 class="card-subtitle mb-1 text-muted">
                                        ครั้งที่ {{ $item->meeting_agenda_number }} / {{ $item->meeting_agenda_year }}
                                    </h6>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            สร้างโดย: {{ $item->user->first_name }} {{ $item->user->last_name }}
                                        </small>
                                    </p>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            <i class="bi bi-calendar-event me-1"></i>
                                            ปรับปรุงข้อมูลวันที่ {{ $item->created_at->format('d M Y') }}
                                        </small>
                                    </p>
                                    @php
                                        $section = App\Models\MeetingAgendaSection::where('meeting_agenda_id', $item->id)->get();
                                    @endphp
                                    <p class="card-text">
                                        <span class="badge bg-primary">
                                            วาระการประชุม: {{ count($section) }} รายการ
                                        </span>
                                    </p>
                                </div>
                                <div class="card-footer bg-white border-top-0 d-flex align-items-center justify-content-center">
                                    {{-- <a href="{{ route('meeting.approval.detail', $item->id) }}" class="btn btn-outline-primary btn-md">
                                        <i class="lni lni-eye me-1"></i> ดูรายละเอียด
                                    </a> --}}
                                    @php
                                        $user = Auth::user();
                                        $userMeetingTypes = $user->meetingTypes;
                                        $userCommitteeIds = [];
                                        foreach ($userMeetingTypes as $meetingType) {
                                            $committeeIds = json_decode($meetingType->pivot->committee_ids, true);
                                            $userCommitteeIds = array_merge($userCommitteeIds, $committeeIds ?? []);
                                        }
                                        $userCommitteeIds = array_unique($userCommitteeIds);

                                        $hasPermission = $userMeetingTypes->contains($item->meeting_type_id) &&
                                                        in_array($item->committee_category_id, $userCommitteeIds);
                                    @endphp
                                    @if ($hasPermission)
                                    <a href="{{ route('meeting.approval.detail', $item->id) }}" class="btn btn-outline-primary btn-md">
                                        <i class="lni lni-eye me-1"></i> ดูรายละเอียด
                                    </a>
                                    @else
                                    <span class="text-muted">ไม่มีสิทธิ์เข้าถึง</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
@endpush

