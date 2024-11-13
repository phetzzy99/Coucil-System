@extends('admin.admin_dashboard')
@section('admin')

<div class="page-content">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header bg-primary text-white">
                        <h4 class="card-title mb-0">แก้ไขข้อมูลส่วนตัว</h4>
                    </div>
                    <div class="card-body p-4">
                        
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form method="post" action="{{ route('user.profile.update') }}" class="mt-4">
                            @csrf

                            <div class="mb-4 row align-items-center">
                                <label class="col-sm-3 col-form-label fw-bold fs-5">คำนำหน้า</label>
                                <div class="col-sm-4">
                                    <select name="prefix_name_id" class="form-select form-select-lg @error('prefix_name_id') is-invalid @enderror">
                                        @foreach($prefixNames as $prefix)
                                            <option value="{{ $prefix->id }}" 
                                                {{ old('prefix_name_id', $user->prefix_name_id) == $prefix->id ? 'selected' : '' }}>
                                                {{ $prefix->title }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('prefix_name_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4 row align-items-center">
                                <label class="col-sm-3 col-form-label fw-bold fs-5">ชื่อ</label>
                                <div class="col-sm-6">
                                    <input type="text" name="first_name" 
                                        class="form-control form-control-lg @error('first_name') is-invalid @enderror"
                                        value="{{ old('first_name', $user->first_name) }}">
                                    @error('first_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4 row align-items-center">
                                <label class="col-sm-3 col-form-label fw-bold fs-5">นามสกุล</label>
                                <div class="col-sm-6">
                                    <input type="text" name="last_name" 
                                        class="form-control form-control-lg @error('last_name') is-invalid @enderror"
                                        value="{{ old('last_name', $user->last_name) }}">
                                    @error('last_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4 row align-items-center">
                                <label class="col-sm-3 col-form-label fw-bold fs-5">Email</label>
                                <div class="col-sm-6">
                                    <input type="email" name="email" 
                                        class="form-control form-control-lg @error('email') is-invalid @enderror"
                                        value="{{ old('email', $user->email) }}">
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="mb-4 row align-items-center">
                                <label class="col-sm-3 col-form-label fw-bold fs-5">รหัสผ่านปัจจุบัน</label>
                                {{-- <label class="fs-10">(ปล่อยว่างถ้าไม่ต้องการเปลี่ยน)</label> --}}
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="password" name="current_password" placeholder="(ปล่อยว่างถ้าไม่ต้องการเปลี่ยน)"
                                            class="form-control form-control-lg @error('current_password') is-invalid @enderror">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('current_password')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @error('current_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4 row align-items-center">
                                <label class="col-sm-3 col-form-label fw-bold fs-5">รหัสผ่านใหม่</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="password" name="new_password" placeholder="(รหัสผ่าน ต้องไม่ต่ำกว่า 8 ตัวอักษร )"
                                            class="form-control form-control-lg @error('new_password') is-invalid @enderror">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                        @error('new_password')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="mb-4 row align-items-center">
                                <label class="col-sm-3 col-form-label fw-bold fs-5">ยืนยันรหัสผ่านใหม่</label>
                                <div class="col-sm-8">
                                    <div class="input-group">
                                        <input type="password" name="new_password_confirmation" placeholder="(รหัสผ่าน ต้องไม่ต่ำกว่า 8 ตัวอักษร )"
                                            class="form-control form-control-lg">
                                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('new_password_confirmation')">
                                            <i class="fas fa-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-4">
                                <div class="col-sm-9 offset-sm-3">
                                    <button type="submit" class="btn btn-primary btn-lg px-5">
                                        <i class="fas fa-save me-2"></i>บันทึกข้อมูล
                                    </button>
                                    <a href="{{ route('admin.dashboard') }}" class="btn btn-danger btn-lg ms-3">
                                        <i class="fas fa-times me-2"></i>ยกเลิก
                                    </a>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .card {
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0,0,0,0.1);
    }
    .card-header {
        border-radius: 15px 15px 0 0;
    }
    .form-control, .form-select {
        border-radius: 10px;
        border: 1px solid #dce7f1;
    }
    .form-control:focus, .form-select:focus {
        box-shadow: 0 0 0 0.25rem rgba(13,110,253,.15);
    }
    .btn {
        border-radius: 10px;
        padding: 10px 20px;
    }
</style>

<script>
    function togglePassword(inputName) {
        const input = document.querySelector(`input[name="${inputName}"]`);
        const icon = event.currentTarget.querySelector('i');
        
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }
</script>

@endsection
