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

        th{
            font-size: 12px;
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
            <h1>Create Shipment </h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item"> <a href="{{ route('shipment.index') }}">Shipment</a> </li>
                    <li class="breadcrumb-item active">Create</li>
                </ol>
            </nav>
        </div>
    </div><!-- End Page Title -->
    @if (session('message'))
        <div class="alert alert-success">
            {{ session('message') }}
        </div>
    @endif
    <section class="section">
        <div class="card">
            <div class="card-header">
                <strong>Select warehouse</strong>
            </div>
            <div class="card-body mt-2">
                <form action="{{ route('shipment.create') }}" method="GET">
                    @csrf
                    <select id="warehouse-select" name="warehouseId" class="form-control">
                        <option value="" disabled selected>Name - Code</option>
                        @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" @if (!blank($selectedWarehouse) && $selectedWarehouse['id'] == $warehouse->id) selected @endif>
                                {{ $warehouse->name_warehouse }} {{ $warehouse->code_warehouse }}
                            </option>
                        @endforeach
                    </select>
                    <button class="btn btn-info mt-4" style="float: right;">Proceed</button>
                </form>
            </div>
        </div>
        {{-- Header 1 --}}
        @if (!blank($selectedWarehouse))
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <strong>Shipper Details</strong>
                        </div>
                        <div class="card-body mt-2">
                            <div class="form-group">
                                <label class="form-label">Shipper Name</label>
                                <input type="text" name="shipper_name" id="shipperName" class="form-control">
                            </div>
                            <div class="form-group mt-4">
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="shipper_phone" id="shipperPhone" class="form-control">
                            </div>
                            <div class="form-group mt-4">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" name="shipper_address" id="shipperAddress" cols="30" rows="2"></textarea>
                            </div>
                            <div class="form-group mt-4">
                                <label class="form-label">Email</label>
                                <input type="email" name="shipper_email" id="shipperEmail" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <strong>Receiver Details</strong>
                        </div>
                        <div class="card-body mt-2">
                            <div class="form-group">
                                <label class="form-label">Receiver Name</label>
                                <input type="text" name="receiver_name" id="receiverName" class="form-control">
                            </div>
                            <div class="form-group mt-4">
                                <label class="form-label">Phone Number</label>
                                <input type="text" name="receiver_phone" id="receiverPhone" class="form-control">
                            </div>
                            <div class="form-group mt-4">
                                <label class="form-label">Address</label>
                                <textarea class="form-control" name="receiver_address" id="receiverAddress" cols="30" rows="2"></textarea>
                            </div>
                            <div class="form-group mt-4">
                                <label class="form-label">Email</label>
                                <input type="email" name="receiver_email" id="receiverEmail" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Header 2 --}}
            <div class="card">
                <div class="card-header">
                    <strong>Shipment Details</strong>
                </div>
                <div class="card-body mt-2">
                    <div class="row">
                        <div class="col-md-12">
                            <label class="form-label">Shipment Number</label>
                            <input class="form-control" type="text" name="shipment_number" id="shipmentNumber"
                                placeholder="Blank out this field generate automatically">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mt-2">
                                <label class="form-label">Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="Warehouse Confirmation">Warehouse Confirmation</option>
                                    <option value="Draft">Draft</option>
                                </select>
                            </div>
                            <div class="form-group mt-4">
                                <label class="form-label">Type of Shipment</label>
                                <select name="shipment_type" id="shipmentType" class="form-control">
                                    @foreach ($shipmentTypes as $shippingType)
                                        <option value="{{ $shippingType->id }}">
                                            {{ $shippingType->type_of_shipments }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mt-4">
                                <label class="form-label">Payment Mode</label>
                                <select name="payment_mode" id="paymentMode" class="form-control">
                                    @foreach ($paymentModes as $paymentMode)
                                        <option value="{{ $paymentMode->id }}">
                                            {{ $paymentMode->name_payment_mode }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mt-4">
                                <label class="form-label">Carrier</label>
                                <select name="carrier" id="carrier" class="form-control">
                                    @foreach ($carriers as $carrier)
                                        <option value="{{ $carrier->id }}">{{ $carrier->carrier_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mt-4">
                                <label class="form-label">Destination</label>
                                <select name="destination" id="destination" class="form-control">
                                    @foreach ($destinations as $destination)
                                        <option value="{{ $destination->id }}">
                                            {{ $destination->destination_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mt-4">
                                <label class="form-label">Departure Time</label>
                                <input type="time" name="departure_time" id="departureTime"class="form-control">
                            </div>
                            <div class="form-group mt-4">
                                <label class="form-label">Courier</label>
                                <input type="text" name="courier" id="courier" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mt-2">
                                <label class="form-label">Mode</label>
                                <select name="mode" id="mode" class="form-control">
                                    @foreach ($modes as $mode)
                                        <option value="{{ $mode->id }}">{{ $mode->mode }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mt-4">
                                <label class="form-label">Total Freight</label>
                                <input type="number" name="total_freight" id="totalFreight" class="form-control">
                            </div>
                            <div class="form-group mt-4">
                                <label class="form-label">Carrier Reference No</label>
                                <input type="text" name="carrier_ref" id="carrierRef" class="form-control">
                            </div>
                            <div class="form-group mt-4">
                                <label class="form-label">Origin</label>
                                <select name="origin" id="origin" class="form-control">
                                    @foreach ($origins as $origin)
                                        <option value="{{ $origin->id }}">{{ $origin->name_origin }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group mt-4">
                                <label class="form-label">Pickup Date and Time</label>
                                <input type="datetime-local" name="pick_date_time" id="pickDateTime"class="form-control">
                            </div>
                            <div class="form-group mt-4">
                                <label class="form-label">Expected Delivery Date and Time</label>
                                <input type="datetime-local" name="expected_delivery"
                                    id="expectedDelivery"class="form-control">
                            </div>
                            <div class="form-group mt-4">
                                <label class="form-label">Remarks</label>
                                <textarea name="remarks" id="remarks" rows="2" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <div class="card">
                {{-- shipment items --}}
                <div class="shipment-items">
                    <div class="card-header d-flex justify-content-between align-content-center">
                        <strong>Shipment Items</strong>
                        <div class="vat">
                            <input type="number" id="vat" class="form-control" placeholder="Input VAT (%)">
                        </div>
                    </div>
                    <div class="card-body mt-2">
                        <table id="shipment-items" class="table table-bordered table-striped table-hover dt-responsive">
                            <thead>
                                <tr>
                                    <th>Item Name</th>
                                    <th>Description</th>
                                    <th>Qty</th>
                                    <th>Price</th>
                                    <th>Length(cm)</th>
                                    <th>Width(cm)</th>
                                    <th>Height(cm)</th>
                                    <th>Weight(kg)</th>
                                    <th>Weight * Qty(kg)</th>
                                    <th>Price * Qty</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- The first row -->
                                <tr class="item-row">
                                    <td width="20%">
                                        <select name="items[]" class="form-control item-select">
                                            <option value="" disabled selected>Select item</option>
                                            @foreach ($warehouse->items as $item)
                                                <option value="{{ $item->id }}">{{ $item->item_name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td width="20%">
                                        <input type="text" name="desc[]" class="form-control desc" readonly>
                                    </td>
                                    <td width="10%">
                                        <input type="text" name="qty[]" class="form-control qty">
                                    </td>
                                    <td width="10%">
                                        <input type="text" name="price[]" class="form-control price" id="price" value="0.00"
                                            readonly>
                                    </td>
                                    <td>
                                        <input type="text" name="length[]" class="form-control length" value="0.00"
                                            readonly>
                                    </td>
                                    <td>
                                        <input type="text" name="width[]" class="form-control width" value="0.00"
                                            readonly>
                                    </td>
                                    <td>
                                        <input type="text" name="height[]" class="form-control height" value="0.00"
                                            readonly>
                                    </td>
                                    <td>
                                        <input type="text" name="weight[]" class="form-control weight" value="0.00"
                                            readonly>
                                    </td>
                                    <td width="20%">
                                        <input type="text" name="total_weight[]" class="form-control total-weight"
                                            value="0.00" readonly>
                                    </td>
                                    <td width="20%">
                                        <input type="text" name="total_price[]" class="form-control total-price"
                                            value="0.00" readonly>
                                    </td>
                                    <td style="vertical-align: middle; font-size: 15px;">
                                        <button id="delete-row" class="btn btn-danger" style="padding: 1px 8px;">
                                            <i class='bx bxs-trash'></i>
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="8" style="text-align: right;">Total Weight and Price</th>
                                    <th>
                                        <input type="text" value="0.00" id="subTotalWeight" class="form-control"
                                            readonly>
                                    </th>
                                    <th>
                                        <input type="text" value="0.00" id="subTotalPrice" class="form-control"
                                            readonly>
                                    </th>
                                    <th></th>
                                </tr>
                            </tfoot>
                            <!-- Table footer -->
                        </table>
                        <button id="addItem" class="btn btn-info" style="font-size: 12px;">Add Item</button>
                    </div>
                </div>
            </div>
            <div style="align-self: center;">
                <button id="submitShipment" class="btn btn-success" style="font-size: 12px;">Save</button>
            </div>
            <input type="hidden" value="{{ $selectedWarehouse->id }}" id="selectedWarehouseId">
        @endif
    </section>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            var selectedItems = [];
            new DataTable('#shipment-items', {
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
            var selectedItems = []; // Array to store selected item IDs

            // Event delegation for item select change
            $('#shipment-items').on('change', '.item-select', function() {
                var selectedRow = $(this).closest('.item-row');
                var itemId = $(this).val();

                if (itemId !== '' && selectedItems.indexOf(itemId) === -1) {
                    selectedItems.push(itemId);
                    $.ajax({
                        url: '/item/' + itemId, // Replace with your API endpoint
                        type: 'GET',
                        dataType: 'json',
                        success: function(response) {
                            console.log(response.price);
                            selectedRow.find('.desc').val(response.description);
                            selectedRow.find('.length').val(response.length);
                            selectedRow.find('.width').val(response.width);
                            selectedRow.find('.height').val(response.height);
                            selectedRow.find('.weight').val(response.weight);
                            selectedRow.find('.qty').trigger('input');
                            selectedRow.find('.price').val(response.price);
                        },
                        error: function(error) {
                            console.log(error);
                        }
                    });
                } else {
                    $(this).val('');
                    selectedRow.find('.desc, .length, .width, .height, .weight').val('');
                    selectedRow.find('.total-weight').val('0.00');
                    selectedRow.find('.total-price').val('0.00');
                }

            });

            // Event listener for "Add Item" button click
            $('#addItem').click(function() {
                var newRow = $('#shipment-items tbody tr.item-row:last').clone();
                newRow.find('input').val('').removeAttr('disabled');
                newRow.find('.item-select').val('');
                newRow.find('.desc').val('');
                newRow.find('.length, .width, .height, .weight, .total-weight', '.price').val('0.00');
                $('#shipment-items tbody').append(newRow);

            });

            // Event listener for delete row button click
            $('#shipment-items').on('click', '#delete-row', function() {
                var rowToDelete = $(this).closest('.item-row');
                var itemId = rowToDelete.find('.item-select').val();

                // Re-enable the option that was selected in this row
                $('.item-select option[value="' + itemId + '"]').prop('disabled', false);

                // Remove the row
                rowToDelete.remove();

                // Update selectedItems array
                var itemIndex = selectedItems.indexOf(itemId);
                if (itemIndex !== -1) {
                    selectedItems.splice(itemIndex, 1);
                }
            });

            // Event listener for qty input change
            $('#shipment-items').on('input', '.qty', function() {
                var selectedRow = $(this).closest('.item-row');
                var qty = parseFloat($(this).val());
                var weight = parseFloat(selectedRow.find('.weight').val());
                var price = parseFloat(selectedRow.find('.price').val());

                if (!isNaN(qty) && !isNaN(weight)) {
                    var totalWeight = (qty * weight).toFixed(2);
                    var totalPrice = (qty * price).toFixed(2);
                    selectedRow.find('.total-weight').val(totalWeight);
                    selectedRow.find('.total-price').val(totalPrice);
                    updateSubTotal('weight');
                    updateSubTotal('price');
                } else {
                    selectedRow.find('.total-weight').val('0.00');
                    selectedRow.find('.total-price').val('0.00');
                    updateSubTotal('weight');
                    updateSubTotal('price');
                }
            });

            // Function to update sub total weight
            function updateSubTotal(type) {
                var subTotalWeight = 0;
                var subTotalPrice = 0;

                if (type == 'weight') {
                    $('.total-weight').each(function() {
                        var rowWeight = parseFloat($(this).val());
                        if (!isNaN(rowWeight)) {
                            subTotalWeight += rowWeight;
                        }
                    });

                    $('#subTotalWeight').val(subTotalWeight.toFixed(2));
                } else {
                    $('.total-price').each(function() {
                        var rowPrice = parseFloat($(this).val());
                        if (!isNaN(rowPrice)) {
                            subTotalPrice += rowPrice;
                        }
                    });

                    $('#subTotalPrice').val(subTotalPrice.toFixed(2));
                }
            }


            $('#submitShipment').click(function() {
                // Collect data from each row
                var dataToSend = [];
                $('.item-row').each(function() {
                    var row = $(this);
                    var itemId = row.find('.item-select :selected').val();
                    var itemName = row.find('.item-select :selected').text();
                    var qty = parseFloat(row.find('.qty').val());
                    var length = parseFloat(row.find('.length').val());
                    var width = parseFloat(row.find('.width').val());
                    var height = parseFloat(row.find('.height').val());
                    var weight = parseFloat(row.find('.weight').val());
                    var totalWeight = parseFloat(row.find('.total-weight').val());
                    var price = parseFloat(row.find('.price').val());
                    var totalPrice = parseFloat(row.find('.total-price').val());
                    var description = row.find('.desc').val();

                    if (!isNaN(qty) && !isNaN(totalWeight)) {
                        dataToSend.push({
                            item_id: itemId,
                            item_name: itemName,
                            description: description,
                            quantity: qty,
                            length: length,
                            width: width,
                            height: height,
                            weight: weight,
                            total_weight: totalWeight,
                            total_price: totalPrice,
                            price: price
                        });
                    }

                });
                var shipperDetails = {
                    'name': $('#shipperName').val(),
                    'phone': $('#shipperPhone').val(),
                    'email': $('#shipperEmail').val(),
                    'address': $('#shipperAddress').val()
                };

                var receiverDetails = {
                    'name': $('#receiverName').val(),
                    'phone': $('#receiverPhone').val(),
                    'email': $('#receiverEmail').val(),
                    'address': $('#receiverAddress').val()
                };

                var shipmentDetails = {
                    'status': $('#status').find(':selected').val(),
                    'type': $('#shipmentType').find(':selected').val(),
                    'payment': $('#paymentMode').find(':selected').val(),
                    'carrier': $('#carrier').find(':selected').val(),
                    'destination': $('#destination').find(':selected').val(),
                    'departure_time': $('#departureTime').val(),
                    'courier': $('#courier').val(),
                    'mode': $('#mode').find(':selected').val(),
                    'total_freight': $('#totalFreight').val(),
                    'carrier_ref': $('#carrierRef').val(),
                    'origin': $('#origin').find(':selected').val(),
                    'pick_up_date_time': $('#pickDateTime').val(),
                    'expected_delivery': $('#expectedDelivery').val(),
                    'remarks': $('#remarks').val(),
                    'shipment_number': $('#shipmentNumber').val(),
                    'vat': $('#vat').val(),
                }

                // Perform AJAX call to send data
                $.ajax({
                    url: '/shipment', // Replace with your API endpoint
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        warehouse_id: $('#selectedWarehouseId').val(),
                        shipper_details: shipperDetails,
                        receiver_details: receiverDetails,
                        shipment_details: shipmentDetails,
                        shipment_items: dataToSend
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
                                window.location.href = "{{ route('shipment.index') }}"
                            }, 3000);
                        }
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            });
        })
    </script>
@endsection
