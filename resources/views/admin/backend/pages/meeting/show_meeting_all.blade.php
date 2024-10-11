@extends('admin.admin_dashboard')
@section('admin')

<style>
    .pdf-like-document {
        background-color: white;
        padding: 40px;
        box-shadow: 0 0 10px rgba(0,0,0,0.1);
        font-family: Arial, sans-serif;
    }
    .pdf-like-document h1 {
        color: #333;
        border-bottom: 2px solid #333;
        padding-bottom: 10px;
        margin-bottom: 20px;
    }
    .pdf-like-document table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    .pdf-like-document th, .pdf-like-document td {
        border: 1px solid #ddd;
        padding: 12px;
        text-align: left;
    }
    .pdf-like-document th {
        background-color: #f2f2f2;
        font-weight: bold;
    }
    .pdf-like-document .meeting-info {
        margin-bottom: 15px;
    }
    .pdf-like-document .meeting-actions {
        margin-top: 10px;
    }
    .pdf-like-document .btn {
        text-decoration: none;
        padding: 5px 10px;
        margin-right: 5px;
        color: white;
        border-radius: 3px;
        font-size: 0.9em;
    }
    .pdf-like-document .btn-info { background-color: #17a2b8; }
    .pdf-like-document .btn-primary { background-color: #007bff; }
    .pdf-like-document .btn-danger { background-color: #dc3545; }
</style>

<div class="page-content">
    <div class="pdf-like-document">
        <h1>รายการการประชุมทั้งหมด</h1>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        @forelse($meetings as $key => $meeting)
            <div class="meeting-info">
                <h3>การประชุมครั้งที่ {{ $key + 1 }}</h3>
                <table>
                    <tr>
                        <th width="30%">ประเภทการประชุม</th>
                        <td>{{ $meeting->meetingType->name ?? 'ไม่ระบุ' }}</td>
                    </tr>
                    <tr>
                        <th>ระเบียบวาระการประชุม</th>
                        <td>
                            @if($meeting->meetingAgenda)
                                {{ $meeting->meetingAgenda->meeting_agenda_title }}<br>
                                ครั้งที่ {{ $meeting->meetingAgenda->meeting_agenda_number }}<br>
                                วันที่ {{ $meeting->meetingAgenda->meeting_agenda_date }} เวลา {{ $meeting->meetingAgenda->meeting_agenda_time }} น.
                            @else
                                ไม่มีข้อมูล
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>หมวดวาระการประชุม</th>
                        <td>{{ $meeting->meetingAgendaSection->section_title ?? 'ไม่ระบุ' }}</td>
                    </tr>
                    <tr>
                        <th>สถานะ</th>
                        <td>{{ $meeting->status == '1' ? 'ใช้งาน' : 'ไม่ใช้งาน' }}</td>
                    </tr>
                </table>
                <div class="meeting-actions">
                    <a href="{{ route('show.meeting', $meeting->id) }}" class="btn btn-info">รายละเอียด</a>
                    <a href="{{ route('edit.meeting', $meeting->id) }}" class="btn btn-primary">แก้ไข</a>
                    <form action="{{ route('delete.meeting', $meeting->id) }}" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger" onclick="return confirm('คุณแน่ใจหรือไม่ที่จะลบรายการนี้?')">ลบ</button>
                    </form>
                </div>
            </div>
        @empty
            <p>ไม่พบข้อมูลการประชุม</p>
        @endforelse

        <div class="mt-3">
            {{ $meetings->links() }}
        </div>

        <div class="mt-3">
            <a href="{{ route('add.meeting') }}" class="btn btn-primary">เพิ่มการประชุมใหม่</a>
        </div>
    </div>
</div>

@endsection
