<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <a href=""><img src="{{ asset('backend/assets/images/logo-system-logo.png') }}" class="logo-icon"
                    alt="logo icon" style="width:239px"></a>
        </div>
        {{-- <div>
            <h4 class="logo-text">Admin</h4>
        </div> --}}
        <div class="toggle-icon ms-auto">
            {{-- <i class='bx bx-arrow-back'></i> --}}
        </div>
    </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">

        <li>
            <a href="{{ route('admin.dashboard') }}">
                <div class="parent-icon"><i class='bx bx-home-alt'></i>
                </div>
                <div class="menu-title">หน้าหลัก</div>
            </a>
        </li>

        @if (Auth::user()->can('category.menu'))
            <li class="menu-label"></li>

            <li
                class="{{ request()->routeIs('all.meeting.type') || request()->routeIs('all.committee.category') ? 'mm-active' : '' }}">
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class='lni lni-network'></i>
                    </div>
                    <div class="menu-title"> ประเภทการประชุม / คณะกรรมการ </div>
                </a>
                <ul>
                    <li class="{{ request()->routeIs('all.meeting.type') ? 'mm-active' : '' }}"> <a
                            href="{{ route('all.meeting.type') }}"><i class='bx bx-radio-circle'></i>
                            จัดการประเภทการประชุม </a>
                    </li>
                    <li class="{{ request()->routeIs('all.committee.category') ? 'mm-active' : '' }}"> <a
                            href="{{ route('all.committee.category') }}"><i class='bx bx-radio-circle'></i>
                            จัดการประเภทคณะกรรมการ </a>
                    </li>
                </ul>
            </li>
            <hr>

            <li
                class="{{ request()->routeIs('rule.category') || request()->routeIs('all.rule.meeting') ? 'mm-active' : '' }}">
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class='bx bx-list-check'></i>
                    </div>
                    <div class="menu-title"> ข้อบังคับ </div>
                </a>
                <ul>
                    <li class="{{ request()->routeIs('rule.category') ? 'mm-active' : '' }}"> <a
                            href="{{ route('rule.category') }}"><i class='bx bx-radio-circle'></i> จัดการประเภทข้อบังคับ
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('all.rule.meeting') ? 'mm-active' : '' }}"> <a
                            href="{{ route('all.rule.meeting') }}"><i class='bx bx-radio-circle'></i> จัดการข้อบังคับ
                        </a>
                    </li>
                </ul>
            </li>
            <hr>

            <li
                class="{{ request()->routeIs('all.regulation.category') || request()->routeIs('all.regulation.meeting') ? 'mm-active' : '' }}">
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class='lni lni-clipboard'></i>
                    </div>
                    <div class="menu-title"> ระเบียบ </div>
                </a>
                <ul>
                    <li class="{{ request()->routeIs('all.regulation.category') ? 'mm-active' : '' }}"> <a
                            href="{{ route('all.regulation.category') }}"><i class='bx bx-radio-circle'></i>
                            จัดการประเภทระเบียบ </a>
                    </li>
                    <li class="{{ request()->routeIs('all.regulation.meeting') ? 'mm-active' : '' }}"> <a
                            href="{{ route('all.regulation.meeting') }}"><i class='bx bx-radio-circle'></i>
                            จัดการระเบียบ </a>
                    </li>
                </ul>
            </li>
            <hr>

            {{-- <li class="">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-line-chart'></i>
                </div>
                <div class="menu-title"> กฎหมาย </div>
            </a>
            <ul>
                <li class=""> <a href=""><i class='bx bx-radio-circle'></i> จัดการประเภทกฎหมาย </a>
                </li>
                <li class=""> <a href=""><i class='bx bx-radio-circle'></i> จัดการกฎหมาย </a>
                </li>
            </ul>
        </li>
        <hr> --}}

            {{-- <li class="">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-line-chart'></i>
                </div>
                <div class="menu-title"> กฎหมายอื่นๆ ที่เกี่ยวข้อง </div>
            </a>
            <ul>
                <li class=""> <a href=""><i class='bx bx-radio-circle'></i> จัดการประเภทกฎหมายอื่นๆ </a>
                </li>
                <li class=""> <a href=""><i class='bx bx-radio-circle'></i> จัดการกฎหมายอื่นๆ </a>
                </li>
            </ul>
        </li>
        <hr> --}}

            <li class="{{ request()->routeIs('all.meeting.format') ? 'mm-active' : '' }}">
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class='lni lni-layers'></i>
                    </div>
                    <div class="menu-title"> รูปแบบการประชุม </div>
                </a>
                <ul>
                    <li class="{{ request()->routeIs('all.meeting.format') ? 'mm-active' : '' }}"> <a
                            href="{{ route('all.meeting.format') }}"><i class='bx bx-radio-circle'></i>
                            จัดการประเภทรูปแบบการประชุม </a>
                    </li>
                </ul>
            </li>

            <hr>
            {{-- <li>
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class='bx bx-cart'></i>
                    </div>
                    <div class="menu-title"> ประชุมหลัก (อาจไม่ใช้)</div>
                </a>
                <ul>
                    <li> <a href="{{ route('all.main.meeting') }}"><i class='bx bx-radio-circle'></i> จัดการประชุมหลัก </a>
                    </li>
                </ul>
            </li>
        <hr> --}}

            <li class="{{ request()->routeIs('all.meeting.report') ? 'mm-active' : '' }}">
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class='bx bx-line-chart'></i>
                    </div>
                    <div class="menu-title"> รายงานการประชุม </div>
                </a>
                <ul>
                    <li class="{{ request()->routeIs('all.meeting.report') ? 'mm-active' : '' }}"> <a
                            href="{{ route('all.meeting.report') }}"><i class='bx bx-radio-circle'></i>
                            จัดการรายงานการประชุม </a>
                    </li>
                </ul>
            </li>
            <hr>

            <li class="{{ request()->routeIs('all.meeting.resolution') ? 'mm-active' : '' }}">
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class='bx bxs-report'></i>
                    </div>
                    <div class="menu-title"> รายงานมติการประชุม </div>
                </a>
                <ul>
                    <li class="{{ request()->routeIs('all.meeting.resolution') ? 'mm-active' : '' }}"> <a
                            href="{{ route('all.meeting.resolution') }}"><i class='bx bx-upload'></i>
                            จัดการรายงานมติการประชุม </a>
                    </li>
                    <li class=""> <a
                            href="{{ route('all.meeting.resolution.types') }}"><i class='bx bx-category'></i>
                            เพิ่ม O ประเภทคณะกรรมการของแต่ละด้าน </a>
                    </li>
                    <li class="{{ request()->routeIs('all.management.categories') || request()->routeIs('add.management.category') ? 'mm-active' : '' }}">
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='lni lni-briefcase'></i>
                            </div>
                            <div class="menu-title">หมวดด้านการบริหาร</div>
                        </a>
                        <ul>
                            <li class="{{ request()->routeIs('all.management.categories') ? 'mm-active' : '' }}"> <a
                                    href="{{ route('all.management.categories') }}"><i class='bx bx-list-ul'></i>
                                    รายการทั้งหมด</a>
                            </li>
                            <li class="{{ request()->routeIs('add.management.category') ? 'mm-active' : '' }}"> <a
                                    href="{{ route('add.management.category') }}"><i class='bx bx-plus-circle'></i>
                                    เพิ่มหมวดใหม่</a>
                            </li>
                        </ul>
                    </li>
                    <li class="{{ request()->routeIs('all.management.keywords') || request()->routeIs('add.management.keyword') ? 'mm-active' : '' }}">
                        <a href="javascript:;" class="has-arrow">
                            <div class="parent-icon"><i class='lni lni-keyword-research'></i>
                            </div>
                            <div class="menu-title">Keyword หมวดด้านการบริหาร</div>
                        </a>
                        <ul>
                            <li class="{{ request()->routeIs('all.management.keywords') ? 'mm-active' : '' }}"> <a
                                    href="{{ route('all.management.keywords') }}"><i class='bx bx-list-ul'></i>
                                    รายการทั้งหมด</a>
                            </li>
                            <li class="{{ request()->routeIs('add.management.keyword') ? 'mm-active' : '' }}"> <a
                                    href="{{ route('add.management.keyword') }}"><i class='bx bx-plus-circle'></i>
                                    เพิ่ม Keyword ใหม่</a>
                            </li>
                        </ul>
                    </li>
                    {{-- <li class="{{ request()->routeIs('search.meeting.resolution') ? 'mm-active' : '' }}"> <a
                        href="{{ route('search.meeting.resolution') }}"><i class='bx bx-search'></i>
                        สืบค้นข้อมูลมติการประชุม </a>
                    </li> --}}
                </ul>
            </li>
            <hr>

            <li
                class="{{ request()->routeIs('all.meeting.agenda') || request()->routeIs('meeting.report.summary.index') || request()->routeIs('add.meeting.agenda') || request()->routeIs('edit.meeting.agenda') ? 'mm-active' : '' }}">
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class='bx bx-comment-dots'></i>
                    </div>
                    <div class="menu-title"> ระเบียบวาระการประชุม </div>
                </a>
                <ul>
                    <li class="{{ request()->routeIs('all.meeting.agenda') ? 'mm-active' : '' }}"> <a
                            href="{{ route('all.meeting.agenda') }}"><i class='bx bx-file'></i>
                            จัดการระเบียบวาระการประชุม </a>
                    </li>

                    <li class="{{ request()->routeIs('meeting.report.summary.index') ? 'mm-active' : '' }}">
                        <a href="{{ route('meeting.report.summary.index') }}">
                            <i class="bx bx-file"></i>
                            <span>สรุปรายงานการประชุม</span>
                        </a>
                    </li>


                    {{-- <li class="{{ request()->routeIs('add.meeting.agenda') ? 'mm-active' : '' }}"> <a href="{{ route('add.meeting.agenda') }}"><i class='bx bx-radio-circle'></i> เพิ่มระเบียบวาระการประชุม </a>
                </li> --}}
                    {{-- <li> <a href=""><i class='bx bx-radio-circle'></i> จัดการประเภทคณะกรรมการ </a>
                </li>
                <li> <a href=""><i class='bx bx-radio-circle'></i> จัดการรายงานการประชุม </a>
                </li> --}}
                </ul>
            </li>
            <hr>

            {{-- <li class="{{ request()->routeIs('my.meetings') ? 'mm-active' : '' }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-line-chart'></i>
                </div>
                <div class="menu-title"> การประชุม </div>
            </a>
            <ul>
                <li> <a href="{{ route('all.meeting') }}"><i class='bx bx-radio-circle'></i> จัดการการประชุม </a>
                </li>
                <li class="{{ request()->routeIs('my.meetings') ? 'mm-active' : '' }}"> <a href="{{ route('my.meetings') }}"><i class='bx bx-radio-circle'></i> แสดงรายงานการประชุม </a>
                </li>
            </ul>
        </li> --}}
        @endif




        {{-- <li class="{{ request()->routeIs('meeting.section.detail') ? 'mm-active' : '' }}">
            <a href="javascript:;" class="has-arrow">
                <i class='bx bx-list-ul'></i>
                <div class="menu-title"> หมวดวาระการประชุม </div>
            </a>
            <ul>
                @php
                    $meetingAgendas = \App\Models\MeetingAgenda::where('status', 1)->get();
                @endphp
                @if ($meetingAgendas && $meetingAgendas->count() > 0)
                    @foreach ($meetingAgendas as $agenda)
                        @php
                            $meetingAgendaSections = \App\Models\MeetingAgendaSection::where('meeting_agenda_id', $agenda->id)->get();
                        @endphp
                        @if ($meetingAgendaSections && $meetingAgendaSections->count() > 0)
                            @foreach ($meetingAgendaSections as $section)
                                <li class="{{ request()->routeIs('meeting.section.detail') && request()->route('id') == $section->id ? 'mm-active' : '' }}"> <a href="{{ route('meeting.section.detail', $section->id) }}"><i class='bx bx-radio-circle'></i>{{ $section->section_title }}</a>
                                </li>
                            @endforeach
                        @endif
                    @endforeach
                @else
                    <li> <a href=""><i class='bx bx-radio-circle'></i>ไม่พบหมวดวาระการประชุม</a>
                    </li>
                @endif
            </ul>
            </a>
        </li> --}}

        <fieldset style="border: 1px solid #007bff; border-radius: 5px;" class="p-2">
            <legend style="font-size: 0.9rem; text-align: center;">
                <i class='bx bx-filter-alt'></i> เลือกประเภทการประชุม
            </legend>
            <div class="meeting-type-dropdown">
                @php
                    $user = Auth::user();

                    // ดึงข้อมูล committee_ids ที่ผู้ใช้มีสิทธิ์
                    $userCommitteeIds = [];
                    foreach ($user->meetingTypes as $type) {
                        $committeeIds = json_decode($type->pivot->committee_ids, true);
                        if (is_array($committeeIds)) {
                            $userCommitteeIds = array_merge($userCommitteeIds, $committeeIds);
                        }
                    }
                    $userCommitteeIds = array_unique($userCommitteeIds);

                    // ดึงประเภทการประชุมที่ผู้ใช้มีสิทธิ์เข้าถึง
                    $userMeetingTypes = $user
                        ->meetingTypes()
                        ->whereHas('users', function ($query) use ($user) {
                            $query->where('users.id', $user->id);
                        })
                        ->whereExists(function ($query) use ($userCommitteeIds) {
                            $query
                                ->from('meeting_agendas')
                                ->whereColumn('meeting_types.id', 'meeting_agendas.meeting_type_id')
                                ->whereIn('meeting_agendas.committee_category_id', $userCommitteeIds);
                        })
                        ->orderBy('name')
                        ->get();

                    $selectedMeetingTypeId = session('selected_meeting_type') ?? ($userMeetingTypes->first()->id ?? null);
                @endphp

                <select class="form-select" id="meetingTypeSelect" onchange="window.location.href=this.value">
                    <option value="{{ route('meeting.type.view') }}">-- เลือกประเภทการประชุม --</option>
                    @if ($userMeetingTypes->count() > 0)
                        @foreach ($userMeetingTypes as $meetingType)
                            @php
                                $committeeIds = json_decode($meetingType->pivot->committee_ids, true) ?? [];
                                $committees = \App\Models\CommitteeCategory::whereIn('id', $committeeIds)
                                    ->pluck('name')
                                    ->implode(', ');
                            @endphp
                            <option value="{{ route('meeting.type.view', ['meeting_type_id' => $meetingType->id]) }}"
                                {{ $selectedMeetingTypeId == $meetingType->id ? 'selected' : '' }}
                                title="คณะกรรมการ: {{ $committees }}">
                                {{ Str::limit($meetingType->name, 10) }}
                            </option>
                        @endforeach
                    @endif
                </select>
            </div>
        </fieldset>

        <style>
            .meeting-type-dropdown {
                padding: 10px 15px;
                background-color: #fff;
                border-bottom: 1px solid #e9ecef;
            }

            .meeting-type-dropdown .form-select {
                width: 100%;
                padding: 8px;
                border: 1px solid #ced4da;
                border-radius: 4px;
                font-size: 14px;
                color: #6c757d;
                background-color: #fff;
                cursor: pointer;
                transition: all 0.3s ease;
            }

            .meeting-type-dropdown .form-select:hover {
                border-color: #0d6efd;
            }

            .meeting-type-dropdown .form-select:focus {
                border-color: #0d6efd;
                box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
                outline: none;
            }

            .meeting-type-dropdown .form-select option {
                padding: 8px;
            }
        </style>

        <hr>

        <li>
            <a href="{{ route('admin.legal.database') }}">
                <div class="parent-icon"><i class='bx bx-data'></i>
                </div>
                <div class="menu-title">ฐานข้อมูลด้านกฎหมาย</div>
                {{-- <span class="badge bg-info rounded-pill">ข้อมูลสำคัญ</span> --}}
            </a>
        </li>

        <!-- เพิ่มเมนูสืบค้นข้อมูลมติการประชุม (สำหรับทุกคน) -->
        {{-- <li class="{{ request()->routeIs('search.meeting.resolution') ? 'mm-active' : '' }}">
            <a href="{{ route('search.meeting.resolution') }}">
                <div class="parent-icon"><i class='bx bx-search'></i>
                </div>
                <div class="menu-title">สืบค้นข้อมูลมติการประชุม</div>
            </a>
        </li> --}}

        <li>
            <a href="{{ route('search.meeting.resolution.types') }}">
                <div class="parent-icon"><i class="bx bx-search"></i></div>
                <div class="menu-title">ค้นหามติที่ประชุม</div>
            </a>
        </li>

        <hr>

        <li class="{{ request()->routeIs('meeting.section.detail') ? 'mm-active' : '' }}">
            <a href="javascript:;" class="has-arrow">
                <i class='lni lni-comments-alt'></i>
                <div class="menu-title"> หมวดวาระการประชุม </div>
            </a>
            <ul>
                @php
                    $selectedMeetingType = $userMeetingTypes->where('id', $selectedMeetingTypeId)->first();

                    if ($selectedMeetingType) {
                        $committeeIds = json_decode($selectedMeetingType->pivot->committee_ids, true) ?? [];

                        // ดึงระเบียบวาระการประชุมตามประเภทที่เลือกและสิทธิ์ของผู้ใช้
                        $meetingAgendas = \App\Models\MeetingAgenda::with([
                            'meeting_type',
                            'sections.meetingAgendaLectures',
                        ])
                            ->where('status', 1)
                            ->where('meeting_type_id', $selectedMeetingType->id)
                            ->whereIn('committee_category_id', array_intersect($committeeIds, $userCommitteeIds))
                            ->orderBy('meeting_agenda_date', 'desc')
                            ->get();
                    } else {
                        $meetingAgendas = collect();
                    }
                @endphp

                @forelse ($meetingAgendas as $agenda)
                    <li class="agenda-group">
                        <a href="javascript:;" class="has-arrow">
                            <i class='bx bx-folder'></i>
                            <span class="agenda-title" title="{{ $agenda->meeting_agenda_title }}">
                                {{ $agenda->meeting_agenda_title }}
                                @php
                                    $committee = \App\Models\CommitteeCategory::find($agenda->committee_category_id);
                                @endphp
                                {{-- @if ($committee)
                                    <small class="text-muted">({{ $committee->name }})</small>
                                @endif --}}
                            </span>
                        </a>
                        <ul>
                            @foreach ($agenda->sections as $section)
                                <li
                                    class="{{ request()->routeIs('meeting.section.detail') && request()->route('id') == $section->id ? 'mm-active' : '' }}">
                                    <a href="{{ route('meeting.section.detail', $section->id) }}"
                                        class="agenda-section">
                                        <i class='bx bx-chevron-down-circle'></i>
                                        <span
                                            title="{{ $section->section_title }}">{{ $section->section_title }}</span>
                                    </a>
                                    <!-- Add sub-menu for lectures -->
                                    <ul>
                                        @foreach ($section->meetingAgendaLectures as $lecture)
                                            <li
                                                class="{{ request()->routeIs('meeting.lecture.detail') && request()->route('id') == $lecture->id ? 'mm-active' : '' }}">
                                                <a href="{{ route('meeting.lecture.detail', $lecture->id) }}"
                                                    class="agenda-lecture">
                                                    <i class='bx bx-right-arrow-alt'></i>
                                                    <span title="{{ $lecture->lecture_title }}">
                                                        {{ \Str::limit($lecture->lecture_title, 30) }}
                                                    </span>
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @endforeach
                        </ul>
                    </li>
                @empty
                    <li>
                        <a href="#">
                            <i class='bx bx-folder-open'></i>
                            <span>ไม่พบวาระการประชุม</span>
                        </a>
                    </li>
                @endforelse
            </ul>
        </li>

        {{-- @if ($meetingAgendas->count() > 0)
                    @foreach ($meetingAgendas as $agenda)
                        @php
                            $meetingAgendaSections = \App\Models\MeetingAgendaSection::where('meeting_agenda_id', $agenda->id)->get();
                        @endphp
                        @if ($meetingAgendaSections->count() > 0)
                            @foreach ($meetingAgendaSections as $section)
                                <li class="{{ request()->routeIs('meeting.section.detail') && request()->route('id') == $section->id ? 'mm-active' : '' }}">
                                    <a href="{{ route('meeting.section.detail', $section->id) }}">
                                        <i class='bx bx-radio-circle'></i>{{ $section->section_title }}
                                    </a>
                                </li>
                            @endforeach
                        @endif
                    @endforeach
                @else
                    <li>
                        <a href="javascript:void(0);">
                            <i class='bx bx-radio-circle'></i>ไม่พบหมวดวาระการประชุมที่คุณมีสิทธิ์เข้าถึง
                        </a>
                    </li>
                @endif
            </ul>
        </li> --}}

        {{-- @if (Auth::user()->can('university.council.menu')) --}}

        <hr>
        @if (Auth::user() && Auth::user()->can('approval.menu'))
            <li class="{{ request()->routeIs('approved.meeting.reports') ? 'mm-active' : '' }}">
                <a href="javascript:;" class="has-arrow">
                    <div class="parent-icon"><i class='bx bx-check-circle'></i></div>
                    <div class="menu-title">รายงานการประชุมวาระสืบเนื่อง</div>
                </a>
                <ul>
                    @if (Auth::check())
                        <li class="{{ request()->routeIs('all.approved.meeting.reports') ? 'mm-active' : '' }}">
                            <a href="{{ route('all.approved.meeting.reports') }}">
                                <div class="parent-icon">
                                    <i class='bx bx-check-circle'></i>
                                </div>
                                <div class="menu-title">รายงานการประชุมวาระสืบเนื่อง</div>
                            </a>
                        </li>
                    @endif
                    {{-- <li class="{{ request()->routeIs('approved.meeting.reports') ? 'mm-active' : '' }}">
                    <a href="{{ route('all.approved.meeting.reports') }}">
                        <i class='bx bx-file'></i>
                        รายงานการประชุมวาระสืบเนื่อง (รายงานที่รับรองแล้ว)
                    </a>
                </li> --}}
                </ul>
            </li>
            <hr>
            {{-- @endif --}}

            {{-- @if (Auth::user() && Auth::user()->can('approval.menu')) --}}
            <li>
                <a href="javascript:;" class="has-arrow">
                    <i class='bx bx-select-multiple'></i>
                    <div class="menu-title"> รับรองรายงานการประชุม </div>
                </a>
                <ul>
                    {{-- @php
                    $meetingAgendas = \App\Models\MeetingAgenda::where('status', 1)->get();
                @endphp
                @if ($meetingAgendas && $meetingAgendas->count() > 0)
                    @foreach ($meetingAgendas as $agenda)
                        @php
                            $meetingAgendaSections = \App\Models\MeetingAgendaSection::where('meeting_agenda_id', $agenda->id)->get();
                        @endphp
                        @if ($meetingAgendaSections && $meetingAgendaSections->count() > 0)
                            @foreach ($meetingAgendaSections as $section)
                                <li> <a href="{{ route('meeting.section.detail', $section->id) }}"><i class='bx bx-radio-circle'></i>{{ $section->section_title }}</a>
                                </li>
                            @endforeach
                        @endif
                    @endforeach
                @else
                    <li> <a href=""><i class='bx bx-radio-circle'></i>ไม่พบหมวดวาระการประชุม</a>
                    </li>
                @endif --}}
                    <li class="{{ request()->routeIs('all.meeting.approval') ? 'mm-active' : '' }}"> <a
                            href="{{ route('all.meeting.approval') }}"><i class='bx bx-pencil'></i>
                            รับรองรายงานการประชุม
                        </a></li>
                </ul>
                </a>
            </li>
            <hr>
        @endif

        @if (Auth::user()->can('category.menu'))
            <li class="menu-label">UI Elements</li>

            {{-- <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-cart'></i>
                </div>
                <div class="menu-title">Manage Category</div>
            </a>
            <ul>
                <li> <a href=""><i class='bx bx-radio-circle'></i>All Category </a>
                </li>
                <li> <a href=""><i class='bx bx-radio-circle'></i>All SubCategory </a>
                </li>

            </ul>
        </li>

        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class='bx bx-bookmark-heart'></i>
                </div>
                <div class="menu-title">Manage Instructor</div>
            </a>
            <ul>
                <li> <a href=""><i class='bx bx-radio-circle'></i>All Instructor</a>
                </li>


            </ul>
        </li> --}}


            {{-- <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class='bx bx-bookmark-heart'></i>
                </div>
                <div class="menu-title">Manage Courses</div>
            </a>
            <ul>
                <li> <a href=""><i class='bx bx-radio-circle'></i>All Courses</a>
                </li>


            </ul>
        </li>

        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class='bx bx-bookmark-heart'></i>
                </div>
                <div class="menu-title">Manage Coupon</div>
            </a>
            <ul>
                <li> <a href=""><i class='bx bx-radio-circle'></i>All Coupon</a>
                </li>


            </ul>
        </li>


        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class='bx bx-bookmark-heart'></i>
                </div>
                <div class="menu-title">Manage Setting</div>
            </a>
            <ul>
                <li> <a href=""><i class='bx bx-radio-circle'></i>Manage SMPT</a>
                </li>
                <li> <a href=""><i class='bx bx-radio-circle'></i>Site Setting </a>
                </li>


            </ul>
        </li>


        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class='bx bx-bookmark-heart'></i>
                </div>
                <div class="menu-title">Manage Orders</div>
            </a>
            <ul>
                <li> <a href=""><i class='bx bx-radio-circle'></i>Pending Orders </a>
                </li>
                <li> <a href=""><i class='bx bx-radio-circle'></i>Confirm Orders </a>
                </li>


            </ul>
        </li>


        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class='bx bx-bookmark-heart'></i>
                </div>
                <div class="menu-title">Manage Report</div>
            </a>
            <ul>
                <li> <a href=""><i class='bx bx-radio-circle'></i>Report View </a>
                </li>



            </ul>
        </li>

        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class='bx bx-bookmark-heart'></i>
                </div>
                <div class="menu-title">Manage Review</div>
            </a>
            <ul>
                <li> <a href=""><i class='bx bx-radio-circle'></i>Pending Review
                    </a>
                </li>
                <li> <a href=""><i class='bx bx-radio-circle'></i>Active Review </a>
                </li>



            </ul>
        </li>

        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class='bx bx-bookmark-heart'></i>
                </div>
                <div class="menu-title">Manage All User </div>
            </a>
            <ul>
                <li> <a href=""><i class='bx bx-radio-circle'></i>All User </a>
                </li>
                <li> <a href=""><i class='bx bx-radio-circle'></i>All Instructor</a>
                </li>



            </ul>
        </li> --}}


            <li
                class="{{ request()->routeIs('all.permission') || request()->routeIs('all.roles') || request()->routeIs('add.roles.permission') || request()->routeIs('all.roles.permission') ? 'mm-active' : '' }}">
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon"><i class='lni lni-protection'></i>
                    </div>
                    <div class="menu-title">Role & Permission </div>
                </a>
                <ul>
                    <li class="{{ request()->routeIs('all.permission') ? 'mm-active' : '' }}"> <a
                            href="{{ route('all.permission') }}"><i class='bx bx-radio-circle'></i>All Permission
                        </a>
                    </li>
                    <li class="{{ request()->routeIs('all.roles') ? 'mm-active' : '' }}"> <a
                            href="{{ route('all.roles') }}"><i class='bx bx-radio-circle'></i>All Roles </a>
                    </li>
                    <li class="{{ request()->routeIs('add.roles.permission') ? 'mm-active' : '' }}"> <a
                            href="{{ route('add.roles.permission') }}"><i class='bx bx-radio-circle'></i>Role In
                            Permission</a>
                    </li>
                    <li class="{{ request()->routeIs('all.roles.permission') ? 'mm-active' : '' }}"> <a
                            href="{{ route('all.roles.permission') }}"><i class='bx bx-radio-circle'></i>All Role In
                            Permission</a>
                    </li>
                </ul>
            </li>

            <li
                class="{{ request()->routeIs('all.admin') || request()->routeIs('all.prefix.name') || request()->routeIs('all.position') ? 'mm-active' : '' }}">
                <a class="has-arrow" href="javascript:;">
                    <div class="parent-icon"><i class="bx bx-user-voice"></i>
                    </div>
                    <div class="menu-title">จัดการสมาชิก</div>
                </a>
                <ul>
                    <li class="{{ request()->routeIs('all.admin') ? 'mm-active' : '' }}"> <a
                            href="{{ route('all.admin') }}"><i class='bx bx-radio-circle'></i>จัดการสมาชิก</a>
                    </li>
                    <li class="{{ request()->routeIs('all.prefix.name') ? 'mm-active' : '' }}"> <a
                            href="{{ route('all.prefix.name') }}"><i
                                class='bx bx-radio-circle'></i>จัดการคำนำหน้า</a>
                    </li>
                    <li class="{{ request()->routeIs('all.position') ? 'mm-active' : '' }}"> <a
                            href="{{ route('all.position') }}"><i class='bx bx-radio-circle'></i>ตำแหน่ง</a>
                    </li>
                </ul>
            </li>

            {{-- <li class="nav-item">
            <a class="nav-link" href="{{ route('settings.edit_approval_deadline') }}">
                <i class="fas fa-cog"></i>
                <span>ตั้งค่า Deadline การรับรอง</span>
            </a>
        </li> --}}
        @endif

        {{-- <li class="menu-label">Charts & Maps</li>
        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class="bx bx-line-chart"></i>
                </div>
                <div class="menu-title">Charts</div>
            </a>
            <ul>
                <li> <a href="charts-apex-chart.html"><i class='bx bx-radio-circle'></i>Apex</a>
                </li>
                <li> <a href="charts-chartjs.html"><i class='bx bx-radio-circle'></i>Chartjs</a>
                </li>
                <li> <a href="charts-highcharts.html"><i class='bx bx-radio-circle'></i>Highcharts</a>
                </li>
            </ul>
        </li>
        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class="bx bx-map-alt"></i>
                </div>
                <div class="menu-title">Maps</div>
            </a>
            <ul>
                <li> <a href="map-google-maps.html"><i class='bx bx-radio-circle'></i>Google Maps</a>
                </li>
                <li> <a href="map-vector-maps.html"><i class='bx bx-radio-circle'></i>Vector Maps</a>
                </li>
            </ul>
        </li>

        <li>
            <a href="https://themeforest.net/user/codervent" target="_blank">
                <div class="parent-icon"><i class="bx bx-support"></i>
                </div>
                <div class="menu-title">Support</div>
            </a>
        </li> --}}
    </ul>
    <!--end navigation-->
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get all menu items
        const menuItems = document.querySelectorAll('.metismenu li');

        // Get stored active menu from localStorage
        const storedActiveMenu = localStorage.getItem('activeMenu');
        const storedActiveParent = localStorage.getItem('activeParentMenu');
        const storedScrollPosition = localStorage.getItem('sidebarScrollPosition');

        // Function to scroll sidebar to specific position
        function scrollSidebarToPosition(position) {
            const simplebarContent = document.querySelector('.simplebar-content-wrapper');
            if (simplebarContent) {
                simplebarContent.scrollTo({
                    top: position,
                    behavior: 'smooth'
                });
            }
        }

        // Set active state and scroll position from localStorage on page load
        if (storedActiveMenu || storedActiveParent) {
            menuItems.forEach(item => {
                const link = item.querySelector('a');
                if (link) {
                    // Check if this is the stored active menu
                    if (storedActiveMenu === link.getAttribute('href')) {
                        item.classList.add('mm-active');
                        // Scroll to the active menu position after a short delay
                        setTimeout(() => {
                            const activeItem = item.getBoundingClientRect();
                            if (storedScrollPosition) {
                                scrollSidebarToPosition(parseInt(storedScrollPosition));
                            } else {
                                scrollSidebarToPosition(activeItem.top - 100);
                            }
                        }, 100);
                    }
                    // Check if this is the stored active parent menu
                    if (link.classList.contains('has-arrow') && storedActiveParent === link
                        .getAttribute('href')) {
                        item.classList.add('mm-active');
                    }
                }
            });
        }

        // Add click event listeners to all menu items
        menuItems.forEach(item => {
            const link = item.querySelector('a');
            if (link) {
                link.addEventListener('click', function(e) {
                    // Store menu state
                    if (this.classList.contains('has-arrow')) {
                        localStorage.setItem('activeParentMenu', this.getAttribute('href'));
                    } else {
                        localStorage.setItem('activeMenu', this.getAttribute('href'));
                        // Store scroll position
                        const simplebarContent = document.querySelector(
                            '.simplebar-content-wrapper');
                        if (simplebarContent) {
                            localStorage.setItem('sidebarScrollPosition', simplebarContent
                                .scrollTop);
                        }
                        // Clear parent menu if it's not a submenu item
                        if (!item.closest('.has-arrow')) {
                            localStorage.removeItem('activeParentMenu');
                        }
                    }
                });
            }
        });

        // Also handle current route active state
        const currentPath = window.location.pathname;
        menuItems.forEach(item => {
            const link = item.querySelector('a');
            if (link && link.getAttribute('href') === currentPath) {
                item.classList.add('mm-active');
                // If it's a submenu item, activate parent
                const parentMenuItem = item.closest('.has-arrow')?.closest('li');
                if (parentMenuItem) {
                    parentMenuItem.classList.add('mm-active');
                }
            }
        });
    });
</script>

<style>
    /* สไตล์สำหรับรายการประชุมล่าสุด */
    .recent-meetings {
        max-height: 300px;
        overflow-y: auto;
    }

    .meeting-link {
        padding: 8px 15px;
        transition: all 0.3s ease;
    }

    .meeting-link:hover {
        background: rgba(255, 255, 255, 0.05);
    }

    .meeting-title {
        font-size: 0.9rem;
        display: block;
        color: #fff;
    }

    .meeting-info small {
        font-size: 0.75rem;
        opacity: 0.7;
    }

    .view-all {
        border-top: 1px solid rgba(255, 255, 255, 0.1);
        margin-top: 5px;
        padding-top: 5px;
    }

    /* Scrollbar styling */
    .recent-meetings::-webkit-scrollbar {
        width: 4px;
    }

    .recent-meetings::-webkit-scrollbar-track {
        background: rgba(255, 255, 255, 0.1);
    }

    .recent-meetings::-webkit-scrollbar-thumb {
        background: rgba(255, 255, 255, 0.3);
        border-radius: 2px;
    }

    /* Active state */
    .recent-meetings .mm-active>a {
        background: rgba(255, 255, 255, 0.1);
        border-left: 3px solid #fff;
    }

    .mm-active {
        scroll-margin-top: 70px;
    }
</style>

<script>
    // เพิ่ม tooltip สำหรับชื่อที่ยาวเกินไป
    $('.meeting-title').each(function() {
        if (this.scrollWidth > this.offsetWidth) {
            $(this).attr('title', $(this).text());
        }
    });

    Smooth scroll to active item
    if ($('.recent-meetings .mm-active').length) {
        $('.recent-meetings').animate({
            scrollTop: $('.recent-meetings .mm-active').offset().top -
                $('.recent-meetings').offset().top +
                $('.recent-meetings').scrollTop()
        });
    }
</script>
