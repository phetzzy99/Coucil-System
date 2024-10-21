<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <a href=""><img src="{{ asset('backend/assets/images/logo-system-logo.png') }}" class="logo-icon" alt="logo icon" style="width:239px"></a>
        </div>
        {{-- <div>
            <h4 class="logo-text">Admin</h4>
        </div> --}}
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i>
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

        <li class="{{ (request()->routeIs('rule.category') || request()->routeIs('all.rule.meeting')) ? 'mm-active' : '' }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-cart'></i>
                </div>
                <div class="menu-title"> ข้อบังคับ </div>
            </a>
            <ul>
                <li class="{{ request()->routeIs('rule.category') ? 'mm-active' : '' }}"> <a href="{{ route('rule.category') }}"><i class='bx bx-radio-circle'></i> จัดการประเภทข้อบังคับ </a>
                </li>
                <li class="{{ request()->routeIs('all.rule.meeting') ? 'mm-active' : '' }}"> <a href="{{ route('all.rule.meeting') }}"><i class='bx bx-radio-circle'></i> จัดการข้อบังคับ </a>
                </li>
            </ul>
        </li>
        <hr>

        <li class="{{ (request()->routeIs('all.regulation.category') || request()->routeIs('all.regulation.meeting')) ? 'mm-active' : '' }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-line-chart'></i>
                </div>
                <div class="menu-title"> ระเบียบ </div>
            </a>
            <ul>
                <li class="{{ request()->routeIs('all.regulation.category') ? 'mm-active' : '' }}"> <a href="{{ route('all.regulation.category') }}"><i class='bx bx-radio-circle'></i> จัดการประเภทระเบียบ </a>
                </li>
                <li class="{{ request()->routeIs('all.regulation.meeting') ? 'mm-active' : '' }}"> <a href="{{ route('all.regulation.meeting') }}"><i class='bx bx-radio-circle'></i> จัดการระเบียบ </a>
                </li>
            </ul>
        </li>
        <hr>

        <li class="">
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
        <hr>

        <li class="">
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
        <hr>

        <li class="{{ request()->routeIs('all.meeting.format') ? 'mm-active' : '' }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-line-chart'></i>
                </div>
                <div class="menu-title"> รูปแบบการประชุม </div>
            </a>
            <ul>
                <li class="{{ request()->routeIs('all.meeting.format') ? 'mm-active' : '' }}"> <a href="{{ route('all.meeting.format') }}"><i class='bx bx-radio-circle'></i> จัดการประเภทรูปแบบการประชุม </a>
                </li>
            </ul>
        </li>

        <hr>
            <li>
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
        <hr>

        <li class="{{ (request()->routeIs('all.meeting.type') || request()->routeIs('all.committee.category')) ? 'mm-active' : '' }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-line-chart'></i>
                </div>
                <div class="menu-title"> ประเภทการประชุม / คณะกรรมการ (ยุบเป็น Main Meeting)</div>
            </a>
            <ul>
                <li class="{{ request()->routeIs('all.meeting.type') ? 'mm-active' : '' }}"> <a href="{{ route('all.meeting.type') }}"><i class='bx bx-radio-circle'></i> จัดการประเภทการประชุม </a>
                </li>
                <li class="{{ request()->routeIs('all.committee.category') ? 'mm-active' : '' }}"> <a href="{{ route('all.committee.category') }}"><i class='bx bx-radio-circle'></i> จัดการประเภทคณะกรรมการ </a>
                </li>
            </ul>
        </li>
        <hr>

        <li class="{{ request()->routeIs('all.meeting.report') ? 'mm-active' : '' }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-line-chart'></i>
                </div>
                <div class="menu-title"> รายงานการประชุม </div>
            </a>
            <ul>
                <li class="{{ request()->routeIs('all.meeting.report') ? 'mm-active' : '' }}"> <a href="{{ route('all.meeting.report') }}"><i class='bx bx-radio-circle'></i> จัดการรายงานการประชุม </a>
                </li>
            </ul>
        </li>
        <hr>

        <li class="{{ (request()->routeIs('all.meeting.agenda') || request()->routeIs('add.meeting.agenda') || request()->routeIs('edit.meeting.agenda')) ? 'mm-active' : '' }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-line-chart'></i>
                </div>
                <div class="menu-title"> ระเบียบวาระการประชุม </div>
            </a>
            <ul>
                <li class="{{ request()->routeIs('all.meeting.agenda') ? 'mm-active' : '' }}"> <a href="{{ route('all.meeting.agenda') }}"><i class='bx bx-radio-circle'></i> จัดการระเบียบวาระการประชุม </a>
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

        <li class="{{ request()->routeIs('my.meetings') ? 'mm-active' : '' }}">
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class='bx bx-line-chart'></i>
                </div>
                <div class="menu-title"> การประชุม </div>
            </a>
            <ul>
                {{-- <li> <a href="{{ route('all.meeting') }}"><i class='bx bx-radio-circle'></i> จัดการการประชุม </a>
                </li> --}}
                <li class="{{ request()->routeIs('my.meetings') ? 'mm-active' : '' }}"> <a href="{{ route('my.meetings') }}"><i class='bx bx-radio-circle'></i> แสดงรายงานการประชุม </a>
                </li>
            </ul>
        </li>

        @endif

        <hr>

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

        <li class="{{ request()->routeIs('meeting.section.detail') ? 'mm-active' : '' }}">
            <a href="javascript:;" class="has-arrow">
                <i class='bx bx-list-ul'></i>
                <div class="menu-title"> หมวดวาระการประชุม </div>
            </a>
            <ul>
                @php
                    $user = Auth::user();
                    $userMeetingTypes = $user->meetingTypes;
                    $userCommitteeIds = [];
                    foreach ($userMeetingTypes as $meetingType) {
                        $committeeIds = json_decode($meetingType->pivot->committee_ids, true);
                        $userCommitteeIds = array_merge($userCommitteeIds, $committeeIds ?? []);
                    }
                    $userCommitteeIds = array_unique($userCommitteeIds);

                    $meetingAgendas = \App\Models\MeetingAgenda::where('status', 1)
                        ->whereIn('meeting_type_id', $userMeetingTypes->pluck('id'))
                        ->whereIn('committee_category_id', $userCommitteeIds)
                        ->get();
                @endphp
                @if ($meetingAgendas->count() > 0)
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
        </li>

        <hr>

        <li>
            <a href="javascript:;" class="has-arrow">
                <i class='bx bx-list-ul'></i>
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
                <li> <a href="{{ route('all.meeting.approval') }}"><i class='bx bx-radio-circle'></i> รับรองรายงานการประชุม </a></li>
            </ul>
            </a>
        </li>
        <hr>

        @if(Auth::user()->can('category.menu'))

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


        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class='bx bx-bookmark-heart'></i>
                </div>
                <div class="menu-title">Role & Permission </div>
            </a>
            <ul>
                <li> <a href="{{ route('all.permission') }}"><i class='bx bx-radio-circle'></i>All Permission </a>
                </li>
                <li> <a href="{{ route('all.roles') }}"><i class='bx bx-radio-circle'></i>All Roles </a>
                </li>
                <li> <a href="{{ route('add.roles.permission') }}"><i class='bx bx-radio-circle'></i>Role In Permission</a>
                </li>
                <li> <a href="{{ route('all.roles.permission') }}"><i class='bx bx-radio-circle'></i>All Role In Permission</a>
                </li>
            </ul>
        </li>

        <li>
            <a class="has-arrow" href="javascript:;">
                <div class="parent-icon"><i class="bx bx-line-chart"></i>
                </div>
                <div class="menu-title">Manage User</div>
            </a>
            <ul>
                <li> <a href="{{ route('all.admin') }}"><i class='bx bx-radio-circle'></i>จัดการสมาชิก</a>
                </li>
                <li> <a href="{{ route('all.prefix.name') }}"><i class='bx bx-radio-circle'></i>จัดการคำนำหน้า</a>
                </li>
                <li> <a href="{{ route('all.position') }}"><i class='bx bx-radio-circle'></i>ตำแหน่ง</a>
                </li>
            </ul>
        </li>

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
        </li>
    </ul> --}}
    <!--end navigation-->
</div>
