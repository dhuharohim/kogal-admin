@extends('layouts.admin')

@section('content')
    <div class="pagetitle d-flex justify-content-between items-center">
        <div>
            <h1>View User Access Management</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item"> <a href="/user-access-management">User Access Management</a></li>
                    <li class="breadcrumb-item active">View {{ $user->name }}</li>
                </ol>
            </nav>
        </div>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="container">
            <div class="card mt-2">
                <div class="card-header">
                    <strong>Create User with Role</strong>
                </div>
                <div class="card-body mt-4">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mt-2">
                                <label for="name" class="form-label">Name</label>
                                <input type="text" id="name" class="form-control" required
                                    value="{{ $user->name }}">
                            </div>
                            <div class="form-group mt-2">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" id="password" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mt-2">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" id="email" class="form-control" required
                                    value="{{ $user->email }}">
                            </div>
                            <div class="form-group mt-2">
                                <label for="password-confirmation" class="form-label">Password Confirmation</label>
                                <input type="password" id="password-confirmation" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group mt-2">
                            <label for="role">Role</label>
                            <select name="role" id="role" class="form-control" required>
                                <option value="shipment" @if ($user->role == 'shipment') selected @endif>Shipment Admin
                                </option>
                                <option value="warehouse" @if ($user->role == 'warehouse') selected @endif>Warehouse Admin
                                </option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-outline-info float-end" id="save">Save changes</button>
                </div>
            </div>
        </div>
    </section>

    <input type="hidden" value="{{ $user->id }}" id="userId">
@endsection

@section('js')
    <script>
        $('#save').on('click', function() {
            if (confirm('Are you sure you want to save changes to this user?')) {
                const dataToSend = {
                    name: $('#name').val(),
                    email: $('#email').val(),
                    password: $('#password').val(),
                    role: $('#role').val()
                };

                const userId = $('#userId').val();
                $.ajax({
                    url: '/user-access-management/update/' + userId, // Replace with your API endpoint
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        dataToSend: dataToSend
                    },
                    success: function(response) {
                        if (response.error) {
                            iziToast.error({
                                title: 'Error',
                                message: response.message,
                            });
                        } else {
                            iziToast.success({
                                title: 'Success',
                                message: response.message
                            });
                            setTimeout(() => {
                                window.location.href =
                                    "{{ route('user-access-management.index') }}"
                            }, 1000);
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }
        });
    </script>
@endsection
