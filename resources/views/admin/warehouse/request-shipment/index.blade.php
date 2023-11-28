@extends('layouts.admin')
@section('warehouse')
    collapse
@endsection
@section('warehouse_show')
    show
@endsection
@section('warehouse_request')
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
            <h1>Request Shipments</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item"><a href="{{ route('warehouse.index') }}">Warehouse</a> </li>
                    <li class="breadcrumb-item active">Request Shipments</li>
                </ol>
            </nav>
        </div>
    </div><!-- End Page Title -->

    <div class="card">
        <div class="card-header">
            <strong>Select warehouse</strong>
        </div>
        <div class="card-body mt-3">
            <div class="form-group">
                <form action="{{ route('warehouse.request-shipment.index') }}" method="GET">
                    @csrf
                    <select id="warehouse-select" name="warehouseId" class="form-control" style="font-size: 14px">
                        <option value="" disabled selected>Name - Code</option>
                        @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" @if (!blank($selectedWarehouse) && $selectedWarehouse['id'] == $warehouse->id) selected @endif>
                                {{ $warehouse->name_warehouse }} - {{ $warehouse->code_warehouse }}
                            </option>
                        @endforeach
                    </select>
                    <button class="btn btn-info mt-4" style="float: right;">Get list</button>
                </form>
            </div>
        </div>
    </div>
    @if (!blank($request_shipments) && count($request_shipments) > 0)
        <div class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <strong>Request Shipment on {{ $selectedWarehouse->name_warehouse }}</strong>
                </div>
                <div class="card-body mt-3">
                    <table id="data-request" class="table table-bordered table-striped table-hover dt-responsive"
                        style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Shipment Number</th>
                                <th>Pickup Date Time</th>
                                <th>Total Actual Weight</th>
                                <th>Total Volume</th>
                                <th>Remarks</th>
                                <th>Status</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @if (isset($request_shipments) && count($request_shipments) > 0)
                                @foreach ($request_shipments as $request)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $request->shipment_number }}</td>
                                        <td>{{ $request->pickup_date_time }}</td>
                                        <td>{{ $request->total_actual_weight }}</td>
                                        <td>{{ $request->total_vol }}</td>
                                        <td>{{ $request->remarks }}</td>
                                        <td>{{ $request->status }}</td>
                                        <td>
                                            <a href="{{ route('warehouse.request-shipment.show', ['shipment_number' => $request->shipment_number ?? '']) }}"
                                                class="btn btn-outline-primary">View</a>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="8">No request shipments available.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif
@endsection

@section('js')
    <script>
        new DataTable('#data-request', {
            responsive: true,
            fixedHeader: {
                header: true
            },
            dom: 'Bfrtip',
            buttons: [
                'csv', 'excel', 'pdf', 'print'
            ],
        });
    </script>
@endsection
