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
    <div class="pagetitle d-flex justify-content-between">
        <div>
            <h1>Create Shipment Invoice </h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item"> <a href="{{ route('shipment.index') }}">Shipment</a> </li>
                    <li class="breadcrumb-item active">Create Shipment Invoice</li>
                </ol>
            </nav>
        </div>
    </div><!-- End Page Title -->

    <section class="section">
        <div class="card">
            <div class="card-header">
                <strong>Select Shipment</strong> <br>
                <sub>Multiple shipment invoice creation, shipment must be delivered or returned</sub>
            </div>
            <div class="card-body mt-4">
                <table id="multiple-invoices" class="table table-bordered table-striped table-hover dt-responsive">
                    <thead>
                        <tr>
                            <th>Shipment</th>
                            <th>Last Status</th>
                            <th>Remarks</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr class="shipment-row">
                            <td>
                                <select id="shipment-select[]" class="form-control shipment-select">
                                    <option value="" disabled selected>Select shipment</option>
                                    @foreach ($shipments as $item)
                                        <option value="{{ $item->id }}">{{ $item->shipment_number }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" id="shipment-status[]" readonly class="form-control status">
                            </td>
                            <td>
                                <input type="text" id="shipment-remarks[]" readonly class="form-control remarks">
                            </td>
                            <td>
                                <button class="btn btn-danger" id="deleteRow">Delete</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <button class="btn btn-outline-info" id="addRow">Add Row</button>
                <button class="btn btn-outline-success" id="submitInvoice">Convert to Invoice</button>
            </div>
        </div>
    </section>
@endsection

@section('js')
    <script>
        var selectedItems = [];

        new DataTable('#multiple-invoices', {
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

        $('#addRow').on('click', function() {
            var newRow = $('#multiple-invoices tbody tr.shipment-row:last').clone();
            newRow.find('input').val('').removeAttr('disabled');
            newRow.find('.shipment-select').val('');
            newRow.find('.status, .remarks').val('');
            $('#multiple-invoices tbody').append(newRow);
        });

        $('#multiple-invoices').on('click', '#deleteRow', function() {
            if ($('.shipment-row').length == 1) {
                return alert('You cant delete this row');
            }
            var rowToDelete = $(this).closest('.shipment-row');
            var shipmentId = rowToDelete.find('.shipment-select').val();

            // Re-enable the option that was selected in this row
            $('.shipment-select option[value="' + shipmentId + '"]').prop('disabled', false);

            // Remove the row
            rowToDelete.remove();

            // Update selectedItems array
            var itemIndex = selectedItems.indexOf(shipmentId);
            if (itemIndex !== -1) {
                selectedItems.splice(itemIndex, 1);
            }
        });

        $('#multiple-invoices').on('change', '.shipment-select', function() {
            var selectedRow = $(this).closest('.shipment-row');
            var shipmentId = $(this).val();

            if (shipmentId !== '' && selectedItems.indexOf(shipmentId) === -1) {
                selectedItems.push(shipmentId);

                // Disable the selected option in other rows
                $('.shipment-select option[value="' + shipmentId + '"]').not(this).prop('disabled', true);
                $.ajax({
                    url: '/shipment/invoices/data-shipment/' + shipmentId, // Replace with your API endpoint
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        const data = response.shipment;
                        selectedRow.find('.status').val(data.status);
                        selectedRow.find('.remarks').val(data.remarks);
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            } else {
                $(this).val('');
                selectedRow.find('.status, .remarks').val('');
            }
        });

        $('#submitInvoice').on('click', function() {
            if (confirm('Are you sure you want to convert this list of shipment to Invoice?')) {
                var dataToSend = [];
                $('.shipment-row').each(function() {
                    var row = $(this);
                    var shipmentId = row.find('.shipment-select :selected').val();
                    dataToSend.push({
                        shipment_id: shipmentId,
                    });
                });

                $.ajax({
                    url: '/shipment/invoices/multiple-creation',
                    type: 'POST',
                    dataType: 'json',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        shipmentIds: dataToSend
                    },
                    success: function(response) {
                        iziToast.success({
                            title: 'Success',
                            message: 'Successfully convert all list of shipment to invoice'
                        });
                        setTimeout(() => {
                            window.location.href = "{{ route('shipment.invoices.index') }}"
                        }, 3000);
                    },
                    error: function(error) {
                        // Handle error response
                        console.log(error);
                    }
                });
            };

        })
    </script>
@endsection
