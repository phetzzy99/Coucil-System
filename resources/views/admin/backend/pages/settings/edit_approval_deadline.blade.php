@extends('admin.admin_dashboard')

@section('admin')
<div class="container-fluid py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">ตั้งค่าจำนวนวันสำหรับ Deadline การรับรอง</h5>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('settings.update_approval_deadline') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="deadline_days" class="form-label">จำนวนวันสำหรับ Deadline:</label>
                            <input type="number" class="form-control @error('deadline_days') is-invalid @enderror"
                                   id="deadline_days" name="deadline_days" value="{{ old('deadline_days', $deadlineDays) }}"
                                   min="1" max="30" required>
                            @error('deadline_days')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">กำหนดจำนวนวันหลังจากวันประชุมที่จะใช้เป็น deadline สำหรับการรับรอง (1-30 วัน)</small>
                        </div>
                        <button type="submit" class="btn btn-primary">บันทึกการเปลี่ยนแปลง</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
