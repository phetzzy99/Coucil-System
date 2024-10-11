@extends('admin.admin_dashboard')

@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    @php
        use Carbon\Carbon;

        // ตั้งค่า locale เป็นไทย
        Carbon::setLocale('th');

        // แปลงวันที่เป็น Carbon instance
        $meeting_date = Carbon::parse($my_meetings->meeting_date);

        // อาร์เรย์สำหรับชื่อวันภาษาไทย
        $thai_days = ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'];

        // อาร์เรย์สำหรับชื่อเดือนภาษาไทย
        $thai_months = [
            1 => 'มกราคม',
            2 => 'กุมภาพันธ์',
            3 => 'มีนาคม',
            4 => 'เมษายน',
            5 => 'พฤษภาคม',
            6 => 'มิถุนายน',
            7 => 'กรกฎาคม',
            8 => 'สิงหาคม',
            9 => 'กันยายน',
            10 => 'ตุลาคม',
            11 => 'พฤศจิกายน',
            12 => 'ธันวาคม',
        ];

        // รับชื่อวันภาษาไทย
        $thai_day = $thai_days[$meeting_date->dayOfWeek];

        // รับชื่อเดือนภาษาไทย
        $thai_month = $thai_months[$meeting_date->month];

        // สร้างสตริงวันที่ภาษาไทยพร้อมชื่อวันและเดือน
        $thai_date =
            "วัน{$thai_day}ที่ " . $meeting_date->day . ' ' . $thai_month . ' พ.ศ. ' . ($meeting_date->year + 543);
    @endphp

    <div class="container-fluid py-4">
        <div class="row justify-content-center">
            <div class="col-12 col-lg-10">
                <div class="card shadow-sm mb-4">
                    <div class="card-body p-5">
                        <div class="text-center mb-5">
                            <h5 class="mb-3 text-primary">{{ $my_meetings->meeting_agenda_title }}</h5>
                            <div class="d-inline-block p-3 border border-primary rounded">
                                <p class="mb-1"><strong>การประชุมครั้งที่:</strong>
                                    {{ $my_meetings->meeting_agenda_number }} / {{ $my_meetings->meeting_agenda_year }}</p>
                                <p class="mb-1"><strong>วันที่:</strong> {{ $thai_date }}</p>
                                <p class="mb-0"><strong>สถานที่:</strong> {{ $my_meetings->meeting_location }}</p>
                            </div>
                        </div>

                        @php
                            $sections = App\Models\MeetingAgendaSection::where('meeting_agenda_id', $my_meetings->id)
                                ->orderBy('id', 'asc')
                                ->get();
                        @endphp

                        @foreach ($sections as $index => $section)
                            <div class="mb-5">
                                <h5 class="mb-3 pb-2 border-bottom">{{ $section->section_title }}</h5>
                                @if ($section->description != null)
                                    <div class="ms-4 mb-3">
                                        <p>{!! $section->description !!}</p>
                                    </div>
                                @endif

                                @php
                                    $lectures = App\Models\MeetingAgendaLecture::where('meeting_agenda_section_id', $section->id)->get();
                                    $agendaItems = App\Models\MeetingAgendaItems::where('meeting_agenda_section_id', $section->id)
                                        ->orderBy('id', 'asc')
                                        ->get();
                                @endphp

                                @foreach ($lectures as $lectureIndex => $lecture)
                                    <div class="ms-4 mb-1">
                                        {{-- <h6 class="mb-2">{{ $section->section_title }}</h6> --}}
                                        <div class="ms-4 bg-light p-3 rounded">
                                            <p class="mb-0">{{ $lecture->lecture_title }}</p>
                                        </div>

                                        @php
                                            $lectureItems = $agendaItems->where('meeting_agenda_lecture_id', $lecture->id);
                                        @endphp

                                        @if ($lectureItems->count() > 0)
                                            <ul class="list-unstyled ms-4">
                                                @foreach ($lectureItems as $itemIndex => $item)
                                                    <li class="mb-3">
                                                        {{-- <h6 class="mb-2">{{ $section->section_title }}</h6> --}}
                                                        <div class="ms-4 bg-light p-3 rounded">
                                                            <hp class="mb-0">{{ $item->item_title }}</p6>
                                                        </div>

                                                        <div class="ms-5 bg-light p-3 rounded">
                                                            <p class="mb-0">{!! $item->content !!}</p>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                @endforeach

                                @php
                                    $sectionItems = $agendaItems->whereNull('meeting_agenda_lecture_id');
                                @endphp

                                @if ($sectionItems->count() > 0)
                                    <ul class="list-unstyled ms-4">
                                        @foreach ($sectionItems as $itemIndex => $item)
                                            <li class="mb-3">
                                                <h6 class="mb-2">{{ $section->section_title }}{{ $itemIndex + 1 }} {{ $item->item_title }}</h6>
                                                <div class="ms-4 bg-light p-3 rounded">
                                                    <p class="mb-0">{!! $item->content !!}</p>
                                                </div>
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif

                                @if ($lectures->count() == 0 && $agendaItems->count() == 0)
                                    <p class="ms-4 text-muted fst-italic">ไม่มีรายการในวาระนี้</p>
                                @endif
                            </div>
                        @endforeach
                        @php
                            // $meeting_formats = App\Models\MeetingAgenda::where('meeting_format_id', $my_meetings->id)->get();
                            $meeting_formats = App\Models\MeetingFormat::where('id', $my_meetings->meeting_format_id)->get();
                        @endphp

                        {{-- @dd($meeting_formats->id); --}}
                        <div class="mb-3">
                            @if ($meeting_formats->count() > 0)
                                <ul class="list-group list-group-flush">
                                    @foreach ($meeting_formats as $meeting_format)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <p class="mb-0"><strong>รูปแบบการประชุม:</strong> {{ $meeting_format->name }}</p>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted">ไม่มีข้อมูล</p>
                            @endif

                        </div>

                        @php
                            // $meeting_formats = App\Models\MeetingAgenda::where('meeting_format_id', $my_meetings->id)->get();
                            $regulations = App\Models\RegulationMeeting::where('id', $my_meetings->regulation_meeting_id)->get();
                        @endphp

                        <div class="mb-3">
                            @if ($regulations->count() > 0)
                                <ul class="list-group list-group-flush">
                                    @foreach ($regulations as $regulation)
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            <p class="mb-0"><strong>ระเบียบ:</strong> <a href="{{ asset($regulation->regulation_pdf) }}" target="_blank"><span class="badge rounded-pill bg-info text-dark">ดูรายละเอียด</span></a></p>
                                        </li>
                                    @endforeach
                                </ul>
                            @else
                                <p class="text-muted">ไม่มีข้อมูล</p>
                            @endif
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('styles')
<link href="https://fonts.googleapis.com/css2?family=Kanit:wght@300;400;500;700&display=swap" rel="stylesheet">
{{-- <style>
    body {
        font-family: 'Kanit', sans-serif;
        line-height: 1.6;
        background-color: #f8f9fa;
    }
    h1 {
        font-size: 22px;
        font-weight: 700;
        color: #007bff;
    }
    h2 {
        font-size: 14px;
        font-weight: 700;
        color: #007bff;
    }
    h3 {
        font-size: 16px;
        font-weight: 500;
        color: #343a40;
    }
    h4 {
        font-size: 14px;
        font-weight: 500;
        color: #6c757d;
    }
    p {
        font-size: 14px;
        font-weight: 300;
        color: #343a40;
    }
    strong {
        font-weight: 500;
    }
    .card {
        border: none;
        border-radius: 15px;
    }
    .card-body {
        background-color: #ffffff;
    }
    .border-primary {
        border-color: #007bff !important;
    }
    .text-primary {
        color: #007bff !important;
    }
    .bg-light {
        background-color: #f1f3f5 !important;
    }
</style> --}}
@endpush
