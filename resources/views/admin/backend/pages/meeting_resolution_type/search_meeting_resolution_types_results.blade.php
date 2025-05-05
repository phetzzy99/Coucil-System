@extends('admin.admin_dashboard')
@section('admin')

<@php
    function formatThaiDate($dateString) {
        $months = [
            'มกราคม', 'กุมภาพันธ์', 'มีนาคม', 'เมษายน', 'พฤษภาคม', 'มิถุนายน',
            'กรกฎาคม', 'สิงหาคม', 'กันยายน', 'ตุลาคม', 'พฤศจิกายน', 'ธันวาคม'
        ];

        $date = new DateTime($dateString);
        $day = $date->format('d');
        $month = $months[$date->format('n') - 1];
        $year = $date->format('Y') + 543;

        return $day . ' ' . $month . ' ' . $year;
    }
@endphp

    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item"><a href="{{ route('search.meeting.resolution.types') }}">ค้นหามติที่ประชุม</a></li>
                        <li class="breadcrumb-item active" aria-current="page">ผลการค้นหา</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <a href="{{ route('search.meeting.resolution.types') }}" class="btn btn-secondary"><i class="bx bx-arrow-back me-1"></i>กลับไปหน้าค้นหา</a>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card shadow-sm border-0">
            <div class="card-body">
                <h5 class="card-title mb-4"><i class="bx bx-search-alt me-1"></i>ผลการค้นหามติที่ประชุม</h5>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead style="background: linear-gradient(to right, #4e73df, #36b9cc); color: white;">
                            <tr>
                                <th class="text-center" width="8%">ลำดับ</th>
                                <th class="text-center" width="25%">ประเภทคณะกรรมการ</th>
                                <th class="text-center">ชื่อรายงานการประชุม</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td colspan="3" class="bg-light fw-bold">
                                    <i class="bx bx-list-ol me-1"></i>ลำดับของมติสภามหาวิทยาลัย
                                </td>
                            </tr>
                            @if(count($results) > 0)
                                @foreach($results as $key => $item)
                                    <tr style="background: linear-gradient(to right, #4e73df, #36b9cc); color: white;">
                                        <td class="text-center">{{ $key + 1 }}</td>
                                        <td class="text-center"><strong>{{ $item->committeeCategory->name }}</strong></td>
                                        <td class="text-center"><strong>{{ $item->name }}</strong></td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="ps-4">
                                            <i class="bx bx-calendar me-1"></i>การประชุมครั้งที่ {{ $item->meeting_no }}/{{ $item->meeting_year }} - วันที่ประชุม
                                            {{ formatThaiDate($item->meeting_date) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="ps-4">
                                            <i class="bx bx-notepad me-1"></i><strong>{{ $item->agenda_title }}</strong>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="3" class="ps-4">
                                            <div class="mb-2"><i class="bx bx-chat me-1"></i><strong>มติที่ประชุม</strong></div>
                                            <div class="ps-4">{!! $item->resolution_text !!}</div>
                                        </td>
                                    </tr>
                                    {{-- <tr>
                                        <td colspan="3" class="ps-4">
                                            <div class="mb-2"><i class="bx bx-file me-1"></i><strong>เอกสารแนบ</strong></div>
                                            <div class="ps-4">
                                            @if($item->document)
                                                <a href="{{ asset($item->document) }}" target="_blank" class="btn btn-info btn-sm"><i class="bx bx-file-find me-1"></i>ดูเอกสาร</a>
                                            @else
                                                <span class="badge bg-danger"><i class="bx bx-error-circle me-1"></i>ไม่มีเอกสาร</span>
                                            @endif
                                            </div>
                                        </td>
                                    </tr> --}}

                                    @if(!$loop->last)
                                        <tr>
                                            <td colspan="3" class="border-0" style="height: 20px;"></td>
                                        </tr>
                                    @endif
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="3" class="text-center py-4">
                                        <i class="bx bx-search-alt bx-lg d-block mb-2"></i>
                                        ไม่พบข้อมูลที่ตรงกับเงื่อนไขการค้นหา
                                    </td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
