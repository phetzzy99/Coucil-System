@extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

<script src="{{ asset('backend/assets/plugins/ckeditor/ckeditor.js') }}"></script>

    <div class="page-content">


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
                        <textarea name="content" id="content" class="form-control">{{ $meeting_agenda_lecture->content }}</textarea>
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
        CKEDITOR.replace('content', {
            language: 'th',
            height: 300,
            removeButtons: 'PasteFromWord'
        });
    </script>
@endsection

//-----------------------------------------------------

{{-- @extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script src="{{ asset('backend/assets/plugins/ckeditor/ckeditor.js') }}"></script>

    <div class="page-content">
        <div class="card">
            <div class="card-body p-4">
                <h5 class="mb-4">แก้ไขวาระย่อย</h5>
                <form id="myForm" action="{{ route('update.meeting.agenda.lecture') }}" method="post" class="row g-3">
                    @csrf
                    <input type="hidden" name="id" value="{{ $meeting_agenda_lecture->id }}">

                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label">หัวข้อวาระย่อย</label>
                        <input type="text" name="lecture_title" class="form-control" id="input1"
                            value="{{ $meeting_agenda_lecture->lecture_title }}">
                    </div>

                    <div class="form-group col-md-12">
                        <label for="content" class="form-label">รายละเอียด</label>
                        <textarea name="content" id="content" class="form-control">{{ $meeting_agenda_lecture->content }}</textarea>
                    </div>

                    <!-- เพิ่มส่วนคณะกรรมการกลั่นกรอง -->
                    <div class="col-md-12 mt-4">
                        <div class="card">
                            <div class="card-header">
                                <h6 class="mb-0">ความเห็นคณะกรรมการกลั่นกรอง</h6>
                            </div>
                            <div class="card-body">
                                <!-- ความเห็นทั่วไป -->
                                <div class="mb-4">
                                    <label class="form-label">ความเห็นคณะกรรมการ :</label>
                                    <textarea name="committee_opinion" id="committee_opinion" class="form-control" rows="3">{{ $meeting_agenda_lecture->committee_opinion }}</textarea>
                                </div>

                                <!-- เห็นชอบ -->
                                <div class="mb-4">
                                    <label class="form-label fw-bold">1. เห็นชอบ :</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="flex-grow-1">
                                            <textarea name="approve_comment" class="form-control" rows="2"
                                                placeholder="ระบุความเห็น">{{ $meeting_agenda_lecture->approve_comment }}</textarea>
                                        </div>
                                        <div class="input-group" style="width: 200px;">
                                            <input type="number" name="approve_votes" class="form-control"
                                                placeholder="จำนวนเสียง" value="{{ $meeting_agenda_lecture->approve_votes }}">
                                            <span class="input-group-text">เสียง</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- ไม่เห็นชอบ -->
                                <div class="mb-3">
                                    <label class="form-label fw-bold">2. ไม่เห็นชอบ :</label>
                                    <div class="d-flex align-items-center gap-3">
                                        <div class="flex-grow-1">
                                            <textarea name="disapprove_comment" class="form-control" rows="2"
                                                placeholder="ระบุความเห็น">{{ $meeting_agenda_lecture->disapprove_comment }}</textarea>
                                        </div>
                                        <div class="input-group" style="width: 200px;">
                                            <input type="number" name="disapprove_votes" class="form-control"
                                                placeholder="จำนวนเสียง" value="{{ $meeting_agenda_lecture->disapprove_votes }}">
                                            <span class="input-group-text">เสียง</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-primary px-4">บันทึก</button>
                            <a href="{{ route('add.meeting.agenda.lecture', $meeting_agenda_lecture->meeting_agenda_id) }}"
                               class="btn btn-danger px-4">ยกเลิก</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Initialize CKEditor สำหรับเนื้อหาหลัก
        CKEDITOR.replace('content', {
            language: 'th',
            height: 300,
            removeButtons: 'PasteFromWord'
        });

        // Initialize CKEditor สำหรับความเห็นคณะกรรมการ
        CKEDITOR.replace('committee_opinion', {
            language: 'th',
            height: 150,
            toolbar: 'Basic',
            removeButtons: 'PasteFromWord'
        });
    </script>
@endsection --}}
