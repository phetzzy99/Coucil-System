@extends('admin.admin_dashboard')

@section('admin')
<div class="page-content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">
                    <div class="text-center mb-4">
                        <div class="d-inline-flex align-items-center mb-3">
                            <i class="bx bx-data me-3 text-primary" style="font-size: 2.5rem;"></i>
                            <div>
                                <h4 class="mb-0">ฐานข้อมูลด้านกฎหมาย</h4>
                                <p class="mb-0 text-muted">มหาวิทยาลัยราชภัฏสุราษฎร์ธานี</p>
                            </div>
                        </div>
                    </div>
                    <hr>
                    <div class="card">
                        <div class="card-body">
                            <div class="ratio ratio-16x9">
                                <iframe src="https://centerlaw.sru.ac.th/" allowfullscreen></iframe>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
