@extends('admin.admin_dashboard')

@section('admin')
<div class="page-content">
    <h6 class="mb-0 text-uppercase">สิทธิ์การประชุมและคณะกรรมการ</h6>
<div class="text-center my-4">
    <a href="https://cisco.webex.com" target="_blank" class="btn btn-primary btn-lg px-5 py-3 shadow">
        <i class="bx bx-video-plus me-2 fs-1"></i>
        <span style="font-size: 1.5rem; font-weight: bold;">เข้าร่วมประชุม Cisco Webex</span>
    </a>
</div>

    <hr>
    <div class="row">
        <div class="col">
            <div class="card radius-10 border-start border-2 border-primary">
                <div class="card-body">
                    @forelse ($user->meetingTypes as $meetingType)
                        <h5 class="card-title fw-bold shadow-sm p-2 bg-light rounded">{{ $meetingType->name }}</h5>
                        <ul class="list-group list-group-flush mb-3">
                            @php
                                $committeeIds = json_decode($meetingType->pivot->committee_ids, true) ?? [];
                            @endphp
                            @forelse ($committeeIds as $committeeId)
                                @php
                                    $committee = $committeecategories->find($committeeId);
                                @endphp
                                @if ($committee)
                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                        <i class="bx bx-check-circle me-2"></i> {{ $committee->name }}
                                        <div class="widgets-icons bg-light-success text-success ms-auto"><i class="bx bx-user-circle"></i></div>
                                    </li>
                                @endif
                            @empty
                                <li class="list-group-item text-center d-flex align-items-center"><i class="bx bx-info-circle me-2"></i>ไม่พบข้อมูลคณะกรรมการสำหรับประเภทการประชุมนี้</li>
                            @endforelse
                        </ul>
                    @empty
                        <p class="text-center">ไม่พบสิทธิ์การประชุมสำหรับผู้ใช้นี้</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <h6 class="mb-0 text-uppercase mt-4">รูปแบบการประชุม</h6>
    <hr>
    <div class="row">
        <div class="col">
            <div class="card radius-10 border-start border-2 border-primary">
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        @if($user->meeting_format_id)
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">{{ $user->meetingFormat->name }}</h5>
                                <div class="widgets-icons bg-light-info text-info ms-auto"><i class="bx bx-line-chart"></i></div>
                            </li>
                        @else
                            <li class="list-group-item text-center">ไม่พบข้อมูลรูปแบบการประชุมสำหรับผู้ใช้นี้</li>
                        @endif
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
