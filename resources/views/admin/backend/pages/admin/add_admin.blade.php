@extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">Add User</li>
                    </ol>
                </nav>
            </div>

        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body p-4">
                <h5 class="mb-4">Add User</h5>
                <form id="myForm" action="{{ route('store.admin') }}" method="post" class="row g-3"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="form-group col-md-3">
                        <label for="input1" class="form-label"> คํานําหน้า</label>
                        <select name="prefix_name" class="form-select mb-3" required>
                            <option selected="" disabled>เลือกคํานําหน้า</option>
                            @foreach ($prefixnames as $prefixname)
                                <option value="{{ $prefixname->id }}">{{ $prefixname->title }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="input1" class="form-label"> ชื่อ</label>
                        <input type="text" name="first_name" class="form-control" id="input1" required>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="input1" class="form-label"> นามสกุล</label>
                        <input type="text" name="last_name" class="form-control" id="input1" required>
                    </div>

                    <div class="form-group col-md-3">
                        <label for="input1" class="form-label"> ตำแหน่ง</label>
                        <select name="position_id" class="form-select mb-3" required>
                            <option selected="" disabled>เลือกตำแหน่ง</option>
                            @foreach ($positions as $position)
                                <option value="{{ $position->id }}" {{ old('position_id') == $position->id ? 'selected' : '' }}>{{ $position->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">คณะกรรมการที่สภาฯ แต่งตั้ง</label>
                        <div style="column-count: 1;">
                            @foreach($committeecategories as $committee)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="committees[]" value="{{ $committee->id }}" id="committee_{{ $committee->id }}"
                                        {{ (is_array(old('committees')) && in_array($committee->id, old('committees'))) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="committee_{{ $committee->id }}">
                                        {{ $committee->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                        @error('committees')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">ประเภทการประชุม</label>
                            @foreach($meeting_types as $type)
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="meeting_types[]" value="{{ $type->id }}" id="meeting_type_{{ $type->id }}"
                                        {{ (is_array(old('meeting_types')) && in_array($type->id, old('meeting_types'))) ? 'checked' : '' }}>
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

                    {{-- <div class="form-group col-md-6">
                        <label for="input1" class="form-label"> User Name</label>
                        <input type="text" name="username" class="form-control" id="input1" required>
                    </div> --}}

                    {{-- <div class="form-group col-md-3">
                        <label for="input1" class="form-label"> โทร</label>
                        <input type="text" name="phone" class="form-control" id="input1" required>
                    </div> --}}

                    <fieldset class="border border-primary rounded p-2 mt-3">
                        <legend class="float-none w-auto">Credentials</legend>
                        <div class="form-group col-md-6">
                            <label for="input1" class="form-label"> Email/Username</label>
                            <input type="email" name="email" class="form-control" id="input1" required>
                            <span class="text-danger" id="emailError"></span>
                        </div>

                        <div class="form-group col-md-6">
                            <label for="input1" class="form-label"> Password</label>
                            <input type="password" name="password" class="form-control" id="input1" required>
                        </div>
                    </fieldset>

                    <fieldset class="border border-primary rounded p-2 mt-3">
                        <legend class="float-none w-auto">Role</legend>
                        <div class="form-group col-md-6">
                            <label for="input1" class="form-label"> Role Name</label>
                            <select name="roles" class="form-select mb-3" aria-label="Default select example" required>
                                <option selected="" disabled>Open this select menu</option>
                                @foreach ($roles as $role)
                                    <option value="{{ $role->name }}"> {{ $role->name }}</option>
                                @endforeach
                            </select>
                        </div>

                    </fieldset>

                    <fieldset class="border border-primary rounded p-2 mt-3">
                        <legend class="float-none w-auto">รูปแบบการประชุม</legend>
                        <div class="mb-3">
                            <div class="d-flex flex-wrap">
                                @foreach($meeting_formats as $key => $format)
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="radio" name="meeting_format_id" value="{{ $format->id }}" id="meeting_format_{{ $format->id }}"
                                            {{ $key == 0 ? 'checked' : (old('meeting_format_id') == $format->id ? 'checked' : '') }}>
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
                                @foreach($meeting_formats as $format)
                                    <div class="form-check me-3">
                                        <input class="form-check-input" type="checkbox" name="meeting_formats[]" value="{{ $format->id }}" id="meeting_format_{{ $format->id }}"
                                            {{ (is_array(old('meeting_formats')) && in_array($format->id, old('meeting_formats'))) ? 'checked' : '' }}>
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

                    {{-- @section('script')
                        <script>
                            $(document).ready(function() {
                                $('#myForm').submit(function(e) {
                                    e.preventDefault();
                                    var email = $('input[name=email]').val();
                                    $.ajax({
                                        url: "{{ route('check.email') }}",
                                        type: "POST",
                                        data: {
                                            "_token": "{{ csrf_token() }}",
                                            email: email
                                        },
                                        success: function(response) {
                                            if (response == 'unique') {
                                                $('#emailError').text('');
                                                $('#myForm').submit();
                                            } else {
                                                $('#emailError').text('Email already exists');
                                            }
                                        }
                                    });
                                });
                            });
                        </script>
                    @endsection --}}


                    <div class="col-md-12">
                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                            <a href="{{ route('all.admin') }}" class="btn btn-danger px-4">Back</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>




    </div>
@endsection
