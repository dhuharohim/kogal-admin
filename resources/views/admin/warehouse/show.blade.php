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
        .table-title {
            font-weight: bold;
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

        .form-group {
            font-size: 12px;
            font-weight: bold;
        }

        .form-control {
            font-size: 12px;
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
            <input type="hidden" value="{{ $warehouse->id }}" name="" id="warehouseId">
            <h1>Warehouse</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item"><a href="{{ route('warehouse.index') }}">Warehouse</a> </li>
                    <li class="breadcrumb-item active">Show {{ $warehouse->name_warehouse }}</li>
                </ol>
            </nav>
        </div>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="card">
            <div class="card-header">
                <strong>Show Warehouse {{ $warehouse->name_warehouse }} - {{ $warehouse->code_warehouse }}</strong>
            </div>
            <div class="card-body mt-3">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Code</label>
                            <input type="text" name="code_warehouse" id="code_warehouse" class="form-control"
                                value="{{ $warehouse->code_warehouse }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Name</label>
                            <input type="text" name="name_warehouse" id="name_warehouse" class="form-control"
                                value="{{ $warehouse->name_warehouse }}">
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Street</label>
                            <input type="text" name="street" id="street" class="form-control"
                                value="{{ $warehouse->street }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">City</label>
                            <input type="text" name="city" id="city" class="form-control"
                                value="{{ $warehouse->city }}">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label class="form-label">Postal Code</label>
                            <input type="text" name="postal_code" id="postal_code" class="form-control"
                                value="{{ $warehouse->postal_code }}">
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Province</label>
                            <input type="text" name="province" id="province" class="form-control"
                                value="{{ $warehouse->province }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Capacity</label>
                            <input type="text" name="capacity" id="capacity" class="form-control"
                                value="{{ $warehouse->capacity }}">
                        </div>
                    </div>
                </div>
                <div class="row mt-3">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Email</label>
                            <input type="email" name="email_warehouse" id="email_warehouse" class="form-control"
                                value="{{ $warehouse->email_warehouse }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label class="form-label">Contact Number</label>
                            <input type="text" name="work_phone" id="work_phone" class="form-control"
                                value="{{ $warehouse->work_phone }}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer">
                <button onclick="saveChanges()" class="btn btn-outline-primary" style="font-size: 12px; float: right;">Save
                    changes</button>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>
        function saveChanges() {
            if (confirm('Are you sure you want to update this warehouse?')) {
                var warehouse = {
                    code_warehouse: $('#code_warehouse').val(),
                    name_warehouse: $('#name_warehouse').val(),
                    street: $('#street').val(),
                    city: $('#city').val(),
                    postal_code: $('#postal_code').val(),
                    email: $('#email_warehouse').val(),
                    work_phone: $('#work_phone').val(),
                    capacity: $('#capacity').val(),
                    province: $('#province').val(),
                }

                var warehouseId = $('#warehouseId').val();

                $.ajax({
                    url: '/warehouse/update/'+ warehouseId,
                    type: 'post',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        warehouse: warehouse
                    },
                    success: function(response) {
                        // Handle success response
                        console.log(response);
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
                                window.location.reload();
                            }, 1500 );
                        }
                    },
                    error: function(error) {
                        // Handle error response
                        console.log(error);
                    }
                })
            }
        }
        $(document).ready(function() {
        });
    </script>
@endsection
