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
    </style>
@endsection

@if ($user->role == 'warehouse')
    <script>
        window.location = "{{ url('/404') }}";
    </script>
@endif

@section('content')
    <div class="pagetitle d-flex justify-content-between items-center">
        <div>
            <h1>Editing Shipment #{{ $shipment->shipment_number }}</h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item"> <a href="{{ route('shipment.index') }}">Shipment</a> </li>
                    <li class="breadcrumb-item active">#{{ $shipment->shipment_number }}</li>
                </ol>
            </nav>
            <div>
                <a href="/shipment/show/{{ $shipment->id }}" class="btn btn-outline-info">Back</a>
            </div>
        </div>
    </div><!-- End Page Title -->
    <section class="section">
        <div class="card">
            <div class="card-header">
                <strong>Select warehouse</strong>
            </div>
            <div class="card-body mt-2">
                <form action="{{ route('shipment.edit', ['id' => $shipment->id]) }}" method="GET">
                    @csrf
                    <select id="warehouse-select" name="warehouseId" class="form-control">
                        @foreach ($warehouses as $warehouseOpt)
                            <option value="{{ $warehouseOpt->id }}" @if ($warehouseOpt->id == $warehouse->id || $newWarehouse->id == $warehouseOpt->id) selected @endif>
                                {{ $warehouse->name_warehouse }} - {{ $warehouse->code_warehouse }}
                            </option>
                        @endforeach
                    </select>
                    <button class="btn btn-info mt-4" style="float: right;">Change</button>
                </form>
            </div>
        </div>
        {{-- Header 1 --}}
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <strong>Shipper Details</strong>
                    </div>
                    <div class="card-body mt-2">
                        <div class="form-group">
                            <label class="form-label">Shipper Name</label>
                            <input type="text" name="shipper_name" id="shipperName" class="form-control"
                                value="{{ $shipment->shipper_name }}">
                        </div>
                        <div class="form-group mt-4">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="shipper_phone" id="shipperPhone" class="form-control"
                                value="{{ $shipment->shipper_phone }}">
                        </div>
                        <div class="form-group mt-4">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="shipper_address" id="shipperAddress">{{ $shipment->shipper_address }}</textarea>
                        </div>
                        <div class="form-group mt-4">
                            <label class="form-label">Email</label>
                            <input type="email" name="shipper_email" id="shipperEmail" class="form-control"
                                value="{{ $shipment->shipper_email }}">
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
                            <input type="text" name="receiver_name" id="receiverName" class="form-control"
                                value="{{ $shipment->receiver_name }}">
                        </div>
                        <div class="form-group mt-4">
                            <label class="form-label">Phone Number</label>
                            <input type="text" name="receiver_phone" id="receiverPhone" class="form-control"
                                value="{{ $shipment->receiver_phone }}">
                        </div>
                        <div class="form-group mt-4">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" name="receiver_address" id="receiverAddress" value="{{ $shipment->receiver_address }}">{{ $shipment->receiver_address }}</textarea>
                        </div>
                        <div class="form-group mt-4">
                            <label class="form-label">Email</label>
                            <input type="email" name="receiver_email" id="receiverEmail" class="form-control"
                                value="{{ $shipment->receiver_email }}">
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
                            placeholder="Blank out this field generate automatically"
                            value="{{ $shipment->shipment_number }}">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mt-4">
                            {{-- <label class="form-label">Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="{{ $shipment->status }}" selected disabled> {{ $shipment->status }}
                                </option>
                                <option value="Collected">Collected</option>
                                <option value="In Transit">In Transit</option>
                                <option value="Out for Delivery">Out for Delivery</option>
                                <option value="Delivered">Delivered</option>
                                <option value="Delivery Failed">Delivery Failed</option>
                                <option value="Delayed">Delayed</option>
                                <option value="Return to Sender">Return to Sender</option>
                                <option value="Exception">Exception</option>
                                <option value="Completed">Completed</option>
                                <option value="Scheduled for Delivery">Scheduled for Delivery</option>
                            </select> --}}
                        </div>
                        <div class="form-group mt-4">
                            <label class="form-label">Type of Shipment</label>
                            <select name="shipment_type" id="shipmentType" class="form-control">
                                @foreach ($shipmentTypes as $shippingType)
                                    <option value="{{ $shippingType->id }}"
                                        @if ($shippingType->id == $shipment->type_of_shipment_id) selected @endif>
                                        {{ $shippingType->type_of_shipments }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-4">
                            <label class="form-label">Payment Mode</label>
                            <select name="payment_mode" id="paymentMode" class="form-control">
                                @foreach ($paymentModes as $paymentMode)
                                    <option value="{{ $paymentMode->id }}"
                                        @if ($paymentMode->id == $shipment->payment_id) selected @endif>
                                        {{ $paymentMode->name_payment_mode }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-4">
                            <label class="form-label">Carrier</label>
                            <select name="carrier" id="carrier" class="form-control">
                                @foreach ($carriers as $carrier)
                                    <option value="{{ $carrier->id }}" @if ($carrier->id == $shipment->carrier_id) selected @endif>
                                        {{ $carrier->carrier_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-4">
                            <label class="form-label">Destination</label>
                            <select name="destination" id="destination" class="form-control">
                                @foreach ($destinations as $destination)
                                    <option value="{{ $destination->id }}"
                                        @if ($destination->id == $shipment->destination_id) selected @endif>
                                        {{ $destination->destination_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-4">
                            <label class="form-label">Departure Time</label>
                            <input type="time" name="departure_time" id="departureTime"class="form-control"
                                value="{{ $shipment->departure_time }}">
                        </div>
                        <div class="form-group mt-4">
                            <label class="form-label">Courier</label>
                            <input type="text" name="courier" id="courier" class="form-control"
                                value="{{ $shipment->courier }}">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mt-4">
                            <label class="form-label">Mode</label>
                            <select name="mode" id="mode" class="form-control">
                                @foreach ($modes as $mode)
                                    <option value="{{ $mode->id }}" @if ($mode->id == $shipment->mode_id) selected @endif>
                                        {{ $mode->mode }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-4">
                            <label class="form-label">Total Freight</label>
                            <input type="number" name="total_freight" id="totalFreight" class="form-control"
                                value="{{ $shipment->total_freight }}">
                        </div>
                        <div class="form-group mt-4">
                            <label class="form-label">Carrier Reference No</label>
                            <input type="text" name="carrier_ref" id="carrierRef" class="form-control"
                                value="{{ $shipment->carrier_ref }}">
                        </div>
                        <div class="form-group mt-4">
                            <label class="form-label">Origin</label>
                            <select name="origin" id="origin" class="form-control">
                                @foreach ($origins as $origin)
                                    <option value="{{ $origin->id }}" @if ($origin->id == $shipment->origin_id) selected @endif>
                                        {{ $origin->name_origin }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group mt-4">
                            <label class="form-label">Pickup Date and Time</label>
                            <input type="datetime-local" name="pick_date_time" id="pickDateTime"class="form-control"
                                value="{{ $shipment->pickup_date_time }}">
                        </div>
                        <div class="form-group mt-4">
                            <label class="form-label">Expected Delivery Date and Time</label>
                            <input type="datetime-local" name="expected_delivery"
                                id="expectedDelivery"class="form-control"
                                value="{{ $shipment->expected_delivery_date_time }}">
                        </div>
                        <div class="form-group mt-4">
                            <label class="form-label">Remarks</label>
                            <textarea name="remarks" id="remarks" rows="2" class="form-control">{{ $shipment->remarks }}</textarea>
                        </div>
                    </div>
                </div>
                <input type="hidden" id="shipmentId" value="{{ $shipment->id }}">
            </div>
        </div>
        @if ($shipment->status == 'Draft' || $shipment->status == 'Warehouse Confirmation')
            <div class="card">
                {{-- shipment items --}}
                <div class="shipment-items">
                    <div class="card-header d-flex justify-content-between align-content-center">
                        <strong>Shipment Items</strong>
                        <div class="vat">
                            <input type="number" id="vat" class="form-control" placeholder="Input VAT (%)" value="{{ $shipment->vat }}">
                        </div>
                    </div>
                    <div class="card-body mt-2">
                        <form id="data-form">
                            <table id="shipment-items"
                                class="table table-bordered table-striped table-hover dt-responsive" style="width:100%">
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
                                    @foreach ($shipment->shipmentItems as $item)
                                        <tr class="item-row">
                                            <td>
                                                <select name="items[]" class="form-control item-select">
                                                    @if (!blank($newItems))
                                                        @foreach ($newItems as $newItem)
                                                            <option value="{{ $newItem->id }}"
                                                                @if ($newItem->id == $item->item_id) selected @endif>
                                                                {{ $newItem->item_name }}</option>
                                                        @endforeach
                                                    @else
                                                        @foreach ($warehouse->items as $warehouseItem)
                                                            <option value="{{ $warehouseItem->id }}"
                                                                @if ($warehouseItem->id == $item->item_id) selected @endif>
                                                                {{ $warehouseItem->item_name }}
                                                            </option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </td>
                                            <td><input name="description[]" type="text"
                                                    class="form-control description" value="{{ $item->description }}">
                                            </td>
                                            <td><input name="qty[]" type="text" class="form-control qty"
                                                    value="{{ $item->qty }}">
                                            </td>
                                            <td><input name="price[]" type="text" class="form-control price"
                                                    value="{{ $item->price }}">
                                            </td>
                                            <td><input name="length[]" type="text" class="form-control length number"
                                                    value="{{ $item->length }}" readonly>
                                            </td>
                                            <td><input name="width[]" type="text" class="form-control width number"
                                                    value="{{ $item->width }}" readonly>
                                            </td>
                                            <td><input name="height[]" type="text" class="form-control height number"
                                                    value="{{ $item->height }}" readonly>
                                            </td>
                                            <td><input name="weight[]" type="text" class="form-control weight number"
                                                    value="{{ $item->weight }}" readonly>
                                            </td>
                                            <td>
                                                <input name="total-weight" type="text"
                                                    class="form-control total-weight number"
                                                    value="{{ $item->item->weight * $item->qty }}" readonly>
                                            </td>
                                            <td>
                                                <input name="total-price" type="text"
                                                    class="form-control total-price number"
                                                    value="{{ $item->price * $item->qty }}" readonly>
                                            </td>
                                            <td>
                                                <button class="btn btn-danger delete-row">Delete</button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="9" style="text-align: right;">Total Weight</th>
                                        <th>
                                            <input type="text" id="subTotalWeight" class="form-control" readonly
                                                value="{{ number_format($shipment->total_actual_weight, 2) }}">
                                        </th>
                                        <th></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </form>
                        <button class="btn btn-outline-primary mt-2" id="add-row">Add row</button>
                    </div>
                </div>
            </div>
        @endif
        <button class="btn btn-outline-success" id="save-changes">Save Changes</button>
    </section>
@endsection

@section('js')
    <script>
        var selectedItems = [];
        new DataTable('#shipment-items', {
            responsive: true,
            fixedHeader: {
                header: true
            },
        });

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

        $('#add-row').click(function() {
            var newRow = $('#shipment-items tbody tr.item-row:last').clone();
            console.log(newRow.length);
            if (newRow.length > 0) {
                newRow.find('input').val('').removeAttr('disabled');
                newRow.find('.item-select').val('');
                newRow.find('.desc, .length, .width, .height, .weight, .total-weight').val('0.00');
            } else {
                newRow = `    <tr class="item-row">
                                        <td>
                                            <select name="items[]" class="form-control item-select">
                                                @if (!blank($newItems))
                                                    @foreach ($newItems as $newItem)
                                                        <option value="" selected disabled>Select item</option>
                                                        <option value="{{ $newItem->id }}"
                                                         >
                                                            {{ $newItem->item_name }}</option>
                                                    @endforeach
                                                @else
                                                    @foreach ($warehouse->items as $warehouseItem)
                                                    <option value="" selected disabled>Select item</option>

                                                        <option value="{{ $warehouseItem->id }}"
                                                           >
                                                            {{ $warehouseItem->item_name }}
                                                        </option>
                                                    @endforeach
                                                @endif
                                            </select>
                                        </td>
                                        <td><input name="description[]" type="text" class="form-control description"
                                                value=""></td>
                                        <td><input name="qty[]" type="text" class="form-control qty"
                                                value="">
                                        </td>
                                        <td><input name="price[]" type="text" class="form-control price"
                                                    value="{{ $item->price }}">
                                            </td>
                                        <td><input name="length[]" type="text" class="form-control length number"
                                                value="" readonly>
                                        </td>
                                        <td><input name="width[]" type="text" class="form-control width number"
                                                value="" readonly>
                                        </td>
                                        <td><input name="height[]" type="text" class="form-control height number"
                                                value="" readonly>
                                        </td>
                                        <td><input name="weight[]" type="text" class="form-control weight number"
                                                value="" readonly>
                                        </td>
                                        <td>
                                            <input name="total-weight" type="text"
                                                class="form-control total-weight number"
                                                value="" readonly>
                                        </td>
                                        <td>
                                                <input name="total-price" type="text"
                                                    class="form-control total-price number"
                                                    value="{{ $item->price * $item->qty }}" readonly>
                                            </td>
                                        <td>
                                            <button class="btn btn-danger delete-row">Delete</button>
                                        </td>
                                    </tr>`;
            }
            $('#shipment-items tbody').append(newRow);

        });

        // Fungsi untuk menghapus baris
        $(document).on('click', '.delete-row', function() {
            $(this).closest('tr').remove();
        });

        $('#shipment-items').on('change', '.item-select', function() {
            var selectedRow = $(this).closest('.item-row');
            var itemId = $(this).val();

            if (itemId !== '' && selectedItems.indexOf(itemId) === -1) {
                selectedItems.push(itemId);

                // Disable the selected option in other rows
                // $('.item-select option[value="' + itemId + '"]').not(this).prop('disabled', true);
                $.ajax({
                    url: '/item/' + itemId, // Replace with your API endpoint
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        selectedRow.find('.description').val(response.description);
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
                selectedRow.find('.desc, .length, .width, .height, .weight, .price').val('');
                selectedRow.find('.total-weight').val('0.00');
                selectedRow.find('.total-price').val('0.00');
            }
        });


        $('#save-changes').on('click', function(event) {
            var itemToSend = [];
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
                    var description = row.find('.description').val();

                if (!isNaN(qty) && !isNaN(totalWeight)) {
                    itemToSend.push({
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

            const shipmentId = $('#shipmentId').val();
            const warehouseId = $('#warehouse-select :selected').val();

            $.ajax({
                url: '/shipment/update/' + shipmentId,
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    shipment_id: shipmentId,
                    warehouse_id: warehouseId,
                    shipper_details: shipperDetails,
                    receiver_details: receiverDetails,
                    shipment_details: shipmentDetails,
                    shipment_items: itemToSend
                }, // Kirimkan data dalam format objek
                success: function(response) {
                    iziToast.success({
                        title: 'Success',
                        message: response.message
                    });
                    setTimeout(() => {
                        window.location.href = '/shipment/show/' + shipmentId;
                    }, 1500);
                },
                error: function(error) {
                    console.error(error);
                }
            });
        });
    </script>
@endsection
