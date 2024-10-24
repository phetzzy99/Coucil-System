<!-- resources/views/admin/meeting_reports/summary.blade.php -->
@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">สรุปรายงานการประชุม</h4>
                        <div class="header-actions">
                            <button class="btn btn-primary" onclick="enableEdit()">แก้ไขรายงาน</button>
                            <button class="btn btn-success" id="saveReport" style="display:none;">บันทึก</button>
                        </div>
                    </div>

                    <!-- Modal แสดงประวัติการแก้ไข -->
                    <div class="modal fade" id="editHistoryModal" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">ประวัติการแก้ไขรายงานการประชุม</h5>
                                    <button type="button" class="close" data-dismiss="modal">
                                        <span>&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="table-responsive">
                                        <table class="table table-bordered">
                                            <thead>
                                                <tr>
                                                    <th>วันที่แก้ไข</th>
                                                    <th>ส่วนที่แก้ไข</th>
                                                    <th>ผู้แก้ไข</th>
                                                    <th>รายละเอียด</th>
                                                </tr>
                                            </thead>
                                            <tbody id="editHistoryTableBody">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <!-- ข้อมูลการประชุม -->
                        <div class="meeting-info mb-4">
                            <!-- ... ส่วนแสดงข้อมูลการประชุม ... -->
                        </div>

                        <form id="reportForm">
                            @foreach($contentDetails as $section)
                            <div class="section-content mb-4">
                                <h5>{{ $section['title'] }}</h5>

                                <!-- เนื้อหาหลักของส่วน -->
                                <div class="section-main-content mb-3">
                                    <textarea
                                        class="form-control content-editor"
                                        name="sections[{{ $section['id'] }}][content]"
                                        rows="4"
                                        disabled
                                    >{{ $section['content'] }}</textarea>
                                </div>

                                <!-- ส่วนแสดงความคิดเห็นการแก้ไข -->
                                <div class="edit-comments mb-3" style="display:none;">
                                    <textarea
                                        class="form-control"
                                        name="edit_comments[{{ $section['id'] }}]"
                                        placeholder="ระบุความคิดเห็นหรือการแก้ไข"
                                        rows="2"
                                    ></textarea>
                                </div>

                                <!-- Lectures และ Items -->
                                @foreach($section['lectures'] as $lecture)
                                <div class="lecture-content ml-4 mb-3">
                                    <h6>{{ $lecture['title'] }}</h6>
                                    <textarea
                                        class="form-control content-editor"
                                        name="sections[{{ $section['id'] }}][lectures][{{ $lecture['id'] }}][content]"
                                        rows="3"
                                        disabled
                                    >{{ $lecture['content'] }}</textarea>

                                    @foreach($lecture['items'] as $item)
                                    <div class="item-content ml-4 mb-2">
                                        <p class="mb-1">{{ $item['title'] }}</p>
                                        <textarea
                                            class="form-control content-editor"
                                            name="sections[{{ $section['id'] }}][lectures][{{ $lecture['id'] }}][items][{{ $item['id'] }}][content]"
                                            rows="2"
                                            disabled
                                        >{{ $item['content'] }}</textarea>
                                    </div>
                                    @endforeach
                                </div>
                                @endforeach

                                <!-- แสดงการรับรองและความคิดเห็น -->
                                @if(isset($approvalSummary['sections_feedback'][$section['id']]))
                                <div class="approval-feedback">
                                    <div class="approval-stats">
                                        <span class="badge bg-success">
                                            รับรองไม่มีแก้ไข: {{ $approvalSummary['sections_feedback'][$section['id']]['no_changes'] }}
                                        </span>
                                        <span class="badge bg-warning">
                                            รับรองมีแก้ไข: {{ $approvalSummary['sections_feedback'][$section['id']]['with_changes'] }}
                                        </span>
                                    </div>
                                    @if(!empty($approvalSummary['sections_feedback'][$section['id']]['comments']))
                                    <div class="comments-list mt-2">
                                        <h6>ความคิดเห็นจากการรับรอง:</h6>
                                        @foreach($approvalSummary['sections_feedback'][$section['id']]['comments'] as $comment)
                                        <div class="comment-item">
                                            <strong>{{ $comment['user'] }}:</strong>
                                            <span>{{ $comment['comment'] }}</span>
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                                @endif
                            </div>
                            @endforeach

                            <!-- การรับรองของ Admin -->
                            <div class="admin-approval mt-4" style="display:none;">
                                <div class="form-check mb-3">
                                    <input class="form-check-input" type="checkbox" id="adminApproval" name="admin_approval">
                                    <label class="form-check-label" for="adminApproval">
                                        รับรองรายงานการประชุมโดย Admin
                                    </label>
                                </div>
                                <div class="form-group">
                                    <label for="adminComment">ความคิดเห็นเพิ่มเติม</label>
                                    <textarea
                                        class="form-control"
                                        id="adminComment"
                                        name="admin_approval_comment"
                                        rows="3"
                                    ></textarea>
                                </div>
                            </div>
                        </form>

                        <!-- รายชื่อผู้รับรอง -->
                        <div class="approvers-list mt-4">
                            <!-- ... ส่วนแสดงรายชื่อผู้รับรอง ... -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    let isEditing = false;

    function showEditHistory() {
        $.ajax({
            url: '{{ route("meeting.report.history", $meeting->id) }}',
            method: 'GET',
            success: function(response) {
                let tableBody = '';
                response.forEach(function(edit) {
                    tableBody += `
                        <tr>
                            <td>${edit.edited_at}</td>
                            <td>${edit.section}</td>
                            <td>${edit.editor}</td>
                            <td>${edit.comment}</td>
                        </tr>
                    `;
                });
                $('#editHistoryTableBody').html(tableBody);
                $('#editHistoryModal').modal('show');
            },
            error: function(xhr) {
                toastr.error('ไม่สามารถโหลดประวัติการแก้ไขได้');
            }
        });
    }

    // เพิ่มฟังก์ชันตรวจสอบการเปลี่ยนแปลงก่อนบันทึก
    let originalContent = {};

    function enableEdit() {
        if (!isEditing) {
            // เก็บข้อมูลเดิมก่อนแก้ไข
            $('.content-editor').each(function() {
                originalContent[$(this).attr('name')] = $(this).val();
            });
        }

        isEditing = !isEditing;
        $('.content-editor').prop('disabled', !isEditing);
        $('.edit-comments').toggle(isEditing);
        $('.admin-approval').toggle(isEditing);
        $('#saveReport').toggle(isEditing);

        const btnEdit = $('.btn-primary');
        btnEdit.text(isEditing ? 'ยกเลิกการแก้ไข' : 'แก้ไขรายงาน');
        btnEdit.toggleClass('btn-primary btn-danger');
    }

    // ตรวจสอบการเปลี่ยนแปลงก่อนบันทึก
    function hasChanges() {
        let changed = false;
        $('.content-editor').each(function() {
            const currentValue = $(this).val();
            const originalValue = originalContent[$(this).attr('name')];
            if (currentValue !== originalValue) {
                changed = true;
                return false; // break loop
            }
        });
        return changed;
    }

    $(document).ready(function() {
        // เพิ่ม TinyMCE หรือ CKEditor สำหรับ rich text editing
        if (typeof tinymce !== 'undefined') {
            tinymce.init({
                selector: '.content-editor',
                height: 300,
                plugins: 'lists link table',
                toolbar: 'undo redo | formatselect | bold italic | alignleft aligncenter alignright | bullist numlist | link table',
                readonly: true
            });
        }

        $('#saveReport').click(function() {
            if (!hasChanges() && !$('#adminApproval').is(':checked')) {
                toastr.warning('ไม่พบการเปลี่ยนแปลงข้อมูล');
                return;
            }

            const formData = new FormData($('#reportForm')[0]);

            // เพิ่มข้อมูล TinyMCE (ถ้าใช้)
            if (typeof tinymce !== 'undefined') {
                $('.content-editor').each(function() {
                    formData.set($(this).attr('name'), tinymce.get($(this).attr('id')).getContent());
                });
            }

            $.ajax({
                url: '{{ route("meeting.report.update", $meeting->id) }}',
                method: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    toastr.success(response.message);
                    setTimeout(() => {
                        location.reload();
                    }, 1500);
                },
                error: function(xhr) {
                    toastr.error(xhr.responseJSON?.message || 'เกิดข้อผิดพลาดในการบันทึกข้อมูล');
                }
            });
        });
    });

    // เพิ่ม confirm dialog เมื่อยกเลิกการแก้ไข
    window.onbeforeunload = function() {
        if (isEditing && hasChanges()) {
            return "คุณมีข้อมูลที่ยังไม่ได้บันทึก ต้องการออกจากหน้านี้หรือไม่?";
        }
    };

</script>
@endsection

@endsection
