@extends('layouts.admin')
@section('shipment')
    collapse
@endsection
@section('shipment_show')
    show
@endsection
@section('shipment_form_input')
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

        .btn {
            font-size: 12px;
        }
    </style>
@endsection
@section('content')
    <div class="pagetitle">
        <h1>Shipment - Form Input</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item">Home</li>
                <li class="breadcrumb-item">Shipment </li>
                <li class="breadcrumb-item active">Form Input</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="card">
            <div class="card-header">
                <strong>Shipment Details</strong>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-control mt-4">
                            <label class="form-label">Mode</label>
                            <select class="mode" name="modes[]" multiple="multiple">
                                @foreach ($modes as $mode)
                                    <option selected value="{{ $mode->mode }}">{{ $mode->mode }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-control mt-4">
                            <label class="form-label">Type of Shipment</label>
                            <select class="type" name="typeOfShipments[]" multiple="multiple">
                                @foreach ($typeOfShipments as $typeOfShipment)
                                    <option selected value="{{ $typeOfShipment->type_of_shipments }}">
                                        {{ $typeOfShipment->type_of_shipments }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-control mt-4">
                            <label class="form-label">Payment Mode</label>
                            <select class="payment" name="paymentModes[]" multiple="multiple">
                                @foreach ($paymentModes as $paymentMode)
                                    <option selected value="{{ $paymentMode->name_payment_mode }}">
                                        {{ $paymentMode->name_payment_mode }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-control mt-4">
                            <label class="form-label">Carrier</label>
                            <select class="carrier" name="carriers[]" multiple="multiple">
                                @foreach ($carriers as $carrier)
                                    <option selected value="{{ $carrier->carrier_name }}">{{ $carrier->carrier_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-control mt-4">
                            <label class="form-label">Origin</label>
                            <select class="origin" name="origins[]" multiple="multiple">
                                @foreach ($origins as $origin)
                                    <option selected value="{{ $origin->name_origin }}">{{ $origin->name_origin }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-control mt-4">
                            <label class="form-label">Destination</label>
                            <select class="destination" name="destinations[]" multiple="multiple">
                                @foreach ($destinations as $destination)
                                    <option selected value="{{ $destination->destination_name }}">
                                        {{ $destination->destination_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card-footer d-flex flex-end" style="align-self: flex-end">
                <button id="save" class="btn btn-outline-info" style="font-size: 12px">Save Changes</button>
            </div>
        </div>

        {{-- items table --}}
        {{-- <div class="card">
            <div class="card-header">
                <strong>Data Items</strong>
            </div>
            <div class="card-body mt-4">
                <form id="data-form">
                <table id="data-items" class="table table-bordered table-striped table-hover dt-responsive"
                    style="width:100%">
                    <thead>
                        <tr>
                            <td>Item name</td>
                            <td>Description</td>
                            <td>Length</td>
                            <td>Width</td>
                            <td>Height</td>
                            <td>Weight</td>
                            <td>Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td><input type="text" class="form-control item-name" value="{{ $item->item_name }}">
                                </td>
                                <td><input type="text" class="form-control description"
                                        value="{{ $item->description }}"></td>
                                <td><input type="text" class="form-control length number" value="{{ $item->length }}">
                                </td>
                                <td><input type="text" class="form-control width number" value="{{ $item->width }}">
                                </td>
                                <td><input type="text" class="form-control height number" value="{{ $item->height }}">
                                </td>
                                <td><input type="text" class="form-control weight number" value="{{ $item->weight }}">
                                </td>
                                <td>
                                    <button class="btn btn-danger delete-row">Delete</button>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7">
                                <button class="btn btn-primary add-row">Add Row</button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <button type="submit" class="btn btn-outline-primary mt-2">Save Item Changes</button>
                </form>
            </div>
        </div> --}}
    </section>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            new DataTable('#data-items', {
                responsive: true,
                fixedHeader: {
                    header: true
                },
                paging: false,
                bPaginate: false,
                searching: false,
                info: false,
                ordering: false

            });
            $('.mode').selectize({
                create: true
            });
            $('.type').selectize({
                create: true
            });
            $('.payment').selectize({
                create: true
            });
            $('.carrier').selectize({
                create: true
            });
            $('.origin').selectize({
                create: true
            });
            $('.destination').selectize({
                create: true
            });
            $('#save').click(function() {
                var selectedModes = $('.mode').val();
                var selectedTypes = $('.type').val();
                var selectedPayments = $('.payment').val();
                var selectedCarriers = $('.carrier').val();
                var selectedOrigins = $('.origin').val();
                var selectedDestinations = $('.destination').val();

                var data = {
                    modes: selectedModes,
                    types: selectedTypes,
                    payments: selectedPayments,
                    carriers: selectedCarriers,
                    origins: selectedOrigins,
                    destinations: selectedDestinations
                };

                $.ajax({
                    url: '/shipment/form-input/save-shipment',
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: data,
                    success: function(response) {
                        // Proses respons setelah berhasil menyimpan data
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
                                    "{{ route('shipment.form-input.index') }}"
                            }, 3000);
                        }
                    },
                    error: function(error) {
                        // Proses respons jika terjadi kesalahan
                        console.error(error);
                    }
                });
            });

            $('.add-row').click(function() {
                var newRow = `
            <tr>
                <td><input type="text" class="form-control item-name"></td>
                <td><input type="text" class="form-control description"></td>
                <td><input type="text" class="form-control length"></td>
                <td><input type="text" class="form-control width"></td>
                <td><input type="text" class="form-control height"></td>
                <td><input type="text" class="form-control weight"></td>
                <td>
                    <button class="btn btn-danger delete-row">Delete</button>
                </td>
            </tr>
        `;
                $('#data-items tbody').append(newRow);
            });

            // Fungsi untuk menghapus baris
            $(document).on('click', '.delete-row', function() {
                $(this).closest('tr').remove();
            });


            $('#data-form').submit(function(event) {
                event.preventDefault(); // Mencegah form submit normal

                var formData = []; // Array untuk menyimpan data

                $('#data-items tbody tr').each(function() {
                    var row = $(this);
                    var item = {
                        itemName: row.find('.item-name').val(),
                        description: row.find('.description').val(),
                        length: row.find('.length').val(),
                        width: row.find('.width').val(),
                        height: row.find('.height').val(),
                        weight: row.find('.weight').val()
                    };
                    formData.push(item);
                });

                $.ajax({
                    url: '/shipment/form-input/save-item-changes', // Ganti dengan URL tujuan Anda
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        data: formData
                    }, // Kirimkan data dalam format objek
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
                                    "{{ route('shipment.form-input.index') }}"
                            }, 3000);
                        }
                    },
                    error: function(error) {
                        console.error(error);
                    }
                });
            });
        });
    </script>
@endsection
