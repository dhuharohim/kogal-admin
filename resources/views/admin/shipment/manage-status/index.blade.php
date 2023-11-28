@extends('layouts.admin')
@section('css')
    <style>
        .form-group {
            font-size: 12px;
        }

        .form-control {
            font-size: 12px;
        }

        .btn {
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
            <h1>Manage Status </h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item"> <a href="{{ route('shipment.index') }}">Shipment</a> </li>
                    <li class="breadcrumb-item active">Manage Status</li>
                </ol>
            </nav>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <strong>Shipments Update Status</strong>
        </div>
        <div class="card-body pt-4">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="" class="form-label">Select Shipment</label>
                        <select class="form-control" id="select-shipments"></select>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="form-group">
                        <label for="" class="form-label">Select status</label>
                        <select name="status" id="status" class="form-control">
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
                        </select>
                    </div>
                </div>
                <div class="col-md-5">
                    <div class="form-group">
                        <label for="" class="form-label">Remarks</label>
                        <textarea class="form-control" id="remarks"></textarea>
                    </div>
                </div>
                <div class="col-md-1" style="place-self: center;">
                    <button class="btn btn-danger" id="deleteRow">Delete</button>
                </div>
            </div>
        </div>
        <div class="card-footer d-flex justify-content-end" style="gap:1rem;">
            <button class="btn btn-outline-primary" onclick="">Save changes</button>
            <button class="btn btn-primary" onclick="addRow()">Add Row</button>
        </div>
    </div>
@endsection

@section('js')
    <script>
        var dataShipment;
        $(document).ready(function() {
            $.ajax({
                url: '/shipment/master',
                type: 'GET',
                dataType: 'json',
                success: function(responseData) {
                    console.log(responseData);
                    var select = $('#select-shipments'); // assuming you have this element in your HTML
                    dataShipment = responseData.shipments;
                    initializeSelectize(select);
                },
                error: function(xhr, status, error) {
                    console.error('Error fetching data:', error);
                }
            });

        });

        function initializeSelectize(select) {
            select.selectize({
                options: dataShipment.map(function(item) {
                    return {
                        value: item.id,
                        text: item.shipment_number + ' - ' + item.status
                    };
                }),
                delimiter: ',',
                persist: false,
                create: false
            });
        }

        function addRow() {
            var newRow = `
    <div class="row">
        <div class="col-md-4">
            <div class="form-group">
                <label for="" class="form-label">Select Shipment</label>
                <select class="form-control select-shipments"></select>
            </div>
        </div>
        <div class="col-md-2">
            <div class="form-group">
                <label for="" class="form-label">Select status</label>
                <select name="status" class="form-control">
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
                </select>
            </div>
        </div>
        <div class="col-md-5">
            <div class="form-group">
                <label for="" class="form-label">Remarks</label>
                <textarea class="form-control" id="remarks"></textarea>
            </div>
        </div>
        <div class="col-md-1" style="place-self: center;">
            <button class="btn btn-danger">Delete</button>
        </div>
    </div>`;

            $(".card-body").append(newRow);

            // Initialize Selectize for the newly added select element
            var select = $('.card-body').find('.select-shipments').last();
            initializeSelectize(select); // assuming 'data' is accessible here
        }
    </script>
@endsection
