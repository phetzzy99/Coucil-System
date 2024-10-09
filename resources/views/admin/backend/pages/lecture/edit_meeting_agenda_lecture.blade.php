@extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>

    <div class="page-content">
        <!-- ... (ส่วน breadcrumb คงเดิม) ... -->

        <div class="card">
            <div class="card-body p-4">
                <h5 class="mb-4">Edit agenda Lecture</h5>
                <form id="myForm" action="{{ route('update.meeting.agenda.lecture') }}" method="post" class="row g-3">
                    @csrf

                    <input type="hidden" name="id" value="{{ $meeting_agenda_lecture->id }}">

                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label">Lecture Title</label>
                        <input type="text" name="lecture_title" class="form-control" id="input1"
                            value="{{ $meeting_agenda_lecture->lecture_title }}">
                    </div>

                    <div class="form-group col-md-10">
                        <label for="editor" class="form-label">Lecture Description</label>
                        <textarea name="content" id="editor">{!! $meeting_agenda_lecture->content !!}</textarea>
                    </div>

                    <div class="col-md-12">
                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                            <a href="{{ route('add.meeting.agenda.lecture', $meeting_agenda_lecture->meeting_agenda_id) }}" class="btn btn-danger px-4">Back</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        CKEDITOR.replace('editor');
    </script>
@endsection
