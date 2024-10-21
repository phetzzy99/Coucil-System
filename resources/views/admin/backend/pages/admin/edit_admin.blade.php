@extends('admin.admin_dashboard')
@section('admin')
    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item active" aria-current="page">แก้ไขข้อมูลผู้ใช้</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body p-4">
                <h5 class="mb-4">แก้ไขข้อมูลผู้ใช้</h5>
                <form id="myForm" action="{{ route('update.admin', $user->id) }}" method="post" class="row g-3"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="form-group col-md-3">
                        <label for="prefix_name" class="form-label">คํานําหน้า</label>
                        <select name="prefix_name" class="form-select mb-3" required>
                            <option disabled>เลือกคํานําหน้า</option>
                            @foreach ($prefixnames as $prefixname)
                                <option value="{{ $prefixname->id }}"
                                    {{ $user->prefix_name_id == $prefixname->id ? 'selected' : '' }}>
                                    {{ $prefixname->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="first_name" class="form-label">ชื่อ</label>
                        <input type="text" name="first_name" class="form-control" id="first_name"
                            value="{{ $user->first_name }}" required>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="last_name" class="form-label">นามสกุล</label>
                        <input type="text" name="last_name" class="form-control" id="last_name"
                            value="{{ $user->last_name }}" required>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="position_id" class="form-label">ตำแหน่ง</label>
                        <select name="position_id" class="form-select mb-3" required>
                            <option disabled>เลือกตำแหน่ง</option>
                            @foreach ($positions as $position)
                                <option value="{{ $position->id }}"
                                    {{ $user->position_id == $position->id ? 'selected' : '' }}>{{ $position->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- <fieldset class="border border-primary rounded p-2 mt-3">
                        <legend class="float-none w-auto">คณะกรรมการที่สภาฯ แต่งตั้ง</legend>
                        <div class="mb-3">
                            <div class="column-count: 1;">
                                @foreach ($committeecategories as $committee)
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="checkbox" name="committees[]"
                                            value="{{ $committee->id }}" id="committee_{{ $committee->id }}"
                                            {{ in_array($committee->id, $user->committees->pluck('id')->toArray()) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="committee_{{ $committee->id }}">
                                            {{ $committee->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('committees')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div> --}}
                    </fieldset>

                    <fieldset class="border border-primary rounded p-2 mt-3">
                        <legend class="float-none w-auto">ประเภทการประชุมและคณะกรรมการ</legend>
                        @foreach ($meeting_types as $type)
                            <div class="mb-3">
                                <h6>{{ $type->name }}</h6>
                                <div class="ms-3">
                                    @foreach ($committeecategories as $committee)
                                        @php
                                            $pivot = $user->meetingTypes->where('id', $type->id)->first();
                                            $committeeIds = $pivot ? json_decode($pivot->pivot->committee_ids, true) : [];
                                        @endphp
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox"
                                                   name="meeting_committees[{{ $type->id }}][]"
                                                   value="{{ $committee->id }}"
                                                   id="committee_{{ $type->id }}_{{ $committee->id }}"
                                                   {{ in_array($committee->id, $committeeIds) ? 'checked' : '' }}>
                                            <label class="form-check-label" for="committee_{{ $type->id }}_{{ $committee->id }}">
                                                {{ $committee->name }}
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </fieldset>

                    {{-- <fieldset class="border border-primary rounded p-2 mt-3">
                        <legend class="float-none w-auto">ประเภทการประชุม</legend>
                        <div class="mb-3">
                            <div class="column-count: 1;">
                                @foreach ($meeting_types as $type)
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="checkbox" name="meeting_types[]"
                                            value="{{ $type->id }}" id="meeting_type_{{ $type->id }}"
                                            {{ in_array($type->id, $user->meetingTypes->pluck('id')->toArray()) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="meeting_type_{{ $type->id }}">
                                            {{ $type->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('meeting_types')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </fieldset> --}}

                    <fieldset class="border border-primary rounded p-2 mt-3">
                        <legend class="float-none w-auto">Credentials</legend>
                        <div class="form-group col-md-6">
                            <label for="email" class="form-label">Email/Username</label>
                            <input type="email" name="email" class="form-control" id="email"
                                value="{{ $user->email }}" required>
                            <span class="text-danger" id="emailError"></span>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="password" class="form-label">Password (ปล่อยว่างถ้าไม่ต้องการเปลี่ยน)</label>
                            <input type="password" name="password" class="form-control" id="password">
                        </div>
                    </fieldset>

                    <fieldset class="border border-primary rounded p-2 mt-3">
                        <legend class="float-none w-auto">Role</legend>
                        <div class="form-group col-md-6">
                            <label for="roles" class="form-label">Role Name</label>
                            <select name="roles" class="form-select mb-3" required>
                                <option disabled>Open this select menu</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}"
                                        {{ $user->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>
                    </fieldset>

                    <fieldset class="border border-primary rounded p-2 mt-3">
                        <legend class="float-none w-auto">รูปแบบการประชุม</legend>
                        <div class="mb-3">
                            <div class="d-flex flex-wrap">
                                @foreach ($meeting_formats as $format)
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="radio" name="meeting_format_id"
                                            value="{{ $format->id }}" id="meeting_format_{{ $format->id }}"
                                            {{ $user->meeting_format_id == $format->id ? 'checked' : '' }}>
                                        <label class="form-check-label" for="meeting_format_{{ $format->id }}">
                                            {{ $format->name }}
                                        </label>
                                    </div>
                                @endforeach
                            </div>
                            @error('meeting_format_id')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </fieldset>

                    {{-- <fieldset class="border border-primary rounded p-2 mt-3">
                    <legend class="float-none w-auto">รูปแบบการประชุม</legend>
                    <div class="mb-3">
                        <div class="d-flex flex-wrap">
                            @foreach ($meeting_formats as $format)
                                <div class="form-check me-3">
                                    <input class="form-check-input" type="checkbox" name="meeting_formats[]" value="{{ $format->id }}" id="meeting_format_{{ $format->id }}"
                                        {{ in_array($format->id, $user->meetingFormats->pluck('id')->toArray()) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="meeting_format_{{ $format->id }}">
                                        {{ $format->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('meeting_formats')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </fieldset> --}}

                    <div class="col-md-12">
                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-primary px-4">บันทึกการเปลี่ยนแปลง</button>
                            <a href="{{ route('all.admin') }}" class="btn btn-secondary px-4">กลับ</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
