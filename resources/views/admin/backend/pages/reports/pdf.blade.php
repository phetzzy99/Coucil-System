<!DOCTYPE html>
<html>
<head>
    <title>{{ $report->title }}</title>
    <style>
        /* นำเข้าฟอนต์ Kanit จาก Google Fonts */
        @import url('https://fonts.googleapis.com/css2?family=Kanit&display=swap');

        body {
            font-family: 'Kanit', sans-serif;
        }
        h1, h2, h3, h4, h5 {
            margin: 0;
            padding: 5px 0;
        }
        .section {
            margin-bottom: 20px;
        }
        .lecture {
            margin-left: 20px;
        }
        .item {
            margin-left: 40px;
        }
    </style>
</head>
<body>
    <h1>{{ $report->title }}</h1>
    <p>{{ $report->description }}</p>

    @foreach($report->sections as $section)
        <div class="section">
            <h2>{{ $section->section_title }}</h2>
            {!! $section->description !!}

            @foreach($section->meetingAgendaLectures as $lecture)
                <div class="lecture">
                    <h3>{{ $lecture->lecture_title }}</h3>
                    {!! $lecture->content !!}

                    @foreach($lecture->meetingAgendaItems as $item)
                        <div class="item">
                            <h4>{{ $item->item_title }}</h4>
                            {!! $item->content !!}
                        </div>
                    @endforeach
                </div>
            @endforeach
        </div>
    @endforeach
</body>
</html>
