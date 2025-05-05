@extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    {{-- <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script> --}}
    {{-- <script src="https://cdn.ckeditor.com/4.25.0-lts/standard/ckeditor.js"></script> --}}
    <script src="{{ asset('backend/assets/plugins/ckeditor/ckeditor.js') }}"></script>
    {{-- <script src="//cdn.ckeditor.com/4.22.1/full/ckeditor.js"></script> --}}

    <div class="page-content">
        <div class="row">
            <div class="col-12">
                <div class="card radius-10">
                    <div class="card-body">
                        <div class="d-flex align-items-center">
                            <img src="{{ asset('uploads/no_image.jpg') }}" class="rounded-circle p-1 border" width="90"
                                height="90" alt="...">
                            <div class="flex-grow-1 ms-3">
                                <h5 class="mt-0">{{ $meeting_agenda->meeting_agenda_title }}</h5>
                                <p class="mb-0">
                                    {{ 'ครั้งที่ ' . $meeting_agenda->meeting_agenda_number . ' ปี ' . $meeting_agenda->meeting_agenda_year . ' วันที่ ' . \Carbon\Carbon::parse($meeting_agenda->meeting_agenda_date)->locale('th')->isoFormat('LL') }}
                                </p>
                            </div>

                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="meetingStatusSwitch"
                                    {{ $meeting_agenda->status == 1 ? 'checked' : '' }}
                                    data-meeting-id="{{ $meeting_agenda->id }}"
                                    onchange="updateMeetingStatus(this)"
                                    style="width: 3em; height: 1.5em;">
                                <label class="form-check-label" for="meetingStatusSwitch" style="font-size: 1.2em; margin-left: 10px;">
                                    สถานะ: <span id="statusText" class="{{ $meeting_agenda->status == 1 ? 'text-success' : 'text-danger' }}">{{ $meeting_agenda->status == 1 ? 'เปิดการมองเห็น' : 'ปิดการมองเห็น' }}</span>
                                </label>
                            </div>

                            <div style="width: 20px;"></div>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">เพิ่มวาระ</button>
                            <div style="width: 20px;"></div>
                            <a href="{{ route('all.meeting.agenda') }}" class="btn btn-danger">กลับ</a>
                        </div>
                    </div>
                </div>
                @foreach ($meeting_section as $key => $item)
                    <div class="container">
                        <div class="main-body">
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-body p-4 d-flex justify-content-between">
                                            <h6>{{ $item->section_title }}</h6>
                                            <div style="margin-top: 10px"
                                                class="d-flex justify-content-between align-items-center">
                                                <form action="{{ route('delete.meeting.agenda.section', ['id' => $item->id]) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-danger px-2 ms-auto me-2"
                                                        onclick="return confirm('คุณต้องการลบวาระนี้ใช่หรือไม่?')">ลบวาระ </button>
                                                </form>
                                                <a style="margin-left: 10px" class="btn btn-warning ms-2" href="{{ route('edit.meeting.agenda.section', ['id' => $item->id]) }}">แก้ไขวาระ</a>
                                                <a style="margin-left: 10px" class="btn btn-primary ms-2"
                                                    onclick="addLectureDiv({{ $meeting_agenda->id }}, {{ $item->id }}, 'lectureContainer{{ $key }}')"
                                                    id="addLectureBtn($key)">เพิ่มหัวข้อย่อย</a>
                                            </div>
                                        </div>

                                        <div class="courseHide" id="lectureContainer{{ $key }}">
                                            {{-- <div class="container">
                                                @foreach ($item->meetingAgendaLectures as $lecture)
                                                    <div class="lectureDiv mb-3">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <strong
                                                                style="margin-left: 10px">{{ $lecture->lecture_title }}</strong>
                                                            <div class="btn-group">
                                                                <a class="btn btn-sm btn-primary me-2"
                                                                    onclick="addAgendaDiv({{ $meeting_agenda->id }}, {{ $item->id }}, {{ $lecture->id }}, 'agendaContainer{{ $lecture->id }}')">เพิ่มรายการ</a>
                                                                <a href="{{ route('edit.meeting.agenda.lecture', ['id' => $lecture->id]) }}"
                                                                    class="btn btn-sm btn-success"><i
                                                                        class="bx bx-edit"></i></a>
                                                                <a href="{{ route('delete.meeting.agenda.lecture', ['id' => $lecture->id]) }}"
                                                                    class="btn btn-sm btn-danger" id="delete"><i
                                                                        class="bx bx-trash"></i></a>
                                                            </div>
                                                        </div> --}}
                                                        <div class="container">
                                                            @foreach ($item->meetingAgendaLectures as $lecture)
                                                                <div class="lectureDiv mb-3">
                                                                    <div class="d-flex align-items-center justify-content-between">
                                                                        <div class="d-flex align-items-center">
                                                                            <strong style="margin-left: 10px">{{ $lecture->lecture_title }}</strong>
                                                                            <div class="form-check form-switch ms-3">
                                                                                <input class="form-check-input" type="checkbox"
                                                                                    id="showCommitteeOpinion{{ $lecture->id }}"
                                                                                    onchange="updateCommitteeOpinionVisibility({{ $lecture->id }})"
                                                                                    {{ $lecture->show_committee_opinion ? 'checked' : '' }}>
                                                                                <label class="form-check-label" for="showCommitteeOpinion{{ $lecture->id }}">
                                                                                    แสดงความเห็นคณะกรรมการ
                                                                                </label>
                                                                            </div>
                                                                        </div>
                                                                        <div class="btn-group">
                                                                            <a class="btn btn-sm btn-primary me-2"
                                                                                onclick="addAgendaDiv({{ $meeting_agenda->id }}, {{ $item->id }}, {{ $lecture->id }}, 'agendaContainer{{ $lecture->id }}')">เพิ่มรายการ</a>
                                                                            <a href="{{ route('edit.meeting.agenda.lecture', ['id' => $lecture->id]) }}"
                                                                                class="btn btn-sm btn-success"><i
                                                                                    class="bx bx-edit"></i></a>
                                                                            <a href="{{ route('delete.meeting.agenda.lecture', ['id' => $lecture->id]) }}"
                                                                                class="btn btn-sm btn-danger" id="delete"><i
                                                                                    class="bx bx-trash"></i></a>
                                                                        </div>
                                                                    </div>
                                                        <div id="agendaContainer{{ $lecture->id }}" class="mt-2">
                                                            <!-- Agenda items will be loaded here -->
                                                        </div>
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
                {{-- /// End Add Section and Lecture  --}}
            </div>
        </div>
    </div>



    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">เพิ่มวาระการประชุม</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('add.meeting.agenda.section') }}" method="POST">
                        @csrf
                        <input type="hidden" name="id" value="{{ $meeting_agenda->id }}">

                        <div class="form-group mb-3">
                            <label for="section_title" class="form-label">หัวข้อวาระการประชุม</label>
                            <input type="text" name="section_title" class="form-control" id="section_title" required>
                        </div>

                        <div class="form-group mb-3">
                            <label for="section_content" class="form-label">รายละเอียด</label>
                            <textarea name="section_content" id="section_content" class="form-control ckeditor"></textarea>
                        </div>

                        {{-- <div class="form-group mb-3">
                            <label for="section_content" class="form-label">รายละเอียด</label>
                            <textarea name="section_content" id="section_content" class="form-control"></textarea>
                        </div> --}}

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">ยกเลิก</button>
                            <button type="submit" class="btn btn-primary">บันทึก</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            CKEDITOR.replace('section_content', {
                language: 'th',
                height: 300,
                removeButtons: 'PasteFromWord'
            });
        });
    </script>

    @push('scripts')
    {{-- <script src="https://cdn.ckeditor.com/4.16.2/standard/ckeditor.js"></script> --}}
    {{-- <script src="https://cdn.ckeditor.com/4.25.0-lts/standard/ckeditor.js"></script> --}}
    <script src="{{ asset('backend/assets/plugins/ckeditor/ckeditor.js') }}"></script>

    <script>
        CKEDITOR.replace('section_content', {
            language: 'th',
            height: 300,
            removeButtons: 'PasteFromWord'
        });
    </script>
    @endpush

    <script>
        ClassicEditor
            .create(document.querySelector('#ckeditor'))
            .then(editor => {
                console.log(editor);
            })
            .catch(error => {
                console.error(error);
            });
    </script>

    <script>
        function addLectureDiv(courseId, sectionId, containerId) {
            const lectureContainer = document.getElementById(containerId);

            const newLectureDiv = document.createElement('div');
            newLectureDiv.classList.add('lectureDiv', 'mb-3');

            newLectureDiv.innerHTML = `
            <div class="container">
                <h6 class="mt-3"> ระเบียบวาระย่อย </h6>
                <input type="text" class="form-control" placeholder="Enter Lecture Title">
                <textarea id="editor-${containerId}" class="form-control"></textarea>

                <button class="btn btn-primary mt-3" onclick="SaveMeetingAgendaLecture('${courseId}',${sectionId},'${containerId}')" >Save Lecture</button>
                <button class="btn btn-secondary mt-3" onclick="hideLectureContainer('${containerId}')">Cancel</button>
            </div>
            `;

            lectureContainer.appendChild(newLectureDiv);

            // Initialize CKEditor
            CKEDITOR.replace(`editor-${containerId}`);

        }

        function hideLectureContainer(containerId) {
            const lectureContainer = document.getElementById(containerId);
            lectureContainer.style.display = 'none';
            location.reload();
        }
    </script>

    <script>
        function addAgendaDiv(courseId, lectureId, sectionId, containerId) {
            const agendaContainer = document.getElementById(containerId);

            const newAgendaDiv = document.createElement('div');
            newAgendaDiv.classList.add('agendaDiv', 'mb-3', 'ml-4');

            newAgendaDiv.innerHTML = `
            <div class="container">
                <h6>Meeting Agenda Item</h6>
                <input type="text" class="form-control mb-2" placeholder="Enter Agenda Title">
                <textarea id="editor-${containerId}" class="form-control" placeholder="Enter Agenda Description"></textarea>
                <button class="btn btn-primary mt-2" onclick="saveAgendaItem(${courseId}, ${lectureId}, ${sectionId}, '${containerId}')">Save Agenda Item</button>
                <button class="btn btn-secondary mt-2" onclick="hideAgendaContainer('${containerId}')">Cancel</button>
            </div>
            `;

            agendaContainer.appendChild(newAgendaDiv);

            // Initialize CKEditor
            CKEDITOR.replace(`editor-${containerId}`);
        }

        function hideAgendaContainer(containerId) {
            const agendaContainer = document.getElementById(containerId);
            agendaContainer.lastElementChild.remove();
        }

        // บันทึก agenda item ต่อมาจาก add agenda section
        function saveAgendaItem(courseId, sectionId, lectureId, containerId) {
            const agendaContainer = document.getElementById(containerId);
            const agendaTitle = agendaContainer.querySelector('input[type="text"]').value;
            const agendaDescription = CKEDITOR.instances[`editor-${containerId}`].getData();

            const formData = new FormData();
            formData.append('course_id', courseId);
            formData.append('section_id', sectionId);
            formData.append('lecture_id', lectureId);
            formData.append('item_title', agendaTitle);
            formData.append('content', agendaDescription);

            fetch('{{ route('save.meeting.agenda.item') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: formData,
                })
                .then(response => {
                    if (!response.ok) {
                        return response.text().then(text => {
                            throw new Error(text);
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    console.log(data);
                    hideAgendaContainer(containerId);
                    loadAgendaItems(lectureId, containerId);

                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: 'Agenda item added successfully',
                        showConfirmButton: false,
                        timer: 1500
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    const errorMessage = JSON.parse(error.message);
                    Swal.fire({
                        icon: 'error',
                        title: 'ขออภัย',
                        text: `เกิดข้อผิดพลาด: ${errorMessage.th} (${errorMessage.en})`,
                    });
                });
        }

        function loadAgendaItems(lectureId, containerId) {
            fetch(`/get-agenda-items/${lectureId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    const agendaContainer = document.getElementById(containerId);
                    agendaContainer.innerHTML = '';
                    if (data.length === 0) {
                        agendaContainer.innerHTML =
                        '<p class="text-sm text-muted ms-4 mb-0">No agenda items found.</p>';
                        return;
                    }
                    data.forEach((item, index) => {
                        const agendaItem = document.createElement('div');
                        agendaItem.classList.add('agendaItem', 'ml-4', 'mt-2');
                        agendaItem.style.marginLeft = '1rem';
                        agendaItem.innerHTML = `
                            <div class="d-flex justify-content-between align-items-center ml-4">
                                <p class="mb-0"><strong>${item.item_title}</strong></p>
                                <div class="btn-group" role="group">
                                    <button class="btn btn-sm btn-primary" onclick="editAgendaItem(${item.id})">แก้ไข</button>
                                    <button class="btn btn-sm btn-danger" onclick="deleteAgendaItem(${item.id}, '${containerId}')">ลบ</button>
                                </div>
                            </div>
                            <div class="text-sm text-muted ms-4 mb-0">
                                ${item.content ? item.content : 'ไม่มีการลงรายละเอียด'}
                            </div>
                            <hr>
                        `;
                        agendaContainer.appendChild(agendaItem);
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    const agendaContainer = document.getElementById(containerId);
                    agendaContainer.innerHTML =
                        `<p class="text-danger">Error loading agenda items: ${error.message}</p>`;
                });
        }

        function editAgendaItem(itemId) {
            fetch(`/edit/get-agenda-items/${itemId}`)
                .then(response => response.json())
                .then(item => {
                    Swal.fire({
                        title: 'Edit Agenda Item',
                        html:
                            `<input id="swal-input1" class="swal2-input" value="${item.item_title}" placeholder="Enter item title" style="width:100%">` +
                            `<textarea id="swal-input2" class="swal2-textarea">${item.content}</textarea>`,
                        width: '90%', // Make modal width 90% of screen
                        height: '90vh', // Set height to 90% of viewport height
                        customClass: {
                            popup: 'swal-wide',
                            content: 'swal-tall'
                        },
                        didOpen: () => {
                            CKEDITOR.replace('swal-input2', {
                                height: '60vh', // Make editor 60% of viewport height
                                // enterMode : CKEDITOR.ENTER_BR,
                                // toolbar : [
                                // ['Font', 'FontSize'], ['TextColor', 'BGColor'], ['Bold', 'Italic', 'Underline', 'Strike'], ['Subscript', 'Superscript'],
                                // ['JustifyLeft', 'JustifyRight', 'JustifyCenter', 'JustifyBlock'], ['NumberedList', 'BulletedList'], ['Outdent', 'Indent', 'Blockquote'], ['Table', 'HorizontalRule', 'SpecialChar'], ['Link', 'Unlink', 'Image'],
                                // ['Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord'], ['Undo', 'Redo', '-', 'Find', 'Replace', '-', 'SelectAll', 'RemoveFormat'], ['Source']
                                // ]
                            });
                        },
                        preConfirm: () => {
                            return {
                                item_title: document.getElementById('swal-input1').value,
                                content: CKEDITOR.instances['swal-input2'].getData()
                            }
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            updateAgendaItem(itemId, result.value.item_title, result.value.content);
                        }
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire('Error', 'Failed to load agenda item data', 'error');
                });
        }

        function updateAgendaItem(itemId, item_title, content) {
            fetch(`/update-agenda-item/${itemId}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ item_title: item_title, content: content })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    Swal.fire('Success', 'Agenda item updated successfully', 'success');
                    // Reload the agenda items
                    loadAgendaItems(data.meeting_agenda_lecture_id, `agendaContainer${data.meeting_agenda_lecture_id}`);
                } else {
                    Swal.fire('Error', data.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire('Error', 'Failed to update agenda item', 'error');
            });
        }

        function loadAllAgendaItems() {
            const lectureContainers = document.querySelectorAll('[id^="lectureContainer"]');
            lectureContainers.forEach(container => {
                const agendaContainers = container.querySelectorAll('[id^="agendaContainer"]');
                agendaContainers.forEach(agendaContainer => {
                    const lectureId = agendaContainer.id.replace('agendaContainer', '');
                    loadAgendaItems(lectureId, agendaContainer.id);
                });
            });
        }

        // Call loadAllAgendaItems when the page loads
        document.addEventListener('DOMContentLoaded', loadAllAgendaItems);

        function deleteAgendaItem(id, containerId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch(`/delete-agenda-item/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json',
                                'Content-Type': 'application/json'
                            },
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                loadAgendaItems(data.meeting_agenda_lecture_id, containerId);
                                Swal.fire(
                                    'Deleted!',
                                    'Agenda item has been deleted.',
                                    'success'
                                );
                            } else {
                                throw new Error(data.message || 'Failed to delete agenda item');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire(
                                'Error!',
                                'Failed to delete agenda item.',
                                'error'
                            );
                        });
                    }
                });
            }
    </script>

    <script>
        function SaveMeetingAgendaLecture(courseId, sectionId, containerId) {
            const lectureContainer = document.getElementById(containerId);
            const lectureTitle = lectureContainer.querySelector('input[type="text"]').value;
            // const lectureContent = lectureContainer.querySelector('textarea').value;

            // Get CKEditor content using the editor instance
            const lectureContent = CKEDITOR.instances[`editor-${containerId}`].getData();

            fetch('{{ route('save.meeting.agenda.lecture') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        meeting_agenda_id: courseId,
                        meeting_agenda_section_id: sectionId,
                        lecture_title: lectureTitle,
                        content: lectureContent,
                    }),
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    console.log(data);
                    lectureContainer.style.display = 'none';
                    location.reload();

                    const Toast = Swal.mixin({
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 10000
                    });

                    Toast.fire({
                        icon: 'success',
                        title: data.success,
                    });
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        text: 'Something went wrong!',
                    });
                });
        }

    </script>

    <script>
        function updateMeetingStatus(element) {
            const meetingId = element.dataset.meetingId;
            const status = element.checked ? 1 : 0;

            $.ajax({
                url: `/update-meeting-status/${meetingId}`,
                type: 'POST',
                data: {
                    status: status,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.success) {
                        toastr.success('อัพเดทสถานะสำเร็จ');
                        setTimeout(function() {
                            window.location.reload();
                        }, 1000);
                    } else {
                        toastr.error('เกิดข้อผิดพลาดในการอัพเดทสถานะ');
                        element.checked = !element.checked;
                    }
                },
                error: function() {
                    toastr.error('เกิดข้อผิดพลาดในการอัพเดทสถานะ');
                    element.checked = !element.checked;
                }
            });
        }
    </script>

<script>
    function updateCommitteeOpinionVisibility(lectureId) {
        const checkbox = document.getElementById(`showCommitteeOpinion${lectureId}`);

        // ส่ง AJAX request ไปอัพเดทค่าในฐานข้อมูล
        fetch(`/update-committee-opinion-visibility/${lectureId}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                show_committee_opinion: checkbox.checked
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // แสดง notification ว่าอัพเดทสำเร็จ
                toastr.success('อัพเดทการแสดงผลความคิดเห็นเรียบร้อยแล้ว');
            } else {
                toastr.error('เกิดข้อผิดพลาดในการอัพเดท');
                checkbox.checked = !checkbox.checked; // revert checkbox state
            }
        })
        .catch(error => {
            console.error('Error:', error);
            toastr.error('เกิดข้อผิดพลาดในการอัพเดท');
            checkbox.checked = !checkbox.checked; // revert checkbox state
        });
    }
</script>

@endsection
