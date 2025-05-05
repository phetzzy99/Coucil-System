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
                        <li class="breadcrumb-item active" aria-current="page">เพิ่มรายงานมติการประชุม</li>
                    </ol>
                </nav>
            </div>
        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body p-4">
                <h5 class="mb-4">เพิ่มรายงานมติการประชุม</h5>
                <form id="meetingResolutionForm" action="{{ route('store.meeting.resolution') }}" method="post" class="row g-3" enctype="multipart/form-data">
                    @csrf

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="col-md-12">
                        <div class="card border-0 shadow-none bg-light">
                            <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(to right, #4e73df, #36b9cc);">
                                <h6 class="mb-0 text-white">ข้อมูลการประชุม</h6>
                                <div class="header-elements">
                                    <i class="bx bx-info-circle text-white"></i>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-6">
                                        <label for="meeting_type_id" class="form-label">1. เลือกประเภทการประชุม</label>
                                        <select name="meeting_type_id" id="meeting_type_id" class="form-select mb-3" required>
                                            <option selected="" disabled>เลือกประเภทการประชุม</option>
                                            @foreach ($meetingTypes as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="committee_category_id" class="form-label">2. เลือกประเภทคณะกรรมการ</label>
                                        <select name="committee_category_id" id="committee_category_id" class="form-select mb-3" required>
                                            <option selected="" disabled>เลือกประเภทคณะกรรมการ</option>
                                            @foreach ($committeeCategories as $committee)
                                                <option value="{{ $committee->id }}">{{ $committee->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="meeting_agenda_id" class="form-label">3. เลือกชื่อรายงานการประชุม</label>
                                        <select name="meeting_agenda_id" id="meeting_agenda_id" class="form-select mb-3" required disabled>
                                            <option selected="" disabled>เลือกชื่อรายงานการประชุม</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="meeting_agenda_section_id" class="form-label">4. เลือกวาระการประชุม</label>
                                        <select name="meeting_agenda_section_id" id="meeting_agenda_section_id" class="form-select mb-3" required disabled>
                                            <option selected="" disabled>เลือกวาระการประชุม</option>
                                        </select>
                                    </div>

                                    <!-- เพิ่มหลังจาก dropdown เลือก section -->
                                    <div class="form-group col-md-6">
                                        <label for="meeting_agenda_lecture_id" class="form-label">5. เลือกหัวข้อย่อย</label>
                                        <select name="meeting_agenda_lecture_id" id="meeting_agenda_lecture_id" class="form-select mb-3" required disabled>
                                            <option value="">-- เลือกหัวข้อย่อย --</option>
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="proposer" class="form-label">ผู้เสนอวาระ :</label>
                                        <input type="text" name="proposer" id="proposer" class="form-control" required>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="document" class="form-label">เอกสารประกอบการประชุม :</label>
                                        <div class="input-group">
                                            <input type="file" name="document" id="document" class="form-control">
                                            <button class="btn btn-outline-secondary" type="button">
                                                <i class="bx bx-paperclip"></i>
                                            </button>
                                        </div>
                                        <small class="text-muted mt-1 d-block">รองรับไฟล์: PDF, Word, Excel, PowerPoint, รูปภาพ (ขนาดไม่เกิน 10MB)</small>
                                        <div id="document-preview" class="mt-2 d-none">
                                            <div class="alert alert-info d-flex align-items-center">
                                                <i class="bx bx-file me-2"></i>
                                                <span id="document-name"></span>
                                                <button type="button" class="btn-close ms-auto" id="remove-document"></button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mt-3">
                        <div class="card border-0 shadow-none bg-light">
                            <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(to right, #4e73df, #36b9cc);">
                                <h6 class="mb-0 text-white">มติที่ประชุม</h6>
                                <div class="header-elements">
                                    <i class="bx bx-info-circle text-white"></i>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="form-group col-md-12">
                                        <label for="resolution_text" class="form-label">มติที่ประชุม :</label>
                                        <textarea name="resolution_text" id="resolution_text" class="form-control" rows="8" required></textarea>
                                    </div>
                                    {{-- <div class="form-group col-md-12">
                                        <label for="resolution_text" class="form-label">มติที่ประชุม :</label>
                                        <textarea name="resolution_text" id="resolution_text" class="form-control" rows="8" required></textarea>
                                    </div> --}}

                                    <div class="form-group col-md-6">
                                        <label for="resolution_date" class="form-label">วันที่มีมติ</label>
                                        <input type="date" name="resolution_date" id="resolution_date" class="form-control" required>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="resolution_status" class="form-label">สถานะมติ</label>
                                        <select name="resolution_status" id="resolution_status" class="form-select mb-3" required>
                                            <option value="approved">อนุมัติ</option>
                                            <option value="rejected">ไม่อนุมัติ</option>
                                            <option value="pending">รอพิจารณา</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <hr class="my-4" style="border-top: 1px solid #ddd; opacity: 0.7;">
                    </div>

                    <!-- เพิ่มส่วนการดำเนินการที่ได้รับมอบหมาย -->
                    <div class="col-md-12 mt-4">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center" style="background: linear-gradient(to right, #4e73df, #36b9cc);">
                                <h6 class="mb-0 text-white">การดำเนินการที่ได้รับมอบหมาย</h6>
                                <div class="header-elements">
                                    <i class="bx bx-info-circle text-white"></i>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="form-group col-md-12 mb-3">
                                    <label for="task_title" class="form-label">เรื่อง :</label>
                                    <input type="text" name="task_title" id="task_title" class="form-control" required>
                                </div>

                                <div class="form-group col-md-12 mb-3">
                                    <label for="responsible_person" class="form-label">ผู้รับผิดชอบ :</label>
                                    <input type="text" name="responsible_person" id="responsible_person" class="form-control" required>
                                </div>

                                <div class="form-group col-md-12 mb-3">
                                    <label class="form-label">ผลการดำเนินงาน :</label>
                                    <div class="d-flex gap-4 mt-2">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="task_status" id="task_status_completed" value="completed" checked>
                                            <label class="form-check-label" for="task_status_completed">
                                                ดำเนินการแล้ว
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="task_status" id="task_status_in_progress" value="in_progress">
                                            <label class="form-check-label" for="task_status_in_progress">
                                                อยู่ระหว่างดำเนินการ
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="task_status" id="task_status_not_started" value="not_started">
                                            <label class="form-check-label" for="task_status_not_started">
                                                ยังไม่ดำเนินการ
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group col-md-6">
                                    <label for="report_date" class="form-label">วันที่รายงานผล :</label>
                                    <input type="date" name="report_date" id="report_date" class="form-control" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-12 mt-4">
                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-primary px-4">
                                <i class="bx bx-save me-1"></i> บันทึก
                            </button>
                            <a href="{{ route('all.meeting.resolution') }}" class="btn btn-danger px-4">
                                <i class="bx bx-x-circle me-1"></i> ยกเลิก
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            // เมื่อเลือกประเภทคณะกรรมการและประเภทการประชุม
            $('#committee_category_id, #meeting_type_id').change(function() {
                var committeeId = $('#committee_category_id').val();
                var meetingTypeId = $('#meeting_type_id').val();

                if (committeeId && meetingTypeId) {
                    // เปิดใช้งาน dropdown รายงานการประชุม
                    $('#meeting_agenda_id').prop('disabled', false);

                    // ดึงข้อมูลรายงานการประชุมตามประเภทคณะกรรมการและประเภทการประชุม
                    $.ajax({
                        url: "{{ route('get.meeting.agendas') }}",
                        type: "GET",
                        data: {
                            committee_category_id: committeeId,
                            meeting_type_id: meetingTypeId
                        },
                        success: function(data) {
                            $('#meeting_agenda_id').empty();
                            $('#meeting_agenda_id').append('<option selected disabled>เลือกชื่อรายงานการประชุม</option>');
                            $.each(data, function(key, value) {
                                $('#meeting_agenda_id').append('<option value="' + value.id + '">' + value.meeting_agenda_title + '</option>');
                            });
                        }
                    });
                } else {
                    $('#meeting_agenda_id').prop('disabled', true);
                    $('#meeting_agenda_section_id').prop('disabled', true);
                }
            });

            // เมื่อเลือกรายงานการประชุม
            $('#meeting_agenda_id').change(function() {
                var meetingAgendaId = $(this).val();

                if (meetingAgendaId) {
                    // เปิดใช้งาน dropdown วาระการประชุม
                    $('#meeting_agenda_section_id').prop('disabled', false);

                    // ดึงข้อมูลวาระการประชุมตามรายงานการประชุมที่เลือก
                    $.ajax({
                        url: "{{ route('get.meeting.sections') }}",
                        type: "GET",
                        data: {
                            meeting_agenda_id: meetingAgendaId
                        },
                        success: function(data) {
                            $('#meeting_agenda_section_id').empty();
                            $('#meeting_agenda_section_id').append('<option selected disabled>เลือกวาระการประชุม</option>');
                            $.each(data, function(key, value) {
                                $('#meeting_agenda_section_id').append('<option value="' + value.id + '">' + value.section_title + '</option>');
                            });
                        }
                    });
                } else {
                    $('#meeting_agenda_section_id').prop('disabled', true);
                }
            });

            $('#meeting_agenda_section_id').change(function() {
                var sectionId = $(this).val();

                if (sectionId) {
                    // เปิดใช้งาน dropdown หัวข้อย่อย
                    $('#meeting_agenda_lecture_id').prop('disabled', false);

                    $.ajax({
                        url: '/get-meeting-agenda-lectures/' + sectionId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            $('#meeting_agenda_lecture_id').empty();
                            $('#meeting_agenda_lecture_id').append('<option value="">-- เลือกหัวข้อย่อย --</option>');
                            $.each(data, function(key, value) {
                                $('#meeting_agenda_lecture_id').append('<option value="' + value.id + '">' + value.lecture_title + '</option>');
                            });
                        }
                    });
                } else {
                    $('#meeting_agenda_lecture_id').empty();
                    $('#meeting_agenda_lecture_id').append('<option value="">-- เลือกหัวข้อย่อย --</option>');
                }
            });

            // แสดงชื่อไฟล์ที่อัปโหลด
            $('#document').change(function() {
                var file = this.files[0];
                if (file) {
                    $('#document-preview').removeClass('d-none');
                    $('#document-name').text(file.name);
                } else {
                    $('#document-preview').addClass('d-none');
                }
            });

            // ลบไฟล์ที่เลือก
            $('#remove-document').click(function() {
                $('#document').val('');
                $('#document-preview').addClass('d-none');
            });

            // กำหนดค่าเริ่มต้นของวันที่
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0');
            var yyyy = today.getFullYear();
            today = yyyy + '-' + mm + '-' + dd;

            $('#resolution_date').val(today);
            $('#report_date').val(today);

            // Validation
            $('#meetingResolutionForm').validate({
                rules: {
                    committee_category_id: {
                        required: true,
                    },
                    proposer: {
                        required: true,
                    },
                    meeting_type_id: {
                        required: true,
                    },
                    meeting_agenda_id: {
                        required: true,
                    },
                    meeting_agenda_section_id: {
                        required: true,
                    },
                    resolution_text: {
                        required: true,
                    },
                    resolution_date: {
                        required: true,
                    },
                    resolution_status: {
                        required: true,
                    },
                    task_title: {
                        required: true,
                    },
                    responsible_person: {
                        required: true,
                    },
                    report_date: {
                        required: true,
                    },
                },
                messages: {
                    committee_category_id: {
                        required: 'กรุณาเลือกประเภทคณะกรรมการ',
                    },
                    proposer: {
                        required: 'กรุณาระบุผู้เสนอวาระ',
                    },
                    meeting_type_id: {
                        required: 'กรุณาเลือกประเภทการประชุม',
                    },
                    meeting_agenda_id: {
                        required: 'กรุณาเลือกชื่อรายงานการประชุม',
                    },
                    meeting_agenda_section_id: {
                        required: 'กรุณาเลือกวาระการประชุม',
                    },
                    resolution_text: {
                        required: 'กรุณาระบุมติที่ประชุม',
                    },
                    resolution_date: {
                        required: 'กรุณาระบุวันที่มีมติ',
                    },
                    resolution_status: {
                        required: 'กรุณาเลือกสถานะมติ',
                    },
                    task_title: {
                        required: 'กรุณาระบุเรื่องที่ได้รับมอบหมาย',
                    },
                    responsible_person: {
                        required: 'กรุณาระบุผู้รับผิดชอบ',
                    },
                    report_date: {
                        required: 'กรุณาระบุวันที่รายงานผล',
                    },
                },
                errorElement: 'span',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    element.closest('.form-group').append(error);
                },
                highlight: function(element, errorClass, validClass) {
                    $(element).addClass('is-invalid');
                },
                unhighlight: function(element, errorClass, validClass) {
                    $(element).removeClass('is-invalid');
                },
            });
        });
    </script>
    <!-- เพิ่ม CKEditor script -->
    {{-- <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script> --}}
    <script src="{{ asset('backend/assets/plugins/ckeditor/ckeditor.js') }}"></script>
    <script>
        $(document).ready(function() {
            // ติดตั้ง CKEditor สำหรับ textarea ที่ต้องการ
            CKEDITOR.replace('resolution_text', {
                language: 'th',
                height: 300,
                toolbar: [
                    ['Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat'],
                    ['NumberedList', 'BulletedList', 'Outdent', 'Indent'],
                    ['JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'],
                    ['Link', 'Unlink'],
                    ['FontSize', 'TextColor', 'BGColor'],
                    ['Source', 'Maximize']
                ],
                removeButtons: 'PasteFromWord'
            });

            // ปรับ validation เพื่อให้ทำงานร่วมกับ CKEditor
            $('#meetingResolutionForm').submit(function() {
                // อัปเดต textarea ด้วยข้อมูลจาก CKEditor ก่อน submit
                for (instance in CKEDITOR.instances) {
                    CKEDITOR.instances[instance].updateElement();
                }
            });
        });
    </script>
@endsection
