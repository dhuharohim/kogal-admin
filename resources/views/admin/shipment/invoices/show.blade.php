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
@section('content')
    <style>
        p {
            margin: 0;
        }

        body {
            font-size: 14px;
        }
    </style>
    <div class="pagetitle d-flex justify-content-between">
        <div>
            <h1>View Invoice #{{ $invoice->invoice_number }} </h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item"> <a href="{{ route('shipment.invoices.index') }}">Invoices</a> </li>
                    <li class="breadcrumb-item active">View Invoice #{{ $invoice->invoice_number }}</li>
                </ol>
            </nav>
        </div>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="row">
            <div class="col-md-9">
                <div class="card">
                    <div class="card-body mt-4">
                        <div class="invoice-container">
                            <div class="header-section d-flex justify-content-between">
                                <div class="company">
                                    <a class="logo d-flex align-items-center" style="width: 100%; gap: 1rem;">
                                        <img src="{{ asset('assets/img/KJM.png') }}" alt="" width="100px"
                                            style="max-height: inherit;">
                                        <span class="d-none d-lg-block">Kogal Jaya Mandiri</span>
                                    </a>
                                    <div class="details mt-4" style="line-height: 180%">
                                        <p>{{ $invoice->shipper_address }}</p>
                                        <p>{{ $invoice->shipper_phone }}</p>
                                        <p>{{ $invoice->shipper_email }}</p>
                                    </div>
                                </div>
                                <div class="invoice" style="line-height: 180%">

                                    <strong>Invoice
                                        @if ($edit)
                                            <input type="text" id="invoice_number" value="{{ $invoice->invoice_number }}"
                                                class="form-control">
                                        @else
                                            #{{ $invoice->invoice_number }}
                                        @endif
                                    </strong>
                                    <p class="mt-2">Date Issues: {{ $invoice->created_at }}</p>
                                    <p>Date Due: {{ $invoice->expected_delivery_date_time }}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="address-section">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="invoice-to">
                                            <strong>Invoice To</strong>
                                            <div class="details mt-2" style="line-height: 180%;">
                                                <p>{{ $invoice->receiver_name }}</p>
                                                <p>{{ $invoice->receiver_address }}</p>
                                                <p>{{ $invoice->receiver_phone }}</p>
                                                <p>{{ $invoice->receiver_email }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="bill-to">
                                            <strong>Details</strong>
                                            <div class="details mt-2" style="line-height: 180%;">
                                                <p>Shipment Number: #{{ $invoice->shipment_number }}</p>
                                                <p>Shipment Type: {{ $invoice->type->type_of_shipments }}</p>
                                                <p>Carrier: {{ $invoice->carrier->carrier_name }}</p>
                                                <p>Mode: {{ $invoice->mode->mode }}</p>
                                                <p>Payment: {{ $invoice->payment->name_payment_mode }}</p>
                                                <p></p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="items-section">
                                <table class="table table-hover" style="font-size: 12px">
                                    <thead>
                                        <tr>
                                            <th scope="col">#</th>
                                            <th scope="col">Item Name</th>
                                            <th scope="col">Description</th>
                                            <th scope="col">Qty</th>
                                            <th scope="col">Price</th>
                                            <th scope="col">Length(cm)</th>
                                            <th scope="col">Width(cm)</th>
                                            <th scope="col">Height(cm)</th>
                                            <th scope="col">Weight(kg)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($invoice->invoiceItems as $item)
                                            <tr>
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                <td>{{ $item->item_name }}</td>
                                                <td>{{ $item->description }}</td>
                                                <td>{{ $item->qty }}</td>
                                                <td>{{ $item->price }}</td>
                                                <td>{{ number_format($item->length, 2) }}</td>
                                                <td>{{ number_format($item->width, 2) }}</td>
                                                <td>{{ number_format($item->height, 2) }}</td>
                                                <td>{{ number_format($item->weight, 2) }}</td>
                                                <td></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="subtotal-section">
                                <div class="row">
                                    <div class="col-6">
                                    </div>
                                    <div class="col-md-6">
                                        <div class="vertical-table">
                                            <table class="table table-bordered table-responsive" style="font-size: 12px;">
                                                <tbody>
                                                    <tr>
                                                        <th>Subotal Volumetric Weight(kg)</th>
                                                        <td>{{ number_format($invoice->total_vol_weight, 2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Subtotal Volume(cm³/kg)</th>
                                                        <td>{{ number_format($invoice->total_vol, 2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Subtotal Weight(kg)</th>
                                                        <td>{{ number_format($invoice->total_actual_weight, 2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>VAT(%)</th>
                                                        <td>{{ number_format($invoice->vat, 2) }}</td>
                                                    </tr>
                                                    <tr>
                                                        <th>Subtotal Price with VAT</th>
                                                        <td>{{ number_format($invoice->total_price_vat, 2) }}</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>
                            <div class="footer-section">
                                <div class="notes">
                                    <strong>Note:
                                        @if ($edit)
                                            <textarea id="note" class="form-control">{{ $invoice->remarks }}</textarea>
                                        @else
                                            <p style="font-weight: 500; font-style:italic;">{{ $invoice->remarks }}</p>
                                        @endif
                                    </strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card">
                    <div class="card-body mt-4">
                        <div class="wrapper-options text-center">
                            @if ($edit)
                                <a href="{{ route('shipment.invoices.show', ['invoice_id' => $invoice->id]) }}"
                                    class="btn btn-danger w-100">Cancel</a>
                                <br>
                                <button class="btn btn-outline-success w-100 mt-4" id="saveChanges">Save Changes</button>
                            @else
                                <a target="_blank"
                                    href="{{ route('shipment.invoices.print', ['invoice_id' => $invoice->id]) }}"
                                    class="btn btn-outline-secondary btn-block w-100" id="print">Print &
                                    Download</a>
                                <br>
                                <a href="{{ route('shipment.invoices.show', ['invoice_id' => $invoice->id, 'edit' => true]) }}"
                                    class="btn btn-outline-secondary mt-4 w-100">Edit</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" id="invoiceId" value="{{ $invoice->id }}">
    </section>
@endsection

@section('js')
    <script>
        $('#saveChanges').on('click', function(e) {
            if (confirm('Are you sure you want to save current changes?')) {
                const invoiceId = $('#invoiceId').val();
                $.ajax({
                    url: `/shipment/invoices/update/${invoiceId}`,
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        invoice_number: $('#invoice_number').val(),
                        remarks: $('#note').val()
                    },
                    success: function(response) {
                        iziToast.success({
                            title: 'Success',
                            message: response.message
                        });
                        setTimeout(() => {
                            window.location.href = '/shipment/invoices/' + invoiceId;
                        }, 1500);
                    },
                    error: function(error) {
                        console.log(error);
                    }
                })
            }
        });
    </script>
@endsection
