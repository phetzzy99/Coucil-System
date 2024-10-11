@extends('admin.admin_dashboard')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">แก้ไขหัวข้อวาระการประชุม</h4>
            </div>
            <div class="card-body">
                <form action="{{ route('update.meeting.agenda.section', $meeting_agenda_section->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('POST')
                    <div class="form-group">
                        <label for="section_title">หัวข้อวาระการประชุม</label>
                        <input type="text" class="form-control" id="section_title" name="section_title" value="{{ $meeting_agenda_section->section_title ?? '' }}" required>
                    </div>
                    <div class="form-group">
                        <label for="description">รายละเอียด</label>
                        <textarea class="form-control" id="editor" name="description" rows="3">{!! $meeting_agenda_section->description !!}</textarea>
                    </div>
                    <div class="form-group text-right">
                        <button type="submit" class="btn btn-primary">แก้ไข</button>
                        <a href="{{ route('add.meeting.agenda.lecture', ['id' => $meeting_agenda_section->meeting_agenda_id]) }}" class="btn btn-secondary">กลับไปเพิ่มระเบียบวาระย่อย</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    CKEDITOR.replace('editor');
</script>
@endsection

