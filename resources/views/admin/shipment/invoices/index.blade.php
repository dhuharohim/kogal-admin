@extends('layouts.admin')
@section('shipment')
    collapse
@endsection
@section('shipment_show')
    show
@endsection
@section('shipment_invoice')
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
@if ($user->role == 'warehouse')
    <script>
        window.location = "{{ url('/404') }}";
    </script>
@endif
@section('content')
    <div class="pagetitle d-flex justify-content-between">
        <div>
            <h1>Shipment Invoices </h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item"> <a href="{{ route('shipment.index') }}">Shipment</a> </li>
                    <li class="breadcrumb-item active">Shipment Invoices</li>
                </ol>
            </nav>
        </div>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <strong>Data Shipment Invoice</strong>
                <div class="align-self-center">
                    <a href="{{ route('shipment.invoices.create') }}" class="btn btn-outline-info"
                        style="font-size: 12px;">Create</a>
                </div>
            </div>
            <div class="card-body mt-3">
                <table id="data-invoice" class="table table-bordered table-striped table-hover dt-responsive"
                    style="width:100%">
                    <thead>
                        <tr>
                            <td>Invoice Number</td>
                            <td>Shipment Number</td>
                            <td>Shipment Number</td>
                            <td>Origin</td>
                            <td>Destination</td>
                            <td>Expected Delivery</td>
                            <td>Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invoices as $invoice)
                            <tr>
                                <td>
                                    <a href="{{ route('shipment.invoices.show', ['invoice_id' => $invoice->id]) }}" style="text-decoration: underline">
                                        {{ $invoice->invoice_number }}
                                    </a>
                                </td>
                                <td>
                                    #{{ $invoice->shipment_number }}
                                </td>
                                <td>{{ $invoice->type->type_of_shipments }}</td>
                                <td>{{ $invoice->origin->name_origin }}</td>
                                <td>{{ $invoice->destination->destination_name }}</td>
                                <td>{{ $invoice->expected_delivery_date_time }}</td>
                                <td>
                                    <button
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
        new DataTable('#data-invoice', {
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
