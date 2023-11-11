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

        .form-control {
            font-size: 12px;
        }
    </style>
@endsection

@section('content')
    <div class="pagetitle d-flex justify-content-between items-center">
        <div>
            <div class="d-flex items-center" style="align-items: center; gap: 1rem">
                <h1>Showing Shipment #{{ $shipment->shipment_number }}</h1>
                @php
                    $statusIcons = [
                        'Draft' => 'bx bxs-cabinet',
                        'Confirmed' => 'bx bxs-badge-check',
                        'On Hold' => 'bx bxs-traffic-barrier',
                        'Warehouse Confirmation' => 'bx bxs-store',
                        'Picked Up' => 'bx bxs-truck',
                        'In Transit' => 'bx bxs-bus',
                        'Delivered' => 'bx bxs-check-circle',
                        'On Delivery' => 'bx bx-trip',
                        'Rejected' => 'bx bxs-x-circle',
                        'Enroute' => 'bx bxs-directions',
                        'Cancelled' => 'bx bx-task-x',
                        'Returned' => 'bx bx-undo',
                        'Out of Delivery' => 'bx bxs-package',
                    ];
                @endphp
                <span
                    class="badge 
            @if ($shipment->status == 'Draft' || $shipment->status == 'On Hold' || $shipment->status == 'Warehouse Confirmation') bg-warning text-dark
            @elseif($shipment->status == 'Picked Up' || $shipment->status == 'In Transit' || $shipment->status == 'Confirmed')
            bg-info text-white
            @elseif($shipment->status == 'Delivered' || $shipment->status == 'On Delivery')
            bg-primary text-white
            @elseif(
                $shipment->status == 'Rejected' ||
                    $shipment->status == 'Enroute' ||
                    $shipment->status == 'Cancelled' ||
                    $shipment->status == 'Returned' ||
                    $shipment->status == 'Out of Delivery')
            bg-danger text-white @endif
            "><i
                        class="{{ $statusIcons[$shipment->status] }}"></i> {{ $shipment->status }}</span>
            </div>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item"> <a href="{{ route('shipment.index') }}">Shipment</a> </li>
                    <li class="breadcrumb-item active">#{{ $shipment->shipment_number }}</li>
                </ol>
            </nav>
        </div>
        @if ($shipment->status !== 'Delivered' || $shipment->status !== 'Returned')
            <div>
                @if ($shipment->status == 'Draft')
                    <button class="btn btn-outline-warning" id="publish">Publish</button>
                @endif
                @if($shipment->status == 'Draft' || $shipment->status == 'Warehouse Confirmation')
                <a href="/shipment/edit/{{ $shipment->id }}" class="btn btn-success">Edit</a>
                @endif
            </div>
        @endif
    </div><!-- End Page Title -->
    <section class="section">
        {{-- Header 1 --}}
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <strong>Shipper Details</strong>
                    </div>
                    <div class="card-body mt-2">
                        <table class="table table-borderless table-responsive-md" style="font-size: 14px;">
                            <tr>
                                <td class="table-title">Shipper Name</td>
                                <td>: {{ $shipment->shipper_name }}</td>
                            </tr>
                            <tr>
                                <td class="table-title">Phone Number</td>
                                <td>: {{ $shipment->shipper_phone }}</td>
                            </tr>
                            <tr>
                                <td class="table-title">Address</td>
                                <td>: {{ $shipment->shipper_address }}</td>
                            </tr>
                            <tr>
                                <td class="table-title">Email</td>
                                <td>: {{ $shipment->shipper_email }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <strong>Receiver Details</strong>
                    </div>
                    <div class="card-body mt-2">
                        <table class="table table-borderless table-responsive-md" style="font-size: 14px;">
                            <tr>
                                <td class="table-title">Receiver Name</td>
                                <td>: {{ $shipment->receiver_name }}</td>
                            </tr>
                            <tr>
                                <td class="table-title">Phone Number</td>
                                <td>: {{ $shipment->receiver_phone }}</td>
                            </tr>
                            <tr>
                                <td class="table-title">Address</td>
                                <td>: {{ $shipment->receiver_address }}</td>
                            </tr>
                            <tr>
                                <td class="table-title">Email</td>
                                <td>: {{ $shipment->receiver_email }}</td>
                            </tr>
                        </table>
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
                    <div class="col-md-6">
                        <table class="table table-borderless table-responsive-md" style="font-size: 14px;">
                            <tr>
                                <td class="table-title">Type of Shipment</td>
                                <td>: {{ $shipment->type->type_of_shipments }}</td>
                            </tr>
                            <tr>
                                <td class="table-title">Payment Mode</td>
                                <td>: {{ $shipment->payment->name_payment_mode }}</td>
                            </tr>
                            <tr>
                                <td class="table-title">Carrier</td>
                                <td>: {{ $shipment->carrier->carrier_name }}</td>
                            </tr>
                            <tr>
                                <td class="table-title">Departure Time</td>
                                <td>: {{ $shipment->departure_time }}</td>
                            </tr>
                            <tr>
                                <td class="table-title">Destination</td>
                                <td>: {{ $shipment->destination->destination_name }}</td>
                            </tr>
                            <tr>
                                <td class="table-title">Courier</td>
                                <td>: {{ $shipment->courier }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-borderless table-responsive-md" style="font-size: 14px;">
                            <tr>
                                <td class="table-title">Mode</td>
                                <td>: {{ $shipment->mode->mode }}</td>
                            </tr>
                            <tr>
                                <td class="table-title">Total Freight</td>
                                <td>: {{ number_format($shipment->total_freight, 2) }}</td>
                            </tr>
                            <tr>
                                <td class="table-title">Carrier Reference No</td>
                                <td>: {{ $shipment->carrier_ref }}</td>
                            </tr>
                            <tr>
                                <td class="table-title">Origin</td>
                                <td>: {{ $shipment->origin->name_origin }}</td>
                            </tr>
                            <tr>
                                <td class="table-title">Pickup Date and Time</td>
                                <td>: {{ $shipment->pickup_date_time }}</td>
                            </tr>
                            <tr>
                                <td class="table-title">Expected Delivery Date and Time</td>
                                <td>: {{ $shipment->expected_delivery_date_time }}</td>
                            </tr>
                        </table>
                    </div>
                </div>
                <div class="row mt-2">
                    <div class="col-md-12 text-left d-flex" style="font-size: 12px;">
                        <label class="form-label"
                            style="    font-style: italic;
                        font-weight: bold;">Remarks: </label>
                        <span class="px-2"></span>
                        <div class="remark-content">
                            {{ $shipment->remarks }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            {{-- shipment items --}}
            <div class="shipment-items">
                <div class="card-header">
                    <strong>Shipment Items</strong>
                </div>
                <div class="card-body mt-2">
                    <table id="shipment-items" class="table table-bordered table-striped table-hover dt-responsive">
                        <thead>
                            <tr>
                                <td>
                                    Item Name
                                </td>
                                <td>
                                    Qty
                                </td>
                                <td>
                                    Price
                                </td>
                                <td>
                                    Description
                                </td>
                                <td>
                                    Length(cm)
                                </td>
                                <td>
                                    Width(cm)
                                </td>
                                <td>
                                    Height(cm)
                                </td>
                                <td>
                                    Weight(kg)
                                </td>
                                <td>
                                    Total Weight(kg)
                                </td>
                                <td>
                                    Total Price
                                </td>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($shipment->shipmentItems as $item)
                                <tr>
                                    <td>{{ $item->item_name }}</td>
                                    <td>{{ $item->qty }}</td>
                                    <td>{{ $item->price }}</td>
                                    <td>{{ $item->description }}</td>
                                    <td>{{ $item->length }}</td>
                                    <td>{{ $item->width }}</td>
                                    <td>{{ $item->height }}</td>
                                    <td>{{ $item->weight }}</td>
                                    <td>{{ $item->total_weight }}</td>
                                    <td>{{ $item->total_price }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="card-footer">
                    <table class="table table-bordered table-responsive" style="    
                    font-size: 12px;">
                        <thead>
                            <tr>
                                <th>Subotal Volumetric Weight(kg)</th>
                                <th>Subtotal Volume(cm³/kg)</th>
                                <th>Subtotal Weight(kg)</th>
                                <th>VAT</th>
                                <th>Subtotal Price with VAT</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{ number_format($shipment->total_vol_weight, 2) }}</td>
                                <td>{{ number_format($shipment->total_vol, 2) }}</td>
                                <td>{{ number_format($shipment->total_actual_weight, 2) }}</td>
                                <td>{{ number_format($shipment->vat, 2) }}</td>
                                <td>{{ number_format($shipment->total_price_vat, 2) }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        {{-- shipment histories --}}

        @if ($shipment->is_shipment_histories)
            <div class="card">
                <div class="card-header">
                    <strong>Shipment History</strong>
                </div>
                <div class="card-body mt-4">
                    <table id="shipment-history" class="table table-bordered table-striped table-hover dt-responsive">
                        <thead>
                            <tr>
                                <th>Date</th>
                                <th>Time</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Updated By</th>
                                <th>Remarks</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($shipment->shipmentHistories as $history)
                                @php
                                    $editable = true;
                                    if ($history->status == 'Confirmed' || $history->status == 'Picked Up') {
                                        $editable = false;
                                    }
                                    $statusHistories = ['Confirmed', 'Picked Up', 'On Hold', 'Out of Delivery', 'In Transit', 'On Delivery', 'Enroute', 'Cancelled', 'Delivered', 'Returned'];
                                @endphp
                                <tr data-history-id="{{ $history->id }}">
                                    <td>
                                        <input type="date" id="date-{{ $history->id }}" value="{{ $history->date }}"
                                            class="form-control date-input" @if (!$editable) disabled @endif>
                                    </td>
                                    <td>
                                        <input type="time" id="time-{{ $history->id }}" value="{{ $history->time }}"
                                            class="form-control time-input" @if (!$editable) disabled @endif>
                                    </td>
                                    <td>
                                        <input type="text" id="time-{{ $history->id }}"
                                            value="{{ $history->location_history }}" class="form-control location-input"
                                            @if (!$editable) disabled @endif>
                                    </td>
                                    <td>
                                        <select id="status-{{ $history->id }}" class="form-control status-input"
                                            @if (!$editable) disabled @endif>
                                            @foreach ($statusHistories as $status)
                                                <option value="{{ $status }}"
                                                    @if ($history->status == $status) selected @endif
                                                    @if ($status == 'Confirmed' || $status == 'Picked Up') disabled @endif>
                                                    {{ $status }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td>{{ $history->updatedBy->name }}</td>
                                    <td>
                                        <input type="text" id="remarks-{{ $history->id }}"
                                            value="{{ $history->remarks }}" class="form-control remarks-input"
                                            @if (!$editable) disabled @endif>
                                    </td>
                                    <td>
                                        @if ($editable)
                                            <div class="d-flex" style="gap: 12px">
                                                <button class="btn btn-danger delete-row" style="font-size: 12px"
                                                    data-action="delete-existing">Delete</button>
                                                <button class="btn btn-info" style="font-size: 12px"
                                                    onclick="saveRow({{ $history->id }})">Save</button>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button id="addRow" class="btn btn-info text-white">Add History</button>
                    <button style="display: none;" id="saveChanges" class="btn btn-outline-success">Save Changes</button>
                </div>
            </div>
        @endif
        <input type="hidden" id="shipmentId" value="{{ $shipment->id }}">

    </section>
@endsection

@section('js')
    <script>
        new DataTable('#shipment-items', {
            responsive: true,
            fixedHeader: {
                header: true
            },
        });

        new DataTable('#shipment-history', {
            responsive: true,
            fixedHeader: {
                header: true
            },
        });

        $('#publish').on('click', function() {
            if (confirm('Are you sure you want to publish this shipment?')) {
                const shipmentId = $('#shipmentId').val();
                $.ajax({
                    url: `/shipment/publish/${shipmentId}`,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        iziToast.success({
                            title: 'Success',
                            message: 'This Shipment is published successfully'
                        });
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    },
                    error: function(xhr) {
                        console.error(xhr);
                    },
                })
            }
        });

        function saveRow(id) {
            if (confirm('Are you sure you want to update this history?')) {
                $.ajax({
                    url: `/shipment/edit-history/${id}`,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        date: $('#date-' + id).val(),
                        time: $('#time-' + id).val(),
                        location: $('#location-' + id).val(),
                        status: $('#status-' + id).val(),
                        remarks: $('#remarks-' + id).val()
                    },
                    success: function(response) {
                        iziToast.success({
                            title: 'Success',
                            message: 'Update history successfully'
                        });
                        setTimeout(() => {
                            window.location.reload();
                        }, 1000);
                    },
                    error: function(xhr) {
                        console.error(xhr);
                    },
                });
            }
        }
    </script>
    <script>
        $(document).ready(function() {
            var saveChangesButton = $("#saveChanges");
            // Add Row button click event
            $("#addRow").on("click", function() {
                var newRow = `
                    <tr>
                        <td>
                            <input type="date" name="date[]" class="form-control">
                        </td>
                        <td>
                            <input type="time" name="time[]" class="form-control">
                        </td>
                        <td>
                            <input type="text" name="location[]" class="form-control">
                        </td>
                        <td>
                            <select name="status[]" class="form-control">
                                @if (isset($statusHistories))
                                @foreach ($statusHistories as $status)
                                    <option value="{{ $status }}"    @if ($status == 'Confirmed' || $status == 'Picked Up') disabled @endif>
                                                    {{ $status }}</option>
                                @endforeach
                                @endif
                            </select>
                        </td>
                        <td></td>
                        <td>
                            <input type="text" name="remarks[]" class="form-control">
                        </td>
                        <td>
                            <button class="btn btn-danger delete-row" data-action="delete-new" style="font-size:12px">Delete</button>
                        </td>
                    </tr>
                `;

                $("#shipment-history tbody").append(newRow);

                saveChangesButton.show();

                return false;
            });

            // Delete Row button click event (for dynamically added rows)
            $(document).on("click", ".delete-row", function() {
                var historyId = $(this).closest("tr").data("history-id");
                var action = $(this).data("action");

                if (action === "delete-existing") {
                    if (confirm("Are you sure you want to delete this existing row?")) {
                        $.ajax({
                            url: `/shipment/delete-history/${historyId}`,
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            success: function(response) {
                                iziToast.success({
                                    title: 'Success',
                                    message: 'Delete history successfully'
                                });
                                setTimeout(() => {
                                    window.location.reload();
                                }, 1000);
                            },
                            error: function(xhr) {
                                console.error(xhr);
                            },
                        });
                    }
                } else if (action === "delete-new") {
                    if (confirm("Are you sure you want to delete this new row?")) {
                        $(this).closest("tr").remove();
                    }
                }
            });

            saveChangesButton.on("click", function() {
                const shipmentId = $('#shipmentId').val();
                var formData = [];
                $("#shipment-history tbody tr").each(function() {
                    var row = $(this);
                    var data = {
                        date: row.find('input[name="date[]"]').val(),
                        time: row.find('input[name="time[]"]').val(),
                        location: row.find('input[name="location[]"]').val(),
                        status: row.find('select[name="status[]"]').val(),
                        remarks: row.find('input[name="remarks[]"]').val(),
                    };
                    formData.push(data);
                });

                $.ajax({
                    url: `/shipment/add-history/${shipmentId}`,
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        shipmentHistories: formData,
                    },
                    success: function(response) {
                        iziToast.success({
                            title: 'Success',
                            message: 'Add shipment history'
                        });
                        setTimeout(() => {
                            window.location.reload();
                        }, 3000);
                    },
                    error: function(xhr) {
                        console.error(xhr);
                    },
                });
                return false;
            });
        });
    </script>
@endsection
