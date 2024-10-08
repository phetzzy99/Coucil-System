@extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

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
                                    {{ 'ครั้งที่ ' . $meeting_agenda->meeting_agenda_number . ' ปี ' . $meeting_agenda->meeting_agenda_year . ' วันที่ ' . $meeting_agenda->meeting_agenda_date }}
                                </p>
                            </div>
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">Add Section</button>
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
                                                <form action="" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-danger px-2 ms-auto me-2">Delete
                                                        Section </button>
                                                </form>
                                                <a style="margin-left: 10px" class="btn btn-primary ms-2"
                                                    onclick="addLectureDiv({{ $meeting_agenda->id }}, {{ $item->id }}, 'lectureContainer{{ $key }}')"
                                                    id="addLectureBtn($key)">Add Agenda item</a>
                                            </div>
                                        </div>

                                        <div class="courseHide" id="lectureContainer{{ $key }}">
                                            <div class="container">
                                                @foreach ($item->meetingAgendaLectures as $lecture)
                                                    <div class="lectureDiv mb-3">
                                                        <div class="d-flex align-items-center justify-content-between">
                                                            <strong style="width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">{{ $lecture->lecture_title }}</strong>
                                                            <div class="btn-group">
                                                                <a class="btn btn-sm btn-primary me-2"
                                                                    onclick="addAgendaDiv({{ $meeting_agenda->id }}, {{ $item->id }}, {{ $lecture->id }}, 'agendaContainer{{ $lecture->id }}')">Add Item</a>
                                                                <a href="{{ route('edit.meeting.agenda.lecture', ['id' => $lecture->id]) }}"
                                                                    class="btn btn-sm btn-success"><i class="bx bx-edit"></i></a>
                                                                <a href="{{ route('delete.meeting.agenda.lecture', ['id' => $lecture->id]) }}"
                                                                    class="btn btn-sm btn-danger" id="delete"><i class="bx bx-trash"></i></a>
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
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Section </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form action="{{ route('add.meeting.agenda.section') }}" method="POST">
                        @csrf

                        <input type="hidden" name="id" value="{{ $meeting_agenda->id }}">

                        <div class="form-group mb-3">
                            <label for="input1" class="form-label">Meeting Agenda Section</label>
                            <input type="text" name="section_title" class="form-control" id="input1">
                        </div>


                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">บันทึก</button>
                </div>
                </form>

            </div>
        </div>
    </div>

    <script>
        function addLectureDiv(courseId, sectionId, containerId) {
            const lectureContainer = document.getElementById(containerId);

            const newLectureDiv = document.createElement('div');
            newLectureDiv.classList.add('lectureDiv', 'mb-3');

            newLectureDiv.innerHTML = `
            <div class="container">
                <h6 class="mt-3"> ระเบียบวาระย่อย </h6>
                <input type="text" class="form-control" placeholder="Enter Lecture Title">

                <button class="btn btn-primary mt-3" onclick="SaveMeetingAgendaLecture('${courseId}',${sectionId},'${containerId}')" >Save Lecture</button>
                <button class="btn btn-secondary mt-3" onclick="hideLectureContainer('${containerId}')">Cancel</button>
            </div>
            `;

            lectureContainer.appendChild(newLectureDiv);

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
                <h6>Agenda Item</h6>
                <input type="text" class="form-control mb-2" placeholder="Enter Agenda Title">
                <input type="file" class="form-control mb-2" placeholder="Upload PDF File">
                <button class="btn btn-primary mt-2" onclick="saveAgendaItem(${courseId}, ${lectureId}, ${sectionId}, '${containerId}')">Save Agenda Item</button>
                <button class="btn btn-secondary mt-2" onclick="hideAgendaContainer('${containerId}')">Cancel</button>
            </div>
            `;

            agendaContainer.appendChild(newAgendaDiv);
        }

        function hideAgendaContainer(containerId) {
            const agendaContainer = document.getElementById(containerId);
            agendaContainer.lastElementChild.remove();
        }

        function saveAgendaItem(courseId, sectionId, lectureId, containerId) {
            const agendaContainer = document.getElementById(containerId);
            const agendaTitle = agendaContainer.querySelector('input[type="text"]').value;
            const agendaFile = agendaContainer.querySelector('input[type="file"]').files[0];

            const formData = new FormData();
            formData.append('course_id', courseId);
            formData.append('section_id', sectionId);
            formData.append('lecture_id', lectureId);
            formData.append('agenda_title', agendaTitle);
            formData.append('pdf', agendaFile);

            fetch('{{ route('save.meeting.agenda.item') }}', {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: formData,
                })
                .then(response => response.json())
                .then(data => {
                    console.log(data);
                    hideAgendaContainer(containerId);
                    loadAgendaItems(lectureId, containerId);

                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'Agenda item added successfully',
                        showConfirmButton: false,
                        timer: 1500
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

        function loadAgendaItems(lectureId, containerId) {
            fetch(`/get-agenda-items/${lectureId}`)
                .then(response => response.json())
                .then(data => {
                    const agendaContainer = document.getElementById(containerId);
                    agendaContainer.innerHTML = '';
                    data.forEach((item, index) => {
                        const agendaItem = document.createElement('div');
                        agendaItem.classList.add('agendaItem', 'ml-4', 'mt-2');
                        agendaItem.innerHTML = `
                <div class="d-flex justify-content-between align-items-center">
                    <p class="mb-0">${index + 1}.<strong>${item.agenda_title}</strong></p>
                    <div class="btn-group" role="group">
                        <button class="btn btn-sm btn-primary" onclick="editAgendaItem(${item.id})">Edit</button>
                        <button class="btn btn-sm btn-danger" onclick="deleteAgendaItem(${item.id}, '${containerId}')">Delete</button>
                    </div>
                </div>
                ${item.pdf ? `<p class="text-sm text-muted mb-0"><img src="{{ asset('upload/logo/pdf.png') }}" alt="PDF" style="width: 16px; height: 16px;"> File: ${item.pdf}</p>` : ''}
            `;
                        agendaContainer.appendChild(agendaItem);
                    });
                })
                .catch(error => console.error('Error:', error));
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
                                loadAgendaItems(data.lecture_id, containerId);
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
                        timer: 3000
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
@endsection
