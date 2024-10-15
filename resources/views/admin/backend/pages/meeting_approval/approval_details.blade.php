<h6>ผู้รับรอง: {{ $approval->user->name }}</h6>
{{-- <p>วันที่รับรอง: {{ $approval->approval_date->format('d/m/Y H:i') }}</p> --}}

@foreach($approval->approvalDetails as $detail)
    <div class="mb-3">
        <h6>{{ $detail->meetingAgendaSection->section_title }}</h6>
        <p>การรับรอง: {{ $detail->approval_type === 'no_changes' ? 'รับรองโดยไม่มีข้อแก้ไข' : 'รับรองโดยมีข้อแก้ไข' }}</p>
        @if($detail->comment)
            <p>ข้อแก้ไข: {{ $detail->comment }}</p>
        @endif
    </div>
@endforeach
