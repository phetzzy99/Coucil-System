@extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    {{-- เพิ่ม CKEditor script หากยังไม่ได้ include ใน admin_dashboard --}}
    <script src="{{ asset('backend/assets/plugins/ckeditor/ckeditor.js') }}"></script>

    <div class="page-content">
        <div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
            <div class="ps-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0 p-0">
                         <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                        <li class="breadcrumb-item"><a href="{{ route('all.meeting.resolution.types') }}">รายงานมติที่ประชุม</a></li>
                        <li class="breadcrumb-item active" aria-current="page">แก้ไขรายงานมติที่ประชุม</li>
                    </ol>
                </nav>
            </div>
        </div>
        <div class="card">
            <div class="card-body p-4">
                 <h5 class="card-title mb-4">แก้ไขรายงานมติที่ประชุม: {{ $resolutionType->name }}</h5>
                 <hr/>
                <form id="resolutionTypeForm" action="{{ route('update.meeting.resolution.type') }}" method="post" class="row g-3" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" value="{{ $resolutionType->id }}"> {{-- สำคัญ: ต้องมี id สำหรับ update --}}

                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                             <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    {{-- ส่วนข้อมูลหลักของการประชุม --}}
                    <div class="col-md-12">
                        <div class="card shadow-sm border-0">
                             <div class="card-header bg-light">
                                <h6 class="mb-0 text-primary"><i class="bx bx-info-circle me-2"></i>ข้อมูลการประชุม</h6>
                            </div>
                            <div class="card-body">
                                <div class="row g-3">
                                    <div class="form-group col-md-6">
                                        <label for="management_category_id" class="form-label">เลขหมวดด้านการบริหาร <span class="text-danger">*</span></label>
                                        <select name="management_category_id" id="management_category_id" class="form-select">
                                            <option value="" disabled>-- เลือกเลขหมวดด้านการบริหาร --</option>
                                            @foreach ($managementCategories as $category)
                                                <option value="{{ $category->id }}" {{ $resolutionType->management_category_id == $category->id ? 'selected' : '' }}>{{ $category->category_code }} - {{ $category->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="meeting_type_id" class="form-label">ประเภทการประชุม <span class="text-danger">*</span></label>
                                        <select name="meeting_type_id" id="meeting_type_id" class="form-select">
                                            <option value="" disabled>-- เลือกประเภทการประชุม --</option>
                                            @foreach ($meetingTypes as $type)
                                                <option value="{{ $type->id }}" {{ $resolutionType->meeting_type_id == $type->id ? 'selected' : '' }}>{{ $type->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="meeting_no" class="form-label">ครั้งที่ประชุม <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="meeting_no" id="meeting_no" placeholder="เช่น 1" value="{{ $resolutionType->meeting_no }}">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="meeting_year" class="form-label">ปี พ.ศ. <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="meeting_year" id="meeting_year" placeholder="เช่น 2567" value="{{ $resolutionType->meeting_year }}">
                                    </div>

                                    <div class="form-group col-md-4">
                                        <label for="meeting_date" class="form-label">วันที่ประชุม <span class="text-danger">*</span></label>
                                        {{-- Format date for input type="date" --}}
                                        <input type="date" class="form-control" name="meeting_date" id="meeting_date" value="{{ $resolutionType->meeting_date ? \Carbon\Carbon::parse($resolutionType->meeting_date)->format('Y-m-d') : '' }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ส่วนรายละเอียดมติ --}}
                     <div class="col-md-12 mt-4">
                         <div class="card shadow-sm border-0">
                             <div class="card-header bg-light">
                                <h6 class="mb-0 text-primary"><i class="bx bx-detail me-2"></i>รายละเอียดมติ</h6>
                            </div>
                             <div class="card-body">
                                 <div class="row g-3">
                                    <div class="form-group col-md-12">
                                        <label for="name" class="form-label">เรื่องพิจารณา <span class="text-danger">*</span></label>
                                        <select name="name" id="name" class="form-select">
                                            <option value="" disabled>-- เลือกเรื่องพิจารณา --</option>
                                            @foreach ($managementKeywords as $keyword)
                                                <option value="{{ $keyword->keyword_title }}" {{ $resolutionType->name == $keyword->keyword_title ? 'selected' : '' }}>{{ $keyword->keyword_title }} ({{ $keyword->managementCategory->category_code }})</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="form-group col-md-12">
                                        <label for="agenda_title" class="form-label">ระเบียบวาระการประชุม <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" name="agenda_title" id="agenda_title" placeholder="ระบุระเบียบวาระที่เกี่ยวข้อง" value="{{ $resolutionType->agenda_title }}">
                                    </div>

                                     <div class="form-group col-md-12">
                                        <label for="resolution_text" class="form-label">มติที่ประชุม <span class="text-danger">*</span></label>
                                        <textarea name="resolution_text" id="resolution_text" class="form-control" rows="5">{{ $resolutionType->resolution_text }}</textarea>
                                     </div>
                                 </div>
                            </div>
                        </div>
                    </div>

                    {{-- ส่วนสถานะและเอกสารแนบ --}}
                    <div class="col-md-12 mt-4">
                         <div class="card shadow-sm border-0">
                             <div class="card-header bg-light">
                                <h6 class="mb-0 text-primary"><i class="bx bx-paperclip me-2"></i>สถานะและเอกสารแนบ</h6>
                            </div>
                             <div class="card-body">
                                <div class="row g-3">
                                    <div class="form-group col-md-6">
                                        <label class="form-label">สถานะการดำเนินการ <span class="text-danger">*</span></label>
                                        <div class="mt-2">
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="task_status" id="task_status_completed" value="completed" {{ $resolutionType->task_status == 'completed' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="task_status_completed">
                                                    ดำเนินการแล้ว
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="task_status" id="task_status_in_progress" value="in_progress" {{ $resolutionType->task_status == 'in_progress' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="task_status_in_progress">
                                                    อยู่ระหว่างดำเนินการ
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input" type="radio" name="task_status" id="task_status_not_started" value="not_started" {{ $resolutionType->task_status == 'not_started' ? 'checked' : '' }}>
                                                <label class="form-check-label" for="task_status_not_started">
                                                    ยังไม่ดำเนินการ
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group col-md-6">
                                        <label for="document" class="form-label">เอกสารแนบ (เลือกไฟล์ใหม่หากต้องการเปลี่ยน)</label>
                                        <input type="file" class="form-control" name="document" id="document" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png">
                                        <small class="text-muted d-block mt-1">รองรับ: PDF, Word, Excel, PowerPoint, รูปภาพ (สูงสุด 10MB)</small>
                                        @if($resolutionType->document)
                                            <div class="mt-2">
                                                เอกสารปัจจุบัน: <a href="{{ asset($resolutionType->document) }}" target="_blank" class="btn btn-outline-secondary btn-sm"><i class="bx bx-link-external me-1"></i>ดูเอกสาร</a>
                                            </div>
                                        @endif
                                    </div>
                                 </div>
                            </div>
                        </div>
                    </div>

                    {{-- ปุ่มบันทึก/ยกเลิก --}}
                    <div class="col-12 mt-4">
                         <div class="d-md-flex d-grid align-items-center gap-3 justify-content-end">
                            <a href="{{ route('all.meeting.resolution.types') }}" class="btn btn-outline-secondary px-4"><i class="bx bx-x me-1"></i>ยกเลิก</a>
                            <button type="submit" class="btn btn-primary px-4"><i class="bx bx-save me-1"></i>อัปเดตข้อมูล</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

     <script>
        $(document).ready(function() {
            // Initialize CKEditor
            CKEDITOR.replace('resolution_text', {
                language: 'th', // ตั้งค่าภาษาไทย
                height: 250, // ความสูงของ editor
                toolbar: [ // กำหนดแถบเครื่องมือที่ต้องการ
                    { name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', 'Strike', 'RemoveFormat' ] },
                    { name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote' ] },
                    { name: 'links', items: [ 'Link', 'Unlink' ] },
                    { name: 'styles', items: [ 'Styles', 'Format', 'Font', 'FontSize' ] },
                    { name: 'colors', items: [ 'TextColor', 'BGColor' ] },
                    { name: 'tools', items: [ 'Maximize', 'ShowBlocks' ] },
                    { name: 'document', items: [ 'Source' ] }
                ],
                removeButtons: 'PasteFromWord,Image,Table,HorizontalRule,Smiley,SpecialChar,PageBreak,Iframe' // ปุ่มที่ไม่ต้องการ
            });

            // Form Validation
             $('#resolutionTypeForm').validate({
                 rules: {
                     management_category_id: { required: true },
                     meeting_type_id: { required: true },
                     meeting_no: { required: true, digits: true },
                     meeting_year: { required: true, digits: true, minlength: 4, maxlength: 4 },
                     meeting_date: { required: true, date: true },
                     name: { required: true, maxlength: 255 },
                     agenda_title: { required: true, maxlength: 255 },
                     task_status: { required: true },
                     resolution_text: { required: true },
                     document: { // Validation for file upload (optional on edit)
                         extension: "pdf|doc|docx|xls|xlsx|ppt|pptx|jpg|jpeg|png",
                         maxsize: 10485760 // 10MB in bytes
                     }
                 },
                 messages: {
                     management_category_id: { required: 'กรุณาเลือกเลขหมวดด้านการบริหาร' },
                     meeting_type_id: { required: 'กรุณาเลือกประเภทการประชุม' },
                     meeting_no: { required: 'กรุณาระบุครั้งที่ประชุม', digits: 'กรุณาระบุเป็นตัวเลขเท่านั้น' },
                     meeting_year: { required: 'กรุณาระบุปี พ.ศ.', digits: 'กรุณาระบุเป็นตัวเลขเท่านั้น', minlength: 'กรุณาระบุปี พ.ศ. 4 หลัก', maxlength: 'กรุณาระบุปี พ.ศ. 4 หลัก' },
                     meeting_date: { required: 'กรุณาระบุวันที่ประชุม', date: 'รูปแบบวันที่ไม่ถูกต้อง' },
                     name: { required: 'กรุณาระบุเรื่องพิจารณา', maxlength: 'ความยาวต้องไม่เกิน 255 ตัวอักษร' },
                     agenda_title: { required: 'กรุณาระบุระเบียบวาระการประชุม', maxlength: 'ความยาวต้องไม่เกิน 255 ตัวอักษร' },
                     task_status: { required: 'กรุณาระบุสถานะการดำเนินการ' },
                     resolution_text: { required: 'กรุณาระบุมติที่ประชุม' },
                     document: {
                         extension: "ไฟล์ที่รองรับคือ PDF, Word, Excel, PowerPoint, รูปภาพ เท่านั้น",
                         maxsize: "ขนาดไฟล์ต้องไม่เกิน 10MB"
                     }
                 },
                 errorElement: 'div', // ใช้ div แสดงข้อผิดพลาด
                 errorPlacement: function(error, element) {
                     error.addClass('invalid-feedback'); // เพิ่ม class ของ Bootstrap
                     if (element.prop("type") === "radio") {
                         error.insertAfter(element.closest(".mt-2")); // แสดงข้อผิดพลาดหลังกลุ่ม radio
                     } else if (element.prop("type") === "file") {
                          error.insertAfter(element.next('.text-muted')); // แสดงข้อผิดพลาดหลัง small text
                     } else if (element.hasClass('form-select')) {
                         error.insertAfter(element); // แสดงข้อผิดพลาดหลัง select box
                     } else if (element.attr("name") == "resolution_text") {
                          error.insertAfter(element.next()); // สำหรับ CKEditor, แสดงหลัง div ที่ CKEditor สร้าง
                     }
                      else {
                         error.insertAfter(element); // สำหรับ input อื่นๆ
                     }
                 },
                  highlight: function(element, errorClass, validClass) {
                     $(element).addClass('is-invalid').removeClass('is-valid');
                       // Highlight CKEditor
                    if ($(element).attr('name') == 'resolution_text') {
                        $(element.next()).addClass('is-invalid'); // ใช้ .next() เพื่อหา element ที่ CKEditor สร้าง
                    }
                 },
                 unhighlight: function(element, errorClass, validClass) {
                     $(element).removeClass('is-invalid').addClass('is-valid');
                     // Unhighlight CKEditor
                    if ($(element).attr('name') == 'resolution_text') {
                        $(element.next()).removeClass('is-invalid').addClass('is-valid');
                    }
                 },
                 // Submit handler for CKEditor
                 submitHandler: function(form) {
                    // Update CKEditor instances before submitting the form
                    for (instance in CKEDITOR.instances) {
                        CKEDITOR.instances[instance].updateElement();
                    }
                    form.submit(); // Submit the form
                }
             });

              // Add custom method for file size validation
              $.validator.addMethod('maxsize', function(value, element, param) {
                 return this.optional(element) || (element.files[0] && element.files[0].size <= param);
              }, 'ขนาดไฟล์เกินกำหนด');

         });
     </script>
@endsection
