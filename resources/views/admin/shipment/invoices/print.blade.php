<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice - {{ $invoice->invoice_number }}</title>
    <link href="{{ asset('assets/vendor/bootstrap/css/bootstrap.min.css ') }}" rel="stylesheet">
    <link href="{{ asset('assets/vendor/simple-datatables/style.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet">
    <style>
        p {
            margin: 0;
        }

        @media print {
            margin: 1rem;
        }

        @page {
            size: auto;
            margin: 5mm;
        }
    </style>
</head>

<body>
    <div class="invoice-container">
        <div class="header-section d-flex justify-content-between">
            <div class="company">
                <a class="logo d-flex align-items-center" style="width: 100%; gap: 1rem;">
                    <img src="{{ asset('assets/img/KJM.png') }}" alt="" width="100px"
                        style="max-height: inherit;">
                    <span class="d-none d-lg-block">Kogal Jaya Mandiri</span>
                </a>
                <div class="details mt-4">
                    <p>{{ $invoice->shipper_address }}</p>
                    <p>{{ $invoice->shipper_phone }}</p>
                    <p>{{ $invoice->shipper_email }}</p>
                </div>
            </div>
            <div class="invoice">
                <strong>Invoice #{{ $invoice->invoice_number }}</strong>
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
                        <div class="details mt-2">
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
                        <div class="details mt-2">
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
                <strong>Note: <p style="font-weight: 500; font-style:italic;">{{ $invoice->remarks }}</p></strong>
            </div>
        </div>
    </div>
    <script src="{{ asset('assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.js') }}"></script>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>

</body>

</html>
