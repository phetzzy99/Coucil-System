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
                        <li class="breadcrumb-item active" aria-current="page">Add User</li>
                    </ol>
                </nav>
            </div>

        </div>
        <!--end breadcrumb-->

        <div class="card">
            <div class="card-body p-4">
                <h5 class="mb-4">Add User</h5>
                <form id="myForm" action="{{ route('store.admin') }}" method="post" class="row g-3"
                    enctype="multipart/form-data">
                    @csrf

                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label"> Title</label>
                        <select name="title" class="form-control" required>
                            <option value="Mr.">Mr.</option>
                            <option value="Mrs.">Mrs.</option>
                            <option value="Ms.">Ms.</option>
                            <option value="Dr.">Dr.</option>
                            <option value="Prof.">Prof.</option>
                        </select>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label"> Firstname</label>
                        <input type="text" name="first_name" class="form-control" id="input1" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label"> Lastname</label>
                        <input type="text" name="last_name" class="form-control" id="input1" required>
                    </div>


                    {{-- <div class="form-group col-md-6">
                        <label for="input1" class="form-label"> User Name</label>
                        <input type="text" name="username" class="form-control" id="input1" required>
                    </div> --}}

                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label"> Phone</label>
                        <input type="text" name="phone" class="form-control" id="input1" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label"> Email</label>
                        <input type="email" name="email" class="form-control" id="input1" required>
                        <span class="text-danger" id="emailError"></span>
                    </div>

                    {{-- <div class="form-group col-md-6">
                        <label for="input1" class="form-label"> Address</label>
                        <input type="text" name="address" class="form-control" id="input1">
                    </div> --}}

                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label"> Password</label>
                        <input type="password" name="password" class="form-control" id="input1" required>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="input1" class="form-label"> Role Name</label>
                        <select name="roles" class="form-select mb-3" aria-label="Default select example" required>
                            <option selected="" disabled>Open this select menu</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}"> {{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    {{-- @section('script')
                        <script>
                            $(document).ready(function() {
                                $('#myForm').submit(function(e) {
                                    e.preventDefault();
                                    var email = $('input[name=email]').val();
                                    $.ajax({
                                        url: "{{ route('check.email') }}",
                                        type: "POST",
                                        data: {
                                            "_token": "{{ csrf_token() }}",
                                            email: email
                                        },
                                        success: function(response) {
                                            if (response == 'unique') {
                                                $('#emailError').text('');
                                                $('#myForm').submit();
                                            } else {
                                                $('#emailError').text('Email already exists');
                                            }
                                        }
                                    });
                                });
                            });
                        </script>
                    @endsection --}}


                    <div class="col-md-12">
                        <div class="d-md-flex d-grid align-items-center gap-3">
                            <button type="submit" class="btn btn-primary px-4">Save Changes</button>
                            <a href="{{ route('all.admin') }}" class="btn btn-danger px-4">Back</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>




    </div>
@endsection
