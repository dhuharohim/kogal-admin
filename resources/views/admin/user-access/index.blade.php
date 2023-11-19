@extends('layouts.admin')

@section('user_access')
    collapsed
@endsection
@section('css')
    <style>
        .btn {
            font-size: 12px;
        }

        table.table-bordered.dataTable thead tr:first-child th,
        table.table-bordered.dataTable thead tr:first-child td {
            font-weight: bold;
        }

        table.table-bordered.dataTable {
            font-size: 12px;
        }

        div.dataTables_wrapper div.dataTables_length label {
            font-size: 12px;
        }

        div.dataTables_wrapper div.dataTables_filter label {
            font-size: 12px;
        }

        div.dataTables_wrapper div.dataTables_info {
            font-size: 12px;
        }

        .page-item .page-link {
            font-size: 12px;
        }

        div.dt-buttons {
            gap: 1rem;
        }

        .btn-group>.btn-group:not(:last-child)>.btn,
        .btn-group>.btn.dropdown-toggle-split:first-child,
        .btn-group>.btn:not(:last-child):not(.dropdown-toggle) {
            background: darkseagreen;
            border: none;
            border-top-right-radius: inherit;
            border-bottom-right-radius: inherit;
            font-size: 12px;
        }

        .btn-group>.btn-group:not(:first-child)>.btn,
        .btn-group>.btn:nth-child(n+3),
        .btn-group>:not(.btn-check)+.btn {
            background: orangered;
            border: none;
            border-top-left-radius: inherit;
            border-bottom-left-radius: inherit;
            font-size: 12px;

        }

        .dataTables_filter {
            float: right;
            margin-bottom: 1em;

            &:after {
                clear: both;
            }
        }

        .show-shipment:hover {
            text-decoration: underline;
        }
    </style>
@endsection

@section('content')
    <div class="pagetitle d-flex justify-content-between items-center">
        <div>
            <h1>User Access Management</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item active">User Access Management</li>
                </ol>
            </nav>
        </div>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <strong>User list</strong>
                <div class="align-self-center">
                    <a href="{{ route('user-access-management.create') }}" class="btn btn-outline-info"
                        style="font-size: 12px;">Create</a>
                </div>
            </div>
            <div class="card-body mt-3">
                <table id="data-user" class="table table-bordered table-striped table-hover dt-responsive"
                    style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data->name }}</td>
                                <td>{{ $data->email }}</td>
                                <td>{{ $data->role }}</td>
                                <td>
                                    <a href="{{ route('user-access-management.view', ['id'=> $data->id]) }}" class="btn btn-outline-info">View</a>
                                    <button class="btn btn-outline-danger"
                                        onclick="deleteUser({{ $data->id }})">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>
        new DataTable('#data-user', {
            responsive: true,
            fixedHeader: {
                header: true
            },
            dom: 'Bfrtip',
            buttons: [
                'csv', 'excel', 'pdf', 'print'
            ],

        });

        function deleteUser(id) {
            if (confirm('Are you sure you want to delete this user?')) {
                $.ajax({
                    url: '/user-access-management/delete/' + id, // Replace with your API endpoint
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
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
            }
        }
    </script>
@endsection
