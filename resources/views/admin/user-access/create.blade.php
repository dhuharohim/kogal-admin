@extends('layouts.admin')
@if ($user->role !== 'admin')
    <script>
        window.location = "{{ url('/404') }}";
    </script>
@endif

@section('content')
    <div class="pagetitle d-flex justify-content-between items-center">
        <div>
            <h1>Create User</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item"> <a href="/user-access-management">User Access Management</a></li>
                    <li class="breadcrumb-item active">Create</li>
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
                                <input type="text" id="name" class="form-control" required>
                            </div>
                            <div class="form-group mt-2">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" id="password" class="form-control" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mt-2">
                                <label for="email" class="form-label">Email</label>
                                <input type="text" id="email" class="form-control" required>
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
                                <option value="shipment">Shipment Admin</option>
                                <option value="warehouse">Warehouse Admin</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-outline-info float-end" id="create">Create</button>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>
        $('#create').click(function() {
            if ($('#password').val() !== $('#password-confirmation').val()) {
                iziToast.error({
                    title: 'Error',
                    message: 'Password confirmation must match with the password',
                });

                return;
            }

            const dataToSend = {
                name: $('#name').val(),
                email: $('#email').val(),
                password: $('#password').val(),
                role: $('#role').val()
            };

            $.ajax({
                url: '/user-access-management/store', // Replace with your API endpoint
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
                            window.location.href = "{{ route('user-access-management.index') }}"
                        }, 1000);
                    }
                },
                error: function(error) {
                    console.log(error);
                }
            });
        });
    </script>
@endsection
