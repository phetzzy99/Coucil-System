@extends('admin.admin_dashboard')
@section('admin')

@php
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
    <!-- breadcrumb -->
    <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
        <div class="ps-3">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0 p-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                    <li class="breadcrumb-item"><a href="{{ route('all.meeting.resolution.types') }}">รายงานมติที่ประชุม</a></li>
                    <li class="breadcrumb-item active" aria-current="page">ค้นหามติที่ประชุม</li>
                </ol>
            </nav>
        </div>
    </div>
    <!-- end breadcrumb -->

    <div class="card shadow-sm border-0 mb-4">
        <div class="card-body">
            <h5 class="card-title mb-4"><i class="bx bx-search me-1"></i>ค้นหามติที่ประชุม</h5>

            @if(request()->isMethod('get') && request()->has('_token') && empty(request('keyword')) && empty(request('committee_category_id')) && empty(request('meeting_type_id')) && empty(request('meeting_no')) && empty(request('meeting_year')) && empty(request('meeting_date')))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="bx bx-info-circle me-1"></i> กรุณาระบุเงื่อนไขในการค้นหา
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            @endif

            <form action="{{ route('search.meeting.resolution.types') }}" method="GET">
                @csrf
                <div class="row mb-4">
                    <div class="col-md-12">
                        <div class="form-group mb-3">
                            <label for="keyword" class="form-label fw-bold">คำค้นหา</label>
                            <input type="text" name="keyword" id="keyword" class="form-control" placeholder="ระบุคำค้นหา เช่น ชื่อเรื่อง, รายละเอียดมติที่ประชุม" value="{{ request('keyword') }}">
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="management_category_id" class="form-label fw-bold">หมวดด้านการบริหาร</label>
                            <select name="management_category_id" id="management_category_id" class="form-select">
                                <option value="">-- เลือกหมวดด้านการบริหาร --</option>
                                @foreach ($managementCategories as $category)
                                    <option value="{{ $category->id }}" {{ request('management_category_id') == $category->id ? 'selected' : '' }}>{{ $category->category_code }} - {{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="meeting_type_id" class="form-label fw-bold">ประเภทการประชุม</label>
                            <select name="meeting_type_id" id="meeting_type_id" class="form-select">
                                <option value="">-- เลือกประเภทการประชุม --</option>
                                @foreach ($meetingTypes as $type)
                                    <option value="{{ $type->id }}" {{ request('meeting_type_id') == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="meeting_no" class="form-label fw-bold">ครั้งที่</label>
                            <input type="text" name="meeting_no" id="meeting_no" class="form-control" placeholder="ระบุครั้งที่" value="{{ request('meeting_no') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="meeting_year" class="form-label fw-bold">ปี</label>
                            <input type="text" name="meeting_year" id="meeting_year" class="form-control" placeholder="ระบุปี (พ.ศ.)" value="{{ request('meeting_year') }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group mb-3">
                            <label for="meeting_date" class="form-label fw-bold">วันที่ประชุม</label>
                            <input type="date" name="meeting_date" id="meeting_date" class="form-control" value="{{ request('meeting_date') }}">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary py-2 fs-5">
                                <i class="bx bx-search me-1"></i> ค้นหา
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    @if(isset($results))
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h5 class="card-title mb-4"><i class="bx bx-list-ul me-1"></i>ผลการค้นหามติที่ประชุม</h5>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead style="background: linear-gradient(to right, #4e73df, #36b9cc); color: white;">
                    </thead>
                    <tbody>
                        <tr>
                            <td colspan="3" class="bg-light fw-bold py-3">
                                <i class="bx bx-list-ol me-1"></i><span style="font-size: 1.1rem;">ลำดับของมติสภามหาวิทยาลัย</span>
                            </td>
                        </tr>
                        @if(count($results) > 0)
                            @foreach($results as $key => $item)
                                <tr style="background: linear-gradient(to right, #4e73df, #36b9cc); color: white;">
                                    <td class="text-center py-3" style="font-size: 18px;">ลำดับ</td>
                                    <td class="text-center py-3"><strong style="font-size: 18px;">{{ $item->managementCategory->category_code }} {{ $item->managementCategory->name }}</strong></td>
                                    <td class="text-center py-3"><strong style="font-size: 18px;">{{ $item->managementCategory->category_code }} {{ $item->managementKeyword->keyword_title }}</strong></td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="ps-4 fs-5 py-3">
                                        <i class="bx bx-calendar me-2"></i>{{ $key + 1 }}. การประชุมครั้งที่ {{ $item->meeting_no }}/{{ $item->meeting_year }} - วันที่ประชุม
                                        {{ formatThaiDate($item->meeting_date) }}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="ps-4 fs-5 py-3">
                                        <i class="bx bx-notepad me-2"></i><strong>{{ $item->agenda_title }}</strong>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="ps-4 py-3">
                                        <div class="mb-3 fs-5"><i class="bx bx-chat me-2"></i><strong>มติที่ประชุม</strong></div>
                                        <div class="ps-4 fs-5">{!! $item->resolution_text !!}</div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="ps-4 py-3">
                                        <div class="mb-3 fs-5"><i class="bx bx-file me-2"></i><strong>สถานะ</strong></div>
                                        <div class="ps-4">
                                            @if($item->task_status == 'completed')
                                                <span class="badge bg-success fs-5 px-3 py-2"><i class="bx bx-check-circle me-1"></i>ดำเนินการแล้วเสร็จ</span>
                                            @elseif($item->task_status == 'in_progress')
                                                <span class="badge bg-warning fs-5 px-3 py-2"><i class="bx bx-loader me-1"></i>อยู่ระหว่างดำเนินการ</span>
                                            @else
                                                <span class="badge bg-danger fs-5 px-3 py-2"><i class="bx bx-error-circle me-1"></i>ยังไม่ดำเนินการ</span>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="ps-4 py-3">
                                        <div class="mb-3 fs-5"><i class="bx bx-file me-2"></i><strong>เอกสารแนบ</strong></div>
                                        <div class="ps-4">
                                        @if($item->document)
                                            <a href="{{ asset($item->document) }}" target="_blank" class="btn btn-info btn-sm fs-5 px-3 py-2"><i class="bx bx-file-find me-1"></i>ดูเอกสาร</a>
                                        @else
                                            <span class="badge bg-danger fs-5 px-3 py-2"><i class="bx bx-error-circle me-1"></i>ไม่มีเอกสาร</span>
                                        @endif
                                        </div>
                                    </td>
                                </tr>

                                @if(!$loop->last)
                                    <tr>
                                        <td colspan="3" class="border-0" style="height: 30px;"></td>
                                    </tr>
                                @endif
                            @endforeach
                        @else
                            <tr>
                                <td colspan="3" class="text-center py-5">
                                    <i class="bx bx-search-alt bx-lg d-block mb-3 text-secondary"></i>
                                    <p class="fs-5 text-muted">ไม่พบข้อมูลที่ตรงกับเงื่อนไขการค้นหา</p>
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>
@endsection
