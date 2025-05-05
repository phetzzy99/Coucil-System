@extends('admin.admin_dashboard')
@section('admin')
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>

    @php
        use Carbon\Carbon;
        Carbon::setLocale('th');
        $meeting_date = Carbon::parse($lecture->meetingAgendaSection->meetingAgenda->meeting_agenda_date);

        $thai_days = ['อาทิตย์', 'จันทร์', 'อังคาร', 'พุธ', 'พฤหัสบดี', 'ศุกร์', 'เสาร์'];
        $thai_months = [
            1 => 'มกราคม', 2 => 'กุมภาพันธ์', 3 => 'มีนาคม', 4 => 'เมษายน',
            5 => 'พฤษภาคม', 6 => 'มิถุนายน', 7 => 'กรกฎาคม', 8 => 'สิงหาคม',
            9 => 'กันยายน', 10 => 'ตุลาคม', 11 => 'พฤศจิกายน', 12 => 'ธันวาคม'
        ];

        $thai_day = $thai_days[$meeting_date->dayOfWeek];
        $thai_month = $thai_months[$meeting_date->month];
        $thai_date = "วัน{$thai_day}ที่ " . $meeting_date->day . ' ' . $thai_month . ' พ.ศ. ' . ($meeting_date->year + 543);
    @endphp

    <style>
        .accessibility-controls {
            position: fixed;
            top: 100px;
            right: 20px;
            background: #fff;
            padding: 10px;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
            z-index: 1000;
            cursor: move;
            user-select: none;
        }

        .font-size-btn {
            padding: 5px 10px;
            margin: 0 2px;
            border: 1px solid #ddd;
            border-radius: 3px;
            background: #f8f9fa;
            cursor: pointer;
        }

        .font-size-btn:hover {
            background: #e9ecef;
        }

        .document-style {
            background-color: white;
            padding: 40px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            font-family: 'TH Sarabun PSK', sans-serif;
            font-size: 16px;
            line-height: 1.6;
        }

        .section-title {
            font-size: 20px;
            font-weight: bold;
            margin-top: 30px;
            margin-bottom: 15px;
        }

        .item-list {
            list-style-type: none;
            padding-left: 20px;
        }

        .ck-content table {
            border-collapse: collapse;
            margin: 15px 0;
            width: 100%;
        }

        .ck-content table td,
        .ck-content table th {
            border: 1px solid #ddd;
            padding: 8px;
        }
    </style>

    <div class="page-content">

        <!-- Add Accessibility Controls -->
        <div class="accessibility-controls" id="accessibilityControls">
            <div style="margin-bottom: 5px; padding-bottom: 5px; border-bottom: 1px solid #eee; cursor: move;" class="drag-handle">
                <i class="fas fa-grip-horizontal"></i>
            </div>
            <button class="font-size-btn" onclick="changeFontSize('decrease')" title="ลดขนาดตัวอักษร">
                <i class="fas fa-minus"></i> ก
            </button>
            <button class="font-size-btn" onclick="changeFontSize('reset')" title="คืนค่าขนาดตัวอักษร">
                <i class="fas fa-sync-alt"></i> ก
            </button>
            <button class="font-size-btn" onclick="changeFontSize('increase')" title="เพิ่มขนาดตัวอักษร">
                <i class="fas fa-plus"></i> ก
            </button>
        </div>

        <div class="container-fluid">
            <div class="row justify-content-center">
                <div class="col-md-10">
                    <div class="card">

                        <div class="text-center alert border-0 border-start border-5 border-primary alert-dismissible fade show py-2">
                            <ul class="nav nav-tabs card-header-tabs justify-content-center">
                                <li class="nav-item">
                                    <a class="nav-link active" href="#committee" data-bs-toggle="tab">
                                        <i class="fas fa-users me-2"></i>หมวด : {{ $lecture->meetingAgenda->committeeCategory->name ?? 'ไม่ระบุ' }}
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <div class="card-body document-style">
                            @if($lecture->meetingAgendaSection->meetingAgenda)
                                <div class="agenda-info text-center">
                                    <p><strong>{{ $lecture->meetingAgendaSection->meetingAgenda->meeting_agenda_title }}</strong></p>
                                    <p><strong>ครั้งที่:
                                        {{ $lecture->meetingAgendaSection->meetingAgenda->meeting_agenda_number }}/
                                        {{ $lecture->meetingAgendaSection->meetingAgenda->meeting_agenda_year }}</strong>
                                    </p>
                                    <p><strong>{{ $thai_date }} เวลา {{ $lecture->meetingAgendaSection->meetingAgenda->meeting_agenda_time }}น.</strong></p>
                                    <p><strong>{{ $lecture->meetingAgendaSection->meetingAgenda->meeting_location }}</strong></p>
                                </div>
                                <hr>
                            @endif

                            <div class="section-title">{{ $lecture->meetingAgendaSection->section_title }}</div>

                            <div class="lecture-title"><strong>{{ $lecture->lecture_title }}</strong></div>
                            @if($lecture->content)
                                <div style="margin-left: 2rem;" class="lecture-content ck-content">{!! $lecture->content !!}</div>
                            @endif

                            @if($lecture->meetingAgendaItems->count() > 0)
                                <ul class="item-list">
                                    @foreach($lecture->meetingAgendaItems as $item)
                                        <li>
                                            @if($item->item_title)
                                                <div class="item-title">{{ $item->item_title }}</div>
                                            @endif
                                            @if($item->content)
                                                <div style="margin-left: 2rem;" class="item-content ck-content">{!! $item->content !!}</div>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                            {{-- เพิ่มความเห็นคณะกรรมการ วันที่เพิ่ม 1/12/2024 --}}
                    {{-- @if($lecture->meetingAgendaSection->meetingAgenda->show_committee_opinion) --}}
                    @if($lecture->meetingAgendaSection->meetingAgenda->show_committee_opinion && $lecture->show_committee_opinion)
                            <div class="card mt-4">
                                <div class="card-header bg-primary text-white">
                                    {{-- <h5 class="mb-0">ความเห็นคณะกรรมการกลั่นกรอง</h5> --}}
                                    <h5 class="mb-0">{{ $lecture->meetingAgendaSection->meetingAgenda->committee_opinion_title }}</h5>
                                </div>
                                <!-- ส่วนแสดงผลคะแนนโหวต -->
                        <div class="card-body">
                            <div class="vote-summary mb-4">
                                <div class="row text-center">
                                    <div class="col-md-6">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h5 class="text-success">เห็นชอบ</h5>
                                                <h3 id="approve-count">{{ $committeeFeedbacks->where('vote_type', 'approve')->count() }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="card bg-light">
                                            <div class="card-body">
                                                <h5 class="text-danger">ไม่เห็นชอบ</h5>
                                                <h3 id="reject-count">{{ $committeeFeedbacks->where('vote_type', 'reject')->count() }}</h3>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                                    <!-- ฟอร์มสำหรับเพิ่มความเห็น -->
                                    <form action="{{ route('committee.feedback.store') }}" method="POST" class="committee-feedback-form mb-4">
                                        @csrf
                                        <input type="hidden" name="lecture_id" value="{{ $lecture->id }}">

                                        <div class="form-group">
                                            <label class="d-block">การพิจารณา:</label>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input feedback-status-radio" type="radio"
                                                    name="feedback_status" id="status_approve_{{ $lecture->id }}"
                                                    value="approve" required>
                                                <label class="form-check-label" for="status_approve_{{ $lecture->id }}">
                                                    เห็นชอบ
                                                </label>
                                            </div>
                                            <div class="form-check form-check-inline">
                                                <input class="form-check-input feedback-status-radio" type="radio"
                                                    name="feedback_status" id="status_reject_{{ $lecture->id }}"
                                                    value="reject">
                                                <label class="form-check-label" for="status_reject_{{ $lecture->id }}">
                                                    ไม่เห็นชอบ
                                                </label>
                                            </div>
                                        </div>

                                        <div id="feedback_content_section_{{ $lecture->id }}" class="form-group mt-3" style="display: none;">
                                            <label for="feedback_content_{{ $lecture->id }}">ความเห็น:</label>
                                            <textarea class="form-control" id="feedback_content_{{ $lecture->id }}"
                                                name="feedback_content" rows="3"></textarea>
                                        </div>

                                        <button type="submit" class="btn btn-primary mt-3">บันทึกความเห็น</button>
                                    </form>

                                    <!-- ส่วนแสดงความเห็นที่มีอยู่ -->
 <!-- ส่วนแสดงความเห็นที่มีอยู่ -->
 <div class="existing-feedback">
    <h6 class="border-bottom pb-2">ความเห็นที่ผ่านมาล่าสุด :</h6>
    <div id="feedback-list">
        @foreach ($committeeFeedbacks as $feedback)
            <div class="feedback-item card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="card-subtitle mb-2 text-muted">
                            {{ $feedback->user->prefixName->title }} {{ $feedback->user->first_name }} {{ $feedback->user->last_name }}
                            <small class="text-muted">
                                ({{ \Carbon\Carbon::parse($feedback->created_at)->locale('th')->isoFormat('D/M/YYYY HH:mm') }})
                            </small>
                        </h6>
                        <span class="badge {{ $feedback->vote_type === 'approve' ? 'bg-success' : 'bg-danger' }}">
                            {{ $feedback->vote_type === 'approve' ? 'เห็นชอบ' : 'ไม่เห็นชอบ' }}
                        </span>
                    </div>
                    @if($feedback->opinion)
                        <p class="card-text mt-2">{{ $feedback->opinion }}</p>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>
                                </div>
                            </div>
                            @else
                            <div class="alert alert-info mt-4">
                                <i class="fas fa-info-circle"></i> การแสดงผลการลงความเห็นถูกปิดโดยผู้จัดการประชุม
                            </div>
                            @endif
                            {{-- เพิ่มความเห็นคณะกรรมการ วันที่เพิ่ม 1/12/2024 --}}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // ฟังก์ชันสำหรับทำให้ Accessibility Controls เคลื่อนย้ายได้
        document.addEventListener('DOMContentLoaded', function() {
            const controls = document.getElementById('accessibilityControls');
            let isDragging = false;
            let currentX;
            let currentY;
            let initialX;
            let initialY;
            let xOffset = 0;
            let yOffset = 0;

            // บันทึกตำแหน่งใน localStorage
            const savedPosition = localStorage.getItem('accessibilityControlsPosition');
            if (savedPosition) {
                const { x, y } = JSON.parse(savedPosition);
                controls.style.transform = `translate(${x}px, ${y}px)`;
                xOffset = x;
                yOffset = y;
            }

            function dragStart(e) {
                if (e.type === "touchstart") {
                    initialX = e.touches[0].clientX - xOffset;
                    initialY = e.touches[0].clientY - yOffset;
                } else {
                    initialX = e.clientX - xOffset;
                    initialY = e.clientY - yOffset;
                }

                if (e.target.closest('.drag-handle')) {
                    isDragging = true;
                }
            }

            function dragEnd(e) {
                initialX = currentX;
                initialY = currentY;
                isDragging = false;

                // บันทึกตำแหน่งเมื่อลากเสร็จ
                localStorage.setItem('accessibilityControlsPosition', JSON.stringify({
                    x: xOffset,
                    y: yOffset
                }));
            }

            function drag(e) {
                if (isDragging) {
                    e.preventDefault();

                    if (e.type === "touchmove") {
                        currentX = e.touches[0].clientX - initialX;
                        currentY = e.touches[0].clientY - initialY;
                    } else {
                        currentX = e.clientX - initialX;
                        currentY = e.clientY - initialY;
                    }

                    xOffset = currentX;
                    yOffset = currentY;

                    controls.style.transform = `translate(${currentX}px, ${currentY}px)`;
                }
            }

            // เพิ่ม Event Listeners
            controls.addEventListener('touchstart', dragStart, false);
            controls.addEventListener('touchend', dragEnd, false);
            controls.addEventListener('touchmove', drag, false);
            controls.addEventListener('mousedown', dragStart, false);
            controls.addEventListener('mouseup', dragEnd, false);
            controls.addEventListener('mousemove', drag, false);
            controls.addEventListener('mouseleave', dragEnd, false);
        });


        // ฟังก์ชันสำหรับเปลี่ยนขนาดตัวอักษร
        function changeFontSize(action) {
            const content = document.querySelector('.document-style');
            const currentSize = parseFloat(window.getComputedStyle(content).fontSize);

            let newSize;
            switch(action) {
                case 'increase':
                    newSize = currentSize * 1.1; // เพิ่ม 10%
                    break;
                case 'decrease':
                    newSize = currentSize * 0.9; // ลด 10%
                    break;
                case 'reset':
                    newSize = 16; // ขนาดเริ่มต้น
                    break;
            }

            // กำหนดขอบเขตขนาดตัวอักษร
            newSize = Math.min(Math.max(newSize, 12), 24); // ขนาดต่ำสุด 12px, สูงสุด 24px
            content.style.fontSize = `${newSize}px`;

            // บันทึกค่าไว้ใน localStorage
            localStorage.setItem('preferredFontSize', newSize);
        }

        // โหลดขนาดตัวอักษรที่ผู้ใช้ตั้งค่าไว้
        document.addEventListener('DOMContentLoaded', function() {
            const savedSize = localStorage.getItem('preferredFontSize');
            if (savedSize) {
                document.querySelector('.document-style').style.fontSize = `${savedSize}px`;
            }
        });
    </script>

    {{-- เพิ่มความเห็นคณะกรรมการ วันที่เพิ่ม 1/12/2024 --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const lectureId = '{{ $lecture->id }}';
            const radioButtons = document.querySelectorAll('.feedback-status-radio');
            const feedbackContentSection = document.getElementById(`feedback_content_section_${lectureId}`);
            const feedbackContent = document.getElementById(`feedback_content_${lectureId}`);
            const existingFeedbackDiv = document.querySelector('.existing-feedback');

            // ฟังก์ชันสำหรับสร้าง HTML ของความเห็นใหม่
            function createFeedbackHTML(feedback) {
                const date = new Date(feedback.created_at).toLocaleString('th-TH', {
                    day: '2-digit',
                    month: '2-digit',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit'
                });

                // สร้างชื่อผู้ใช้แบบเต็ม
                const fullName = feedback.user ?
                    `${feedback.user.prefix_name.title || ''} ${feedback.user.first_name || ''} ${feedback.user.last_name || ''}`.trim() :
                    'ไม่ระบุชื่อ';

                return `
                    <div class="feedback-item card mb-3">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="card-subtitle mb-2 text-muted">
                                    ${fullName}
                                    <small class="text-muted">(${date})</small>
                                </h6>
                                <span class="badge ${feedback.vote_type === 'approve' ? 'bg-success' : 'bg-danger'}">
                                    ${feedback.vote_type === 'approve' ? 'เห็นชอบ' : 'ไม่เห็นชอบ'}
                                </span>
                            </div>
                            ${feedback.opinion ? `<p class="card-text mt-2">${feedback.opinion}</p>` : ''}
                        </div>
                    </div>
                `;
            }

            // ฟังก์ชันสำหรับโหลดและแสดงความเห็นทั้งหมด
            function loadAndDisplayFeedbacks() {
                fetch(`/committee/feedback/${lectureId}`)
                    .then(response => response.json())
                    .then(feedbacks => {
                        const feedbacksHTML = feedbacks.length > 0
                            ? feedbacks.map(createFeedbackHTML).join('')
                            : '<p class="text-muted">ยังไม่มีความเห็นจากคณะกรรมการ</p>';

                        const feedbackContainer = existingFeedbackDiv.querySelector('div') || existingFeedbackDiv;
                        feedbackContainer.innerHTML = `

                            ${feedbacksHTML}
                        `;
                    })
                    .catch(error => {
                        console.error('Error loading feedbacks:', error);
                    });
            }

            // โหลดความเห็นที่มีอยู่แล้วของผู้ใช้
            fetch(`/committee/feedback/${lectureId}/current`, {
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data) {
                    const radio = document.querySelector(`input[name="feedback_status"][value="${data.vote_type}"]`);
                    if (radio) {
                        radio.checked = true;
                        if (data.vote_type === 'reject') {
                            feedbackContentSection.style.display = 'block';
                            feedbackContent.required = true;
                            feedbackContent.value = data.opinion || '';
                        }
                    }
                }
            })
            .catch(error => {
                console.error('Error loading current user feedback:', error);
            });

            // จัดการการแสดง/ซ่อนช่องความเห็น
            radioButtons.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'reject') {
                        feedbackContentSection.style.display = 'block';
                        feedbackContent.required = true;
                    } else {
                        feedbackContentSection.style.display = 'none';
                        feedbackContent.required = false;
                        feedbackContent.value = '';
                    }
                });
            });

            // จัดการการส่งฟอร์ม
            // const feedbackForm = document.querySelector('.committee-feedback-form');
            // if (feedbackForm) {
            //     feedbackForm.addEventListener('submit', function(e) {
            //         e.preventDefault();

            //         const formData = new FormData(this);

            //         fetch(this.action, {
            //             method: 'POST',
            //             body: formData,
            //             headers: {
            //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            //                 'Accept': 'application/json'
            //             }
            //         })
            //         .then(response => response.json())
            //         .then(data => {
            //             if (data.success) {
            //                 const message = data.is_update ? 'อัพเดทความเห็นเรียบร้อยแล้ว' : 'บันทึกความเห็นเรียบร้อยแล้ว';
            //                 Swal.fire({
            //                     icon: 'success',
            //                     title: 'สำเร็จ',
            //                     text: message,
            //                     showConfirmButton: false,
            //                     timer: 1500
            //                 });
            //                 loadAndDisplayFeedbacks();
            //             } else {
            //                 Swal.fire({
            //                     icon: 'error',
            //                     title: 'เกิดข้อผิดพลาด',
            //                     text: data.message || 'กรุณาลองใหม่อีกครั้ง'
            //                 });
            //             }
            //         })
            //         .catch(error => {
            //             console.error('Error:', error);
            //             Swal.fire({
            //                 icon: 'error',
            //                 title: 'เกิดข้อผิดพลาด',
            //                 text: 'กรุณาลองใหม่อีกครั้ง'
            //             });
            //         });
            //     });
            // }

            // โหลดความเห็นทั้งหมดเมื่อโหลดหน้า
            loadAndDisplayFeedbacks();
        });
    </script>
    {{-- เพิ่มความเห็นคณะกรรมการ วันที่เพิ่ม 1/12/2024 --}}

    {{-- วันที่เพิ่ม 1/12/2024  --}}
    {{-- @section('scripts') --}}
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    <script>
        // Pusher configuration
        const pusher = new Pusher('{{ env('PUSHER_APP_KEY') }}', {
            cluster: '{{ env('PUSHER_APP_CLUSTER') }}',
            encrypted: true
        });

        // Subscribe to the channel
        const channel = pusher.subscribe('lecture-votes.{{ $lecture->id }}');

        // Listen for vote updates
        channel.bind('vote-updated', function(data) {
            // Update vote counts
            document.getElementById('approve-count').textContent = data.approve_count;
            document.getElementById('reject-count').textContent = data.reject_count;

            // Add new feedback if it exists
            if (data.new_feedback) {
                const feedbackHtml = createFeedbackHtml(data.new_feedback);
                const feedbackList = document.getElementById('feedback-list');
                feedbackList.insertAdjacentHTML('afterbegin', feedbackHtml);
            }
        });

        // Function to create feedback HTML
        function createFeedbackHtml(feedback) {
            const date = new Date(feedback.created_at).toLocaleString('th-TH', {
                day: '2-digit',
                month: '2-digit',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });

            return `
                <div class="feedback-item card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="card-subtitle mb-2 text-muted">
                                ${feedback.user.prefix_name.title} ${feedback.user.first_name} ${feedback.user.last_name}
                                <small class="text-muted">(${date})</small>
                            </h6>
                            <span class="badge ${feedback.vote_type === 'approve' ? 'bg-success' : 'bg-danger'}">
                                ${feedback.vote_type === 'approve' ? 'เห็นชอบ' : 'ไม่เห็นชอบ'}
                            </span>
                        </div>
                        ${feedback.opinion ? `<p class="card-text mt-2">${feedback.opinion}</p>` : ''}
                    </div>
                </div>
            `;
        }

        // Form submission handling
        document.querySelector('.committee-feedback-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const submitButton = this.querySelector('button[type="submit"]');

            // Disable submit button
            submitButton.disabled = true;

            fetch(this.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Clear form
                    this.reset();
                    document.getElementById('feedback_content_section_{{ $lecture->id }}').style.display = 'none';

                    // Show success message
                    Swal.fire({
                        icon: 'success',
                        title: 'สำเร็จ',
                        text: 'บันทึกความเห็นเรียบร้อยแล้ว',
                        timer: 1500
                    });
                }
                    // รีโหลดหน้าเพื่อแสดงความเห็นใหม่
                    location.reload();
            })
            .finally(() => {
                // Re-enable submit button
                submitButton.disabled = false;
            });
        });
    </script>
    {{-- @endsection --}}

@endsection
