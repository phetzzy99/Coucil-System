@extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    @php
        use Carbon\Carbon;
        Carbon::setLocale('th');
        $meeting_date = Carbon::parse($lecture->meetingAgendaSection->meetingAgenda->meeting_agenda_date);
        
        $thai_days = ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'];
        $thai_months = [
            1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม', 4 => 'เมษายน', 
            5 => 'พฤษภาคม', 6 => 'มิถุนายน', 7 => 'กรกฎาคม', 8 => 'สิงหาคม',
            9 => 'กันยายน', 10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม'
        ];

        $thai_day = $thai_days[$meeting_date->dayOfWeek];
        $thai_month = $thai_months[$meeting_date->month];
        $thai_date = "วัน{$thai_day}ที่ " . $meeting_date->day . ' ' . $thai_month . ' พ.ศ. ' . ($meeting_date->year + 543);
    @endphp

    <style>
        .document-style {
            background-color: white;
            padding: 40px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            font-family: 'TH Sarabun PSK', sans-serif;
            font-size: 16px;
            line-height: 1.6;
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

        .ck-content table {
            border-collapse: collapse;
            margin: 15px 0;
            width: 100%;
        }

        .ck-content table td,
        .ck-content table th {
            border: 1px solid #ddd;
            padding: 8px;
        }
    </style>

    <div class="page-content">
        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card">
                        <div class="card-body document-style">
                            @if($lecture->meetingAgendaSection->meetingAgenda)
                                <div class="agenda-info text-center">
                                    <p><strong>{{ $lecture->meetingAgendaSection->meetingAgenda->meeting_agenda_title }}</strong></p>
                                    <p><strong>ครั้งที่: 
                                        {{ $lecture->meetingAgendaSection->meetingAgenda->meeting_agenda_number }}/
                                        {{ $lecture->meetingAgendaSection->meetingAgenda->meeting_agenda_year }}</strong>
                                    </p>
                                    <p><strong>{{ $thai_date }} เวลา {{ $lecture->meetingAgendaSection->meetingAgenda->meeting_agenda_time }}น.</strong></p>
                                    <p><strong>{{ $lecture->meetingAgendaSection->meetingAgenda->meeting_location }}</strong></p>
                                </div>
                                <hr>
                            @endif

                            <div class="section-title">{{ $lecture->meetingAgendaSection->section_title }}</div>

                            <div class="lecture-title"><strong>{{ $lecture->lecture_title }}</strong></div>
                            @if($lecture->content)
                                <div style="margin-left: 2rem;" class="lecture-content ck-content">{!! $lecture->content !!}</div>
                            @endif

                            @if($lecture->meetingAgendaItems->count() > 0)
                                <ul class="item-list">
                                    @foreach($lecture->meetingAgendaItems as $item)
                                        <li>
                                            @if($item->item_title)
                                                <div class="item-title">{{ $item->item_title }}</div>
                                            @endif
                                            @if($item->content)
                                                <div style="margin-left: 2rem;" class="item-content ck-content">{!! $item->content !!}</div>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
