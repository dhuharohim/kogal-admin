@extends('layouts.admin')

@section('warehouse')
    collapse
@endsection
@section('warehouse_show')
    show
@endsection
@section('warehouse_manage')
    active
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

@if ($user->role == 'shipment')
    <script>
        window.location = "{{ url('/404') }}";
    </script>
@endif

@section('content')
    <div class="pagetitle d-flex justify-content-between items-center">
        <div>
            <h1>Warehouse</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item">Warehouse</li>
                    <li class="breadcrumb-item active">Manage</li>
                </ol>
            </nav>
        </div>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <strong>Warehouse list</strong>
                <div class="align-self-center">
                    <a href="{{ route('warehouse.create') }}" class="btn btn-outline-info"
                        style="font-size: 12px;">Create</a>
                </div>
            </div>
            <div class="card-body mt-3">
                <table id="data-warehouse" class="table table-bordered table-striped table-hover dt-responsive"
                    style="width:100%">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Code Warehouse</th>
                            <th>Name Warehouse</th>
                            <th>Province</th>
                            <th>Capacity</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($warehouses as $warehouse)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>
                                    <a href="{{ route('warehouse.show', $warehouse->id) }}"">
                                        {{ $warehouse->code_warehouse }}
                                    </a>
                                </td>
                                <td>{{ $warehouse->name_warehouse }}</td>
                                <td>{{ $warehouse->province }}</td>
                                <td>{{ $warehouse->capacity }}</td>
                                <td>
                                    <button onclick="deleteWarehouse({{ $warehouse->id }})"
                                        class="btn btn-outline-danger">Delete</button>
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
        new DataTable('#data-warehouse', {
            responsive: true,
            fixedHeader: {
                header: true
            },
            dom: 'Bfrtip',
            buttons: [
                'csv', 'excel', 'pdf', 'print'
            ],

        });

        function deleteWarehouse(warehouseId) {
            if (confirm('Are you sure you want to delete this warehouse?')) {
                $.ajax({
                    url: '/warehouse/delete/' + warehouseId, // Replace with your API endpoint
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
                                window.location.href = "{{ route('warehouse.index') }}"
                            }, 3000);
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
