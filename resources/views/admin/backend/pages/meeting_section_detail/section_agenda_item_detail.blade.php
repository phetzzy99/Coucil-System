@extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    @php
        use Carbon\Carbon;

        // ตั้งค่า locale เป็นไทย
        Carbon::setLocale('th');

        // แปลงวันที่เป็น Carbon instance
        $meeting_date = Carbon::parse($meetingAgenda->meeting_date);

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

    <style>
        .document-style {
            background-color: white;
            padding: 40px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            font-family: 'Times New Roman', Times, serif;
            font-size: 16px;
            line-height: 1.6;
        }

        .document-title {
            font-size: 24px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }

        .section-title {
            font-size: 20px;
            font-weight: bold;
            margin-top: 30px;
            margin-bottom: 15px;
        }

        .item-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .item-list li {
            margin-bottom: 10px;
        }
    </style>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-body document-style">
                            @if (isset($meetingAgenda))
                                <div class="agenda-info text-center">
                                    <p><strong> {{ $meetingAgenda->meeting_agenda_title }}</strong></p>
                                    <p><strong>ครั้งที่:
                                            {{ $meetingAgenda->meeting_agenda_number }}/{{ $meetingAgenda->meeting_agenda_year }}</strong>
                                    </p>
                                    <p><strong>{{ $thai_date }} เวลา {{ $meetingAgenda->meeting_agenda_time }}น.</strong>
                                    </p>
                                    <p><strong>{{ $meetingAgenda->meeting_location }}</strong></p>
                                    {{-- <p><strong>วันที่:</strong> {{ $meetingAgenda->meeting_agenda_date>format('d/m/Y') }}</p> --}}
                                    {{-- @if ($meetingAgenda->description)
                                    <p><strong>รายละเอียด:</strong> {{ $meetingAgenda->description }}</p>
                                @endif --}}
                                </div>
                            @endif

                            <hr>

                            <div class="section-title">{{ $meetingAgendaSection->section_title }}</div>

                            @if ($meetingAgendaSection->description)
                                <div class="section-content ck-content mb-4">{!! $meetingAgendaSection->description !!}</div>
                            @endif

                            @if ($meetingAgendaLectures && $meetingAgendaLectures->count() > 0)
                                @foreach ($meetingAgendaLectures as $lecture)
                                    <div class="lecture-title"><strong>{{ $lecture->lecture_title }}</strong></div>
                                    @if ($lecture->content)
                                        <div style="margin-left: 2rem;" class="lecture-content ck-content">{!! $lecture->content !!}</div>
                                    @endif

                                    @php
                                        $lectureItems = $meetingAgendaItems->where(
                                            'meeting_agenda_lecture_id',
                                            $lecture->id,
                                        );
                                    @endphp

                                    @if ($lectureItems->count() > 0)
                                        <ul class="item-list">
                                            @foreach ($lectureItems as $item)
                                                <li>
                                                    @if ($item->item_title)
                                                        <div class="item-title">{{ $item->item_title }}</div>
                                                    @endif
                                                    @if ($item->content)
                                                        <div style="margin-left: 2rem;" class="item-content ck-content">{!! $item->content !!}</div>
                                                    @endif
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                @endforeach
                            @endif

                            @php
                                $itemsWithoutLecture = $meetingAgendaItems->whereNull('meeting_agenda_lecture_id');
                            @endphp

                            @if ($itemsWithoutLecture->count() > 0)
                                <ul class="item-list">
                                    @foreach ($itemsWithoutLecture as $item)
                                        <li>
                                            @if ($item->item_title)
                                                <div class="item-title">{{ $item->item_title }}</div>
                                            @endif
                                            @if ($item->content)
                                                <div style="margin-left: 2rem;" class="item-content ck-content">{!! $item->content !!}</div>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            @if (!$meetingAgendaSection->description && $meetingAgendaLectures->count() == 0 && $meetingAgendaItems->count() == 0)
                                <p>-ไม่พบรายการ-</p>
                            @endif

                            <br>

                            <div style="width: 100%;" class="p-3 border border-danger rounded mb-4">
                                <p class="mb-0 text-center text-secondary fst-italic">* ท่านสามารถดูรายละเอียดเอกสารที่เกี่ยวกับการประชุม</p>

                                @php
                                    $my_meetings = App\Models\MeetingAgenda::where('status', 1)->get();
                                @endphp

                                @if ($my_meetings->count() > 0)
                                    @php
                                        $meeting_formats = App\Models\MeetingFormat::where('id', $my_meetings->first()->meeting_format_id)->get();
                                    @endphp

                                    @if ($meeting_formats->count() > 0)
                                        <ul class="list-group list-group-flush">
                                            @foreach ($meeting_formats as $meeting_format)
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <p class="mb-0"><strong>รูปแบบการประชุม:</strong> {{ $meeting_format->name }}</p>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-muted">ไม่มีข้อมูล</p>
                                    @endif

                                    @php
                                        $regulations = App\Models\RegulationMeeting::where('id', $my_meetings->first()->regulation_meeting_id)->get();
                                    @endphp

                                    @if ($regulations->count() > 0)
                                        <ul class="list-group list-group-flush">
                                            @foreach ($regulations as $regulation)
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <p class="mb-0"><strong>*ระเบียบ:</strong> {{ $regulation->regulation_title }}<a href="{{ asset($regulation->regulation_pdf) }}" target="_blank"><span class="badge rounded-pill bg-info text-dark">ดูรายละเอียด</span></a></p>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-muted">ไม่มีข้อมูล</p>
                                    @endif

                                    @php
                                        $ruleofmeeting = App\Models\RuleofMeeting::where('id', $my_meetings->first()->rule_of_meeting_id)->get();
                                    @endphp

                                    @if ($ruleofmeeting->count() > 0)
                                        <ul class="list-group list-group-flush">
                                            @foreach ($ruleofmeeting as $ruleofmeet)
                                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                                    <p class="mb-0"><strong>*ข้อบังคับ:</strong> {{ $ruleofmeet->title }}<a href="{{ asset($ruleofmeet->pdf) }}" target="_blank"><span class="badge rounded-pill bg-info text-dark">ดูรายละเอียด</span></a></p>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <p class="text-muted">ไม่มีข้อมูล</p>
                                    @endif

                                @endif
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
