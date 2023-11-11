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
@section('content')
    <div class="pagetitle d-flex justify-content-between items-center">
        <div>
            <input type="hidden" value="{{ $shipment->shipment_number }}" id="shipmentNumber">
            <div class="d-flex" style="align-items:center;">
                <h1>View Request Shipment #{{ $shipment->shipment_number }}</h1>
                <span
                    class="badge 
                @if ($shipment->status == 'Warehouse Confirmation') bg-warning text-dark
                @elseif($shipment->status == 'Confirmed')
                bg-info text-white
                @elseif($shipment->status == 'Rejected')
                bg-danger text-white @endif"
                    style="margin-left: 1rem;"><i
                        class="bi 
                        @if ($shipment->status == 'Warehouse Confirmation') bi-exclamation-triangle 
                        @elseif($shipment->status == 'Confirmed')
                        bi-check
                        @elseif($shipment->status == 'Rejected')
                        bi-cross @endif
                        me-1"></i>{{ $shipment->status }}</span>
            </div>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item"><a href="{{ route('warehouse.index') }}">Warehouse</a> </li>
                    <li class="breadcrumb-item"><a href="{{ route('warehouse.request-shipment.index') }}">Request
                            Shipments</a></li>
                    <li class="breadcrumb-item active">View Request Shipment #{{ $shipment->shipment_number }}</li>
                </ol>
            </nav>
        </div>
    </div><!-- End Page Title -->

    <div class="section">
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
            <div class="card-header">
                @if (!$available_items)
                    <div class="alert alert-danger bg-danger text-light border-0 alert-dismissible fade show"
                        role="alert">
                        Some item aren't available.
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert"
                            aria-label="Close"></button>
                    </div>
                @endif
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
                                Qty Ordered
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
                        @php
                            $itemIds = array();
                        @endphp
                        @foreach ($shipment->shipmentItems as $item)
                            @php
                                $itemIds[] = $item->item_id
                            @endphp
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

        @if ($shipment->status == 'Warehouse Confirmation')
            <div class="button-wrapper d-flex justify-content-center" style="gap: 1rem;">
                <button type="button" class="btn btn-info"
                    @if (!$available_items) data-bs-placement="left" data-bs-original-title="You can't approve this shipment" data-bs-toggle="tooltip"
            @else
            data-bs-toggle="modal" data-bs-target="#approve" @endif>Approve</button>
                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#reject">Reject</button>

                {{-- approve modal --}}
                <div class="modal fade" id="approve" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <strong class="modal-title text-bold">Approve this shipment?</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-control">
                                    <label for="remarks">Remarks note (optional)</label>
                                    <textarea id="remarks" cols="1" rows="1" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-primary" id="proceedApprove">Proceed</button>
                            </div>
                        </div>
                    </div>
                </div><!-- End Basic Modal-->

                {{-- reject modal --}}

                <div class="modal fade" id="reject" tabindex="-1">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <strong class="modal-title text-bold">Reject this shipment?</strong>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="form-control">
                                    <label for="rejection">Reject reason</label>
                                    <textarea id="rejection" cols="3" rows="2" class="form-control"></textarea>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <button type="button" class="btn btn-danger" id="proceedReject">Proceed</button>
                            </div>
                        </div>
                    </div>
                </div><!-- End Basic Modal-->
            </div>
        @elseif($shipment->status == 'Confirmed')
            <div class="card">
                <div class="card-header">
                    <strong>Picked Up?</strong>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group mt-2">
                                <label class="form-label">Date</label>
                                <input type="date" name="date" id="date" class="form-control">
                            </div>
                            <div class="form-group mt-2">
                                <label class="form-label">Location</label>
                                <input type="text" name="location" id="location" class="form-control"
                                    value="{{ $shipment->warehouse->name_warehouse }}" readonly>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group mt-2">
                                <label class="form-label">Time</label>
                                <input type="time" name="time" id="time" class="form-control">
                            </div>
                            <div class="form-group mt-2">
                                <label class="form-label">Remarks</label>
                                <textarea name="remarksPickedUp" id="remarksPickedUp" cols="1" rows="1" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button class="btn btn-primary" id="submitPickedUp">Submit</button>
                </div>
            </div>
        @endif
    </div>
@endsection

@section('js')
    <script>
        new DataTable('#shipment-items', {
            responsive: true,
            fixedHeader: {
                header: true
            },
        });

        $('#proceedApprove').on('click', function() {
            const shipmentNumber = $('#shipmentNumber').val();
            var itemIds = {!! json_encode($itemIds) !!};
            $.ajax({
                url: `/warehouse/request-shipment/${shipmentNumber}/shipment-confirmation`,
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    remarks: $('#remarks').val(),
                    type: 'Approve',
                    itemIds: itemIds
                },
                success: function(response) {
                    iziToast.success({
                        title: 'Success',
                        message: 'Approve this shipment'
                    });
                    setTimeout(() => {
                        window.location.href =
                            "{{ route('warehouse.request-shipment.index') }}"
                    }, 3000);
                },
                error: function(error) {
                    iziToast.error({
                        title: 'Error',
                        message: error.data.message,
                    });
                }
            });
        });

        $('#proceedReject').on('click', function() {
            const shipmentNumber = $('#shipmentNumber').val();
            $.ajax({
                url: `/warehouse/request-shipment/${shipmentNumber}/shipment-confirmation`,
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    remarks: $('#rejection').val(),
                    type: 'Reject'
                },
                success: function(response) {
                    iziToast.success({
                        title: 'Success',
                        message: 'Reject this shipment'
                    });
                    setTimeout(() => {
                        window.location.reload();
                    }, 3000);
                },
                error: function(error) {
                    iziToast.error({
                        title: 'Error',
                        message: error.data.message,
                    });
                }
            });
        });

        $('#submitPickedUp').on('click', function() {
            const shipmentNumber = $('#shipmentNumber').val();

            $.ajax({
                url: `/warehouse/request-shipment/${shipmentNumber}/shipment-picked-up`,
                type: 'POST',
                dataType: 'json',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    date: $('#date').val(),
                    time: $('#time').val(),
                    location: $('#location').val(),
                    remarks: $('#remarksPickedUp').val()
                },
                success: function(response) {
                    iziToast.success({
                        title: 'Success',
                        message: response.message
                    });
                    setTimeout(() => {
                        window.location.href = '/warehouse/request-shipment';
                    }, 3000);
                },
                error: function(error) {
                    iziToast.error({
                        title: 'Error',
                        message: error.data.message,
                    });
                }
            });
        })
    </script>
@endsection
