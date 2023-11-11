@extends('layouts.admin')

@section('shipment')
    collapse
@endsection
@section('shipment_show')
    show
@endsection
@section('shipment_manage')
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

@section('content')
    <div class="pagetitle d-flex justify-content-between items-center">
        <div>
            <h1>Shipment</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item">Shipment</li>
                    <li class="breadcrumb-item active">Manage</li>
                </ol>
            </nav>
        </div>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <strong>Data Shipments</strong>
                <div class="align-self-center">
                    <a href="{{ route('shipment.create') }}" class="btn btn-outline-info" style="font-size: 12px;">Create</a>
                </div>
            </div>
            <div class="card-body mt-3">
                <table id="data-shipment" class="table table-bordered table-striped table-hover dt-responsive"
                    style="width:100%">
                    <thead>
                        <tr>
                            <td>Shipment Number</td>
                            <td>Type</td>
                            <td>Origin</td>
                            <td>Destination</td>
                            <td>Expected Delivery</td>
                            <td>Status</td>
                            <td>Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($shipments as $shipment)
                            <tr>
                                <td>
                                    <a class="show-shipment" href="{{ route('shipment.show', $shipment->id) }}">
                                        #{{ $shipment->shipment_number }}
                                    </a>

                                </td>
                                <td>{{ $shipment->type->type_of_shipments }}</td>
                                <td>{{ $shipment->origin->name_origin }}</td>
                                <td>{{ $shipment->destination->destination_name }}</td>
                                <td>{{ $shipment->expected_delivery_date_time }}</td>
                                <td>{{ $shipment->status }}</td>
                                <td>
                                    <button onclick="deleteShipment({{ $shipment->id }})"
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
        new DataTable('#data-shipment', {
            responsive: true,
            fixedHeader: {
                header: true
            },
            dom: 'Bfrtip',
            buttons: [
                'csv', 'excel', 'pdf', 'print'
            ],

        });

        function deleteShipment(shipmentId) {
            if (confirm('Are you sure you want to delete this shipment?')) {
                $.ajax({
                    url: '/shipment/delete/' + shipmentId, // Replace with your API endpoint
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
                                window.location.href = "{{ route('shipment.index') }}"
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
