
@php
    use Carbon\Carbon;

    // ตั้งค่า locale เป็นไทย
    Carbon::setLocale('th');

    // แปลงวันที่เป็น Carbon instance
    $meeting_date = Carbon::parse($approval->approval_date);

    // อาร์เรย์สำหรับชื่อวันและเดือนภาษาไทย
    $thai_days = ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'];
    $thai_months = [
        1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม', 4 => 'เมษายน', 5 => 'พฤษภาคม', 6 => 'มิถุนายน',
        7 => 'กรกฎาคม', 8 => 'สิงหาคม', 9 => 'กันยายน', 10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม',
    ];

    // สร้างสตริงวันที่ภาษาไทย
    $thai_date = "วัน" . $thai_days[$meeting_date->dayOfWeek] . "ที่ " . $meeting_date->day . ' ' . $thai_months[$meeting_date->month] . ' พ.ศ. ' . ($meeting_date->year + 543);
@endphp

<div class="list-group">
    <h6 class="list-group-item list-group-item-secondary">ผู้รับรอง: {{ $approval->user->first_name }} {{ $approval->user->last_name }}</h6>
    <p class="list-group-item">วันที่รับรอง: {{ $thai_date }}</p>

    @foreach($approval->approvalDetails as $detail)
        <div class="list-group-item">
            <h6>{{ $detail->meetingAgendaSection->section_title }}</h6>
            <p>การรับรอง: {{ $detail->approval_type === 'no_changes' ? 'รับรองโดยไม่มีข้อแก้ไข' : 'รับรองโดยมีข้อแก้ไข' }}</p>
            @if($detail->comments)
                <p><span class="badge bg-success">ข้อแก้ไข</span>: {!! $detail->comments !!}</p>
            @endif
        </div>
    @endforeach
</div>

