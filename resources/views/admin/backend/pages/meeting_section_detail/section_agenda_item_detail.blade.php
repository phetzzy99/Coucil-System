@extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <style>
        .accessibility-controls {
            position: fixed;
            top: 90px;
            right: 20px;
            background: #fff;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
            z-index: 1000;
            cursor: move;
            user-select: none;
        }

        .font-size-btn {
            padding: 5px 10px;
            margin: 0 2px;
            border: 1px solid #ddd;
            border-radius: 3px;
            background: #f8f9fa;
            cursor: pointer;
        }

        .font-size-btn:hover {
            background: #e9ecef;
        }
    </style>

    @php
        use Carbon\Carbon;

        // ตั้งค่า locale เป็นไทย
        Carbon::setLocale('th');

        // แปลงวันที่เป็น Carbon instance
        $meeting_date = Carbon::parse($meetingAgenda->meeting_agenda_date);

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

        /* CSS สำหรับตาราง CKEditor */
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

        .ck-content table th {
            padding-top: 12px;
            padding-bottom: 12px;
            text-align: left;
            background-color: #f2f2f2;
        }

        /* ทำให้ตารางตอบสนองบนอุปกรณ์มือถือ */
        @media screen and (max-width: 600px) {
            .ck-content table {
                overflow-x: auto;
                display: block;
            }
        }
    </style>

    <div class="page-content">

        <!-- Add Accessibility Controls -->
        <div class="accessibility-controls" id="accessibilityControls">
            <div style="margin-bottom: 5px; padding-bottom: 5px; border-bottom: 1px solid #eee; cursor: move;"
                class="drag-handle">
                <i class="fas fa-grip-horizontal"></i>
            </div>
            <button class="font-size-btn" onclick="changeFontSize('decrease')" title="ลดขนาดตัวอักษร">
                <i class="fas fa-minus"></i> ก
            </button>
            <button class="font-size-btn" onclick="changeFontSize('reset')" title="คืนค่าขนาดตัวอักษร">
                <i class="fas fa-sync-alt"></i> ก
            </button>
            <button class="font-size-btn" onclick="changeFontSize('increase')" title="เพิ่มขนาดตัวอักษร">
                <i class="fas fa-plus"></i> ก
            </button>
        </div>

        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card">

                        <div
                            class="text-center alert border-0 border-start border-5 border-primary alert-dismissible fade show py-2">
                            <ul class="nav nav-tabs card-header-tabs justify-content-center">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#committee" data-bs-toggle="tab">
                                        <i class="fas fa-users me-2"></i>หมวด :
                                        {{ $meetingAgenda->committeeCategory->name ?? 'ไม่ระบุ' }}
                                    </a>
                                </li>
                            </ul>
                        </div>


                        <div class="card-body document-style">
                            <div class="d-flex justify-content-end mb-3">
                                <a class="btn btn-sm btn-primary"
                                    href="{{ route('meeting.export.word', ['id' => $meetingAgenda->id]) }}">
                                    <i class="fas fa-file-word me-1 d-none d-sm-inline"></i>
                                    <span class="d-inline d-sm-none"><i class="fas fa-file-word"></i></span>
                                    <span class="d-none d-sm-inline">ส่งออกเป็นเอกสาร Word</span>
                                </a>
                            </div>
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
                                    <div class="lecture-block mb-4">
                                        <!-- หัวข้อ Lecture -->
                                        <div class="lecture-title"><strong>{{ $lecture->lecture_title }}</strong></div>
                                        @if ($lecture->content)
                                            <div style="margin-left: 2rem;" class="lecture-content ck-content">
                                                {!! $lecture->content !!}
                                            </div>
                                        @endif

                                        {{--  ส่วนที่เพิ่มใน section_agenda_item_detail.blade.php หลังส่วนเนื้อหาหลัก --}}
                                        {{-- เพิ่มความเห็นคณะกรรมการ วันที่เพิ่ม 1/12/2024 --}}
                                        {{-- <div class="card mt-4">
                                            <div class="card-header bg-primary text-white">
                                                <h5 class="mb-0">ความเห็นคณะกรรมการกลั่นกรอง</h5>
                                            </div>
                                            <div class="card-body">
                                                <!-- ฟอร์มสำหรับเพิ่มความเห็น -->
                                                <form action="{{ route('committee.feedback.store') }}" method="POST"
                                                    class="committee-feedback-form mb-4">
                                                    @csrf
                                                    <input type="hidden" name="lecture_id" value="{{ $lecture->id }}">

                                                    <div class="form-group">
                                                        <label class="d-block">การพิจารณา:</label>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input feedback-status-radio"
                                                                type="radio" name="feedback_status"
                                                                id="status_approve_{{ $lecture->id }}" value="approve"
                                                                required>
                                                            <label class="form-check-label"
                                                                for="status_approve_{{ $lecture->id }}">
                                                                เห็นชอบ
                                                            </label>
                                                        </div>
                                                        <div class="form-check form-check-inline">
                                                            <input class="form-check-input feedback-status-radio"
                                                                type="radio" name="feedback_status"
                                                                id="status_reject_{{ $lecture->id }}" value="reject">
                                                            <label class="form-check-label"
                                                                for="status_reject_{{ $lecture->id }}">
                                                                ไม่เห็นชอบ
                                                            </label>
                                                        </div>
                                                    </div>

                                                    <div id="feedback_content_section_{{ $lecture->id }}"
                                                        class="form-group mt-3" style="display: none;">
                                                        <label for="feedback_content_{{ $lecture->id }}">ความเห็น:</label>
                                                        <textarea class="form-control" id="feedback_content_{{ $lecture->id }}" name="feedback_content" rows="3"></textarea>
                                                    </div>

                                                    <button type="submit"
                                                        class="btn btn-primary mt-3">บันทึกความเห็น</button>
                                                </form>

                                                <!-- ส่วนแสดงความเห็นที่มีอยู่ -->
                                                <div class="existing-feedback">
                                                    <h6 class="border-bottom pb-2">ความเห็นที่ผ่านมา</h6>
                                                    @if (isset($committeeFeedbacks) && count($committeeFeedbacks) > 0)
                                                        @foreach ($committeeFeedbacks as $feedback)
                                                            <div class="feedback-item card mb-3">
                                                                <div class="card-body">
                                                                    <div
                                                                        class="d-flex justify-content-between align-items-center">
                                                                        <h6 class="card-subtitle mb-2 text-muted">
                                                                            {{ $feedback->user->name }}
                                                                            <small class="text-muted">
                                                                                ({{ Carbon\Carbon::parse($feedback->created_at)->format('d/m/Y H:i') }})
                                                                            </small>
                                                                        </h6>
                                                                        <span
                                                                            class="badge {{ $feedback->vote_type === 'approve' ? 'bg-success' : 'bg-danger' }}">
                                                                            {{ $feedback->vote_type === 'approve' ? 'เห็นชอบ' : 'ไม่เห็นชอบ' }}
                                                                        </span>
                                                                    </div>
                                                                    @if ($feedback->opinion)
                                                                        <p class="card-text mt-2">{{ $feedback->opinion }}
                                                                        </p>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <p class="text-muted">ยังไม่มีความเห็นจากคณะกรรมการ</p>
                                                    @endif
                                                </div>
                                            </div>
                                        </div> --}}
                                        {{-- เพิ่มความเห็นคณะกรรมการ วันที่เพิ่ม 1/12/2024 --}}

                                        <!-- ส่วนความเห็นคณะกรรมการกลั่นกรอง -->
                                        {{-- <div class="committee-review card mt-3 ms-4">
                                            <div class="card-header bg-light">
                                                <h6 class="mb-0">ความเห็นคณะกรรมการกลั่นกรอง</h6>
                                            </div>
                                            <div class="card-body">
                                                <form class="committee-form" data-lecture-id="{{ $lecture->id }}" method="POST">
                                                    @csrf
                                                    <div class="border p-3 rounded mb-3">
                                                        <!-- ความเห็นทั่วไป -->
                                                        <div class="mb-4">
                                                            <label class="form-label fw-bold">ความเห็นคณะกรรมการ:</label>
                                                            <textarea name="committee_opinion" class="form-control" rows="3" placeholder="ระบุความเห็นของคณะกรรมการ">{{ $lecture->committee_opinion }}</textarea>
                                                        </div>

                                                        <!-- การลงความเห็น -->
                                                        <div class="mb-3">
                                                            <label class="form-label fw-bold">ลงความเห็น:</label>
                                                            <div class="opinion-options">
                                                                <!-- เห็นชอบ -->
                                                                <div class="form-check mb-3">
                                                                    <input class="form-check-input" type="radio" name="opinion_type_{{ $lecture->id }}" id="approve_{{ $lecture->id }}" value="approve" {{ $lecture->approve_votes > 0 ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="approve_{{ $lecture->id }}">
                                                                        <span class="badge bg-success me-2">1</span>เห็นชอบ
                                                                    </label>
                                                                    <div class="ms-4 mt-2 approve-section {{ $lecture->approve_votes > 0 ? '' : 'd-none' }}">
                                                                        <textarea name="approve_comment" class="form-control mb-2" rows="2" placeholder="ระบุความเห็น (ถ้ามี)">{{ $lecture->approve_comment }}</textarea>
                                                                        <div class="input-group" style="width: 200px;">
                                                                            <input type="number" name="approve_votes" class="form-control" placeholder="จำนวนเสียง" value="{{ $lecture->approve_votes }}">
                                                                            <span class="input-group-text">เสียง</span>
                                                                        </div>
                                                                    </div>
                                                                </div>

                                                                <!-- ไม่เห็นชอบ -->
                                                                <div class="form-check">
                                                                    <input class="form-check-input" type="radio" name="opinion_type_{{ $lecture->id }}" id="disapprove_{{ $lecture->id }}" value="disapprove" {{ $lecture->disapprove_votes > 0 ? 'checked' : '' }}>
                                                                    <label class="form-check-label" for="disapprove_{{ $lecture->id }}">
                                                                        <span class="badge bg-danger me-2">2</span>ไม่เห็นชอบ
                                                                    </label>
                                                                    <div class="ms-4 mt-2 disapprove-section {{ $lecture->disapprove_votes > 0 ? '' : 'd-none' }}">
                                                                        <textarea name="disapprove_comment" class="form-control mb-2" rows="2" placeholder="ระบุความเห็น (ถ้ามี)">{{ $lecture->disapprove_comment }}</textarea>
                                                                        <div class="input-group" style="width: 200px;">
                                                                            <input type="number" name="disapprove_votes" class="form-control" placeholder="จำนวนเสียง" value="{{ $lecture->disapprove_votes }}">
                                                                            <span class="input-group-text">เสียง</span>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>

                                                        <!-- ปุ่มบันทึก -->
                                                        <div class="text-end mt-4">
                                                            <button type="submit" class="btn btn-primary btn-save-opinion">บันทึกความเห็น</button>
                                                        </div>
                                                    </div>
                                                </form>
                                            </div>
                                        </div> --}}

                                        <!-- แสดง Items ที่เกี่ยวข้อง -->
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
                                                            <div style="margin-left: 2rem;" class="item-content ck-content">
                                                                {!! $item->content !!}
                                                            </div>
                                                        @endif
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                @endforeach
                            @endif

                            {{-- Original Source Code --}}
                            @if ($meetingAgendaLectures && $meetingAgendaLectures->count() > 0)
                                @foreach ($meetingAgendaLectures as $lecture)
                                    <div class="lecture-title"><strong>{{ $lecture->lecture_title }}</strong></div>
                                    @if ($lecture->content)
                                        <div style="margin-left: 2rem;" class="lecture-content ck-content">
                                            {!! $lecture->content !!}</div>
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
                                                        <div style="margin-left: 2rem;" class="item-content ck-content">
                                                            {!! $item->content !!}</div>
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
                                                <div style="margin-left: 2rem;" class="item-content ck-content">
                                                    {!! $item->content !!}</div>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            @if (!$meetingAgendaSection->description && $meetingAgendaLectures->count() == 0 && $meetingAgendaItems->count() == 0)
                                <p>-ไม่พบรายการ-</p>
                            @endif

                            <!-- แสดงรายการเอกสารที่เกี่ยวข้อง -->


                            <!-- สิ้นสุดการแสดงรายการเอกสารที่เกี่ยวข้อง -->

                            <br>

                            <div style="width: 100%;" class="p-3 border border-danger rounded mb-4">
                                <p class="mb-0 text-center text-secondary fst-italic">*
                                    ท่านสามารถดูรายละเอียดเอกสารที่เกี่ยวกับการประชุม</p>

                                @php
                                    $my_meetings = App\Models\MeetingAgenda::where('status', 1)->get();
                                @endphp

                                @if ($my_meetings->count() > 0)
                                    <div class="d-flex flex-column">
                                        @php
                                            $meeting_formats = App\Models\MeetingFormat::where(
                                                'id',
                                                $meetingAgendaSection->meetingAgenda->meeting_format_id
                                            )->get();
                                        @endphp

                                        @if ($meeting_formats->count() > 0)
                                            <ul class="list-group list-group-flush">
                                                @foreach ($meeting_formats as $meeting_format)
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <p class="mb-0"><strong>รูปแบบการประชุม:</strong>
                                                            {{ $meeting_format->name }}</p>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif

                                        @php
                                            $regulations = App\Models\RegulationMeeting::where(
                                                'id',
                                                $my_meetings->first()->regulation_meeting_id,
                                            )->get();
                                        @endphp

                                        @if ($regulations->count() > 0)
                                            <ul class="list-group list-group-flush">
                                                @foreach ($regulations as $regulation)
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <p class="mb-0"><strong>*ระเบียบ:</strong>
                                                            {{ $regulation->regulation_title }}<a href="#"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#regulationModal{{ $regulation->id }}"><span
                                                                    class="badge rounded-pill bg-info text-dark">ดูรายละเอียด</span></a>
                                                        </p>

                                                        <!-- Modal สำหรับแสดงรายละเอียดระเบียบ -->
                                                        <div class="modal fade" id="regulationModal{{ $regulation->id }}"
                                                            tabindex="-1" aria-labelledby="regulationModalLabel"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="regulationModalLabel">
                                                                            ระเบียบ</h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <embed
                                                                            src="{{ asset($regulation->regulation_pdf) }}"
                                                                            width="100%" height="600"
                                                                            type="application/pdf">
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        @endif

                                        @php
                                            $meetingAgendaId = $meetingAgenda->id; // สมมติว่า $meetingAgenda เป็นอ็อบเจ็กต์ของ MeetingAgenda ปัจจุบัน
                                            $ruleofmeetings = App\Models\RuleofMeeting::whereHas(
                                                'meetingAgendas',
                                                function ($query) use ($meetingAgendaId) {
                                                    $query->where('meeting_agenda_id', $meetingAgendaId);
                                                },
                                            )->get();
                                        @endphp

                                        @if ($ruleofmeetings->count() > 0)
                                            <ul class="list-group list-group-flush">
                                                @foreach ($ruleofmeetings as $ruleofmeet)
                                                    <li
                                                        class="list-group-item d-flex justify-content-between align-items-center">
                                                        <p class="mb-0">
                                                            <strong>*ข้อบังคับ:</strong> {{ $ruleofmeet->title }}
                                                            @if ($ruleofmeet->pdf)
                                                                <a href="#" data-bs-toggle="modal"
                                                                    data-bs-target="#ruleofmeet_{{ $ruleofmeet->id }}">
                                                                    <span
                                                                        class="badge rounded-pill bg-info text-dark">ดูรายละเอียด</span>
                                                                </a>
                                                            @endif
                                                        </p>
                                                    </li>

                                                    @if ($ruleofmeet->pdf)
                                                        <!-- Modal สำหรับแสดงรายละเอียดข้อบังคับ -->
                                                        <div class="modal fade" id="ruleofmeet_{{ $ruleofmeet->id }}"
                                                            tabindex="-1"
                                                            aria-labelledby="ruleofmeet_{{ $ruleofmeet->id }}Label"
                                                            aria-hidden="true">
                                                            <div class="modal-dialog modal-lg">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title"
                                                                            id="ruleofmeet_{{ $ruleofmeet->id }}Label">
                                                                            ข้อบังคับ: {{ $ruleofmeet->title }}</h5>
                                                                        <button type="button" class="btn-close"
                                                                            data-bs-dismiss="modal"
                                                                            aria-label="Close"></button>
                                                                    </div>
                                                                    <div class="modal-body">
                                                                        <object data="{{ asset($ruleofmeet->pdf) }}"
                                                                            type="application/pdf" width="100%"
                                                                            height="600px">
                                                                            <p>ไม่สามารถแสดง PDF ได้</p>
                                                                        </object>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        @endif
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ฟังก์ชันสำหรับทำให้ Accessibility Controls เคลื่อนย้ายได้
        document.addEventListener('DOMContentLoaded', function() {
            const controls = document.getElementById('accessibilityControls');
            let isDragging = false;
            let currentX;
            let currentY;
            let initialX;
            let initialY;
            let xOffset = 0;
            let yOffset = 0;

            // บันทึกตำแหน่งใน localStorage
            const savedPosition = localStorage.getItem('accessibilityControlsPosition');
            if (savedPosition) {
                const {
                    x,
                    y
                } = JSON.parse(savedPosition);
                controls.style.transform = `translate(${x}px, ${y}px)`;
                xOffset = x;
                yOffset = y;
            }

            function dragStart(e) {
                if (e.type === "touchstart") {
                    initialX = e.touches[0].clientX - xOffset;
                    initialY = e.touches[0].clientY - yOffset;
                } else {
                    initialX = e.clientX - xOffset;
                    initialY = e.clientY - yOffset;
                }

                if (e.target.closest('.drag-handle')) {
                    isDragging = true;
                }
            }

            function dragEnd(e) {
                initialX = currentX;
                initialY = currentY;
                isDragging = false;

                // บันทึกตำแหน่งเมื่อลากเสร็จ
                localStorage.setItem('accessibilityControlsPosition', JSON.stringify({
                    x: xOffset,
                    y: yOffset
                }));
            }

            function drag(e) {
                if (isDragging) {
                    e.preventDefault();

                    if (e.type === "touchmove") {
                        currentX = e.touches[0].clientX - initialX;
                        currentY = e.touches[0].clientY - initialY;
                    } else {
                        currentX = e.clientX - initialX;
                        currentY = e.clientY - initialY;
                    }

                    xOffset = currentX;
                    yOffset = currentY;

                    controls.style.transform = `translate(${currentX}px, ${currentY}px)`;
                }
            }

            // เพิ่ม Event Listeners
            controls.addEventListener('touchstart', dragStart, false);
            controls.addEventListener('touchend', dragEnd, false);
            controls.addEventListener('touchmove', drag, false);
            controls.addEventListener('mousedown', dragStart, false);
            controls.addEventListener('mouseup', dragEnd, false);
            controls.addEventListener('mousemove', drag, false);
            controls.addEventListener('mouseleave', dragEnd, false);
        });


        // ฟังก์ชันสำหรับเปลี่ยนขนาดตัวอักษร
        function changeFontSize(action) {
            const content = document.querySelector('.document-style');
            const currentSize = parseFloat(window.getComputedStyle(content).fontSize);

            let newSize;
            switch (action) {
                case 'increase':
                    newSize = currentSize * 1.1; // เพิ่ม 10%
                    break;
                case 'decrease':
                    newSize = currentSize * 0.9; // ลด 10%
                    break;
                case 'reset':
                    newSize = 16; // ขนาดเริ่มต้น
                    break;
            }

            // กำหนดขอบเขตขนาดตัวอักษร
            newSize = Math.min(Math.max(newSize, 12), 24); // ขนาดต่ำสุด 12px, สูงสุด 24px
            content.style.fontSize = `${newSize}px`;

            // บันทึกค่าไว้ใน localStorage
            localStorage.setItem('preferredFontSize', newSize);
        }

        // โหลดขนาดตัวอักษรที่ผู้ใช้ตั้งค่าไว้
        document.addEventListener('DOMContentLoaded', function() {
            const savedSize = localStorage.getItem('preferredFontSize');
            if (savedSize) {
                document.querySelector('.document-style').style.fontSize = `${savedSize}px`;
            }
        });
    </script>

    {{-- radio button ส่วนลงความเห็น คณะกรรมการกลั่นกรอง --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // จัดการการส่งฟอร์ม
            document.querySelectorAll('.committee-form').forEach(form => {
                form.addEventListener('submit', async function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);
                    try {
                        const response = await fetch('/committee/vote', {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content
                            }
                        });

                        const data = await response.json();
                        if (data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'สำเร็จ',
                                text: 'บันทึกความเห็นเรียบร้อยแล้ว'
                            });
                            updateVoteSummary(this.dataset.lectureId);
                        }
                    } catch (error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: error.message
                        });
                    }
                });
            });

            // อัพเดทการแสดงผลการลงคะแนน
            function updateVoteSummary(lectureId) {
                fetch(`/committee/votes/${lectureId}`)
                    .then(response => response.json())
                    .then(data => {
                        const summary = document.querySelector(`#voteSummary_${lectureId}`);
                        summary.innerHTML = `
                            <div class="row">
                                <div class="col-6">
                                    <div class="text-center">
                                        <h4>${data.approve_count}</h4>
                                        <p class="text-success">เห็นชอบ</p>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="text-center">
                                        <h4>${data.disapprove_count}</h4>
                                        <p class="text-danger">ไม่เห็นชอบ</p>
                                    </div>
                                </div>
                            </div>
                        `;
                    });
            }
        });
    </script>

    {{-- เพิ่มความเห็นคณะกรรมการ วันที่เพิ่ม 1/12/2024 --}}
    {{-- <script>
        document.addEventListener('DOMContentLoaded', function() {
            const lectureId = '{{ $lecture->id }}';
            const radioButtons = document.querySelectorAll('.feedback-status-radio');
            const feedbackContentSection = document.getElementById(`feedback_content_section_${lectureId}`);
            const feedbackContent = document.getElementById(`feedback_content_${lectureId}`);
            const existingFeedbackDiv = document.querySelector('.existing-feedback');

            // ฟังก์ชันสำหรับสร้าง HTML ของความเห็นใหม่
            function createFeedbackHTML(feedback) {
                const date = new Date(feedback.created_at).toLocaleString('th-TH', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });

                return `
                    <div class="feedback-item card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="card-subtitle mb-2 text-muted">
                                    ${feedback.user ? feedback.user.name : 'ไม่ระบุชื่อ'}
                                    <small class="text-muted">(${date})</small>
                                </h6>
                                <span class="badge ${feedback.vote_type === 'approve' ? 'bg-success' : 'bg-danger'}">
                                    ${feedback.vote_type === 'approve' ? 'เห็นชอบ' : 'ไม่เห็นชอบ'}
                                </span>
                            </div>
                            ${feedback.opinion ? `<p class="card-text mt-2">${feedback.opinion}</p>` : ''}
                        </div>
                    </div>
                `;
            }

            // ฟังก์ชันสำหรับโหลดและแสดงความเห็นทั้งหมด
            function loadAndDisplayFeedbacks() {
                fetch(`/committee/feedback/${lectureId}`)
                    .then(response => response.json())
                    .then(feedbacks => {
                        const feedbacksHTML = feedbacks.length > 0
                            ? feedbacks.map(createFeedbackHTML).join('')
                            : '<p class="text-muted">ยังไม่มีความเห็นจากคณะกรรมการ</p>';

                        const feedbackContainer = existingFeedbackDiv.querySelector('div') || existingFeedbackDiv;
                        feedbackContainer.innerHTML = `
                            <h6 class="border-bottom pb-2">ความเห็นที่ผ่านมา</h6>
                            ${feedbacksHTML}
                        `;
                    })
                    .catch(error => {
                        console.error('Error loading feedbacks:', error);
                    });
            }

            // โหลดความเห็นที่มีอยู่แล้วของผู้ใช้
            fetch(`/committee/feedback/${lectureId}/current`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data) {
                    const radio = document.querySelector(`input[name="feedback_status"][value="${data.vote_type}"]`);
                    if (radio) {
                        radio.checked = true;
                        if (data.vote_type === 'reject') {
                            feedbackContentSection.style.display = 'block';
                            feedbackContent.required = true;
                            feedbackContent.value = data.opinion || '';
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Error loading current user feedback:', error);
            });

            // จัดการการแสดง/ซ่อนช่องความเห็น
            radioButtons.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'reject') {
                        feedbackContentSection.style.display = 'block';
                        feedbackContent.required = true;
                    } else {
                        feedbackContentSection.style.display = 'none';
                        feedbackContent.required = false;
                        feedbackContent.value = '';
                    }
                });
            });

            // จัดการการส่งฟอร์ม
            const feedbackForm = document.querySelector('.committee-feedback-form');
            if (feedbackForm) {
                feedbackForm.addEventListener('submit', function(e) {
                    e.preventDefault();

                    const formData = new FormData(this);

                    fetch(this.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            const message = data.is_update ? 'อัพเดทความเห็นเรียบร้อยแล้ว' : 'บันทึกความเห็นเรียบร้อยแล้ว';
                            alert(message);
                            loadAndDisplayFeedbacks(); // โหลดความเห็นทั้งหมดใหม่
                        } else {
                            alert(data.message || 'เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('เกิดข้อผิดพลาด กรุณาลองใหม่อีกครั้ง');
                    });
                });
            }

            // โหลดความเห็นทั้งหมดเมื่อโหลดหน้า
            loadAndDisplayFeedbacks();
        });
    </script> --}}
    {{-- เพิ่มความเห็นคณะกรรมการ วันที่เพิ่ม 1/12/2024 --}}

@endsection
