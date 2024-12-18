@extends('admin.admin_dashboard')

@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>

    <div class="page-content">
        <!--breadcrumb-->
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                        <li class="breadcrumb-item"><a href="javascript:;"><i class="bx bx-home-alt"></i></a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">แก้ไขประชุมหลัก</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body p-4">
                <h1 class="mb-4">Edit Main Meeting</h1>
                <form action="{{ route('update.main.meeting', $main_meeting->id) }}" method="POST">
                    @csrf
                    @method('POST')

                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="{{ $main_meeting->title }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ $main_meeting->description }}</textarea>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label class="form-label">Meeting Types</label>
                        <div>
                            @foreach ($meeting_types as $meetingType)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="meeting_types_id[]"
                                        value="{{ $meetingType->id }}" id="meetingType{{ $meetingType->id }}"
                                        {{ $main_meeting->meetingTypes->contains($meetingType->id) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="meetingType{{ $meetingType->id }}">
                                        {{ $meetingType->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <hr>
                    <div class="mb-3">
                        <label class="form-label">Committee Categories</label>
                        <div>
                            @foreach ($committee_categories as $committeeCategory)
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="committee_categories_id[]"
                                        value="{{ $committeeCategory->id }}"
                                        id="committeeCategory{{ $committeeCategory->id }}"
                                        {{ $main_meeting->committeeCategories->contains($committeeCategory->id) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="committeeCategory{{ $committeeCategory->id }}">
                                        {{ $committeeCategory->name }}
                                    </label>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">บันทึกการแก้ไข</button>
                    <a href="{{ route('all.main.meeting') }}" class="btn btn-danger">ยกเลิก</a>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        CKEDITOR.replace('description');
    </script>
@endsection
