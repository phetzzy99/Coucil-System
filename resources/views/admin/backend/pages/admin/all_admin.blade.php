@extends('admin.admin_dashboard')
@section('admin')

    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">All Users</li>
                    </ol>
                </nav>
            </div>
            <div class="ms-auto">
                <div class="btn-group">
                    <a href="{{ route('add.admin') }}" class="btn btn-primary  ">Add User </a>
                </div>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    @if ($alladmin->isEmpty())
                        <h4 class="text-center">ไม่พบข้อมูลผู้ดูแลระบบ</h4>
                    @else
                        <table id="example" class="table table-striped table-bordered" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Committee</th>
                                    <th>Meeting Type</th>
                                    <th>Role</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                                @php
                                    $committees = App\Models\CommitteeCategory::all();
                                    $meeting_types = App\Models\MeetingType::all();
                                    $prefixname = App\Models\PrefixName::all();
                                @endphp
                            <tbody>
                                @foreach ($alladmin as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>
                                        @foreach ($prefixname as $prefix)
                                            @if ($prefix->id == $item->prefix_name_id)
                                                {{ $prefix->title }}
                                            @endif
                                        @endforeach
                                        {{ $item->first_name }} {{ $item->last_name }}
                                    </td>
                                    <td>{{ $item->email }}</td>
                                    <td>
                                        @foreach ($item->meetingTypes as $meetingType)
                                            <strong>{{ $meetingType->name }}:</strong><br>
                                            @php
                                                $committeeIds =
                                                    json_decode($meetingType->pivot->committee_ids, true) ?? [];
                                            @endphp
                                            @foreach ($committeeIds as $committeeId)
                                                @php
                                                    $committee = $committees->find($committeeId);
                                                @endphp
                                                @if ($committee)
                                                    <span class="badge bg-info">{{ $committee->name }}</span><br>
                                                @endif
                                            @endforeach
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($item->meetingTypes as $meetingtype)
                                            <span class="badge bg-success d-block mb-1">{{ $meetingtype->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        @foreach ($item->roles as $role)
                                            <span class="badge bg-primary">{{ $role->name }}</span>
                                        @endforeach
                                    </td>
                                    <td>
                                        @if ($item->UserOnline())
                                            <span class="badge bg-success">Online</span>
                                        @else
                                            <span
                                                class="badge bg-danger">{{ Carbon\Carbon::parse($item->last_seen)->diffForHumans() }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('edit.admin', $item->id) }}" class="btn btn-info px-2"><i class="bx bx-edit"></i></a>
                                        <a href="{{ route('delete.admin', $item->id) }}" class="btn btn-danger px-2" id="delete"><i class="bx bx-trash"></i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>
            </div>
        </div>
    </div>

@endsection
