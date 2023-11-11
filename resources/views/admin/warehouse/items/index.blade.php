@extends('layouts.admin')
@section('warehouse')
    collapse
@endsection
@section('warehouse_show')
    show
@endsection
@section('warehouse_items')
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
    <div class="pagetitle d-flex justify-content-between">
        <div>
            <h1>Items </h1>
            <nav>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">Home</li>
                    <li class="breadcrumb-item"> <a href="{{ route('warehouse.index') }}">Warehouse</a> </li>
                    <li class="breadcrumb-item active">Items</li>
                </ol>
            </nav>
        </div>
    </div><!-- End Page Title -->

    <div class="card">
        <div class="card-header">
            <strong>Select warehouse</strong>
        </div>
        <div class="card-body mt-3">
            <div class="form-group">
                <form action="{{ route('warehouse.items.index') }}" method="GET">
                    @csrf
                    <select id="warehouse-select" name="warehouseId" class="form-control">
                        <option value="" disabled selected>Name - Code</option>
                        @foreach ($warehouses as $warehouse)
                            <option value="{{ $warehouse->id }}" @if (!blank($selectedWarehouse) && $selectedWarehouse['id'] == $warehouse->id) selected @endif>
                                {{ $warehouse->name_warehouse }} {{ $warehouse->code_warehouse }}
                            </option>
                        @endforeach
                    </select>
                    <button class="btn btn-info mt-4" style="float: right;">Get items</button>
                </form>
            </div>
        </div>
    </div>
    @if ($selectedWarehouse['id'] !== 0)
        <div class="card">
            <div class="card-header">
                <strong>Items detail on {{ $selectedWarehouse->name_warehouse }}</strong>
            </div>
            <div class="card-body mt-3" id="items">
                <div class="">
                    <p>
                        Capacity: <span id="items-total">{{ $itemsTotal }}</span>/{{ $selectedWarehouse->capacity }}
                        <span id="capacity-warning" style="color: red; display: none;">Total weight exceeds capacity!</span>
                    </p>
                </div>
                <table id="data-items" class="table table-bordered table-striped table-hover dt-responsive"
                    style="width:100%">
                    <thead>
                        <tr>
                            <th>SKU</th>
                            <th>Item name</th>
                            <th>Description</th>
                            <th>Price</th>
                            <th>Qty</th>
                            <th>Length</th>
                            <th>Width</th>
                            <th>Height</th>
                            <th>Weight</th>
                            <th>Total Weight</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($items as $index => $item)
                            <tr>
                                <td><input type="text" class="form-control sku" value="{{ $item->sku }}">
                                </td>
                                <td><input type="text" class="form-control item-name" value="{{ $item->item_name }}">
                                </td>
                                <td><input type="text" class="form-control description"
                                        value="{{ $item->description }}">
                                </td>
                                <td><input type="text" class="form-control price" value="{{ $item->price }}">
                                </td>
                                <td><input type="text" class="form-control qty" data-index="{{ $index }}"
                                        value="{{ $item->quantity }}">
                                </td>
                                <td><input type="text" class="form-control length number" value="{{ $item->length }}">
                                </td>
                                <td><input type="text" class="form-control width number" value="{{ $item->width }}">
                                </td>
                                <td><input type="text" class="form-control height number" value="{{ $item->height }}">
                                </td>
                                <td><input type="text" class="form-control weight number"
                                        data-index="{{ $index }}" value="{{ $item->weight }}">
                                </td>
                                <td><input type="text" class="form-control total-weight number"
                                        value="{{ $item->total_weight }}" readonly>
                                </td>
                                <td>
                                    <button class="btn btn-danger delete-row">Delete</button>
                                </td>
                            </tr>
                        @endforeach

                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="9">
                                <p style="text-align: end; font-weight:bold; margin-bottom: 0">Grand Total Weight</p>
                            </td>
                            <td colspan="2" id="grand-total-weight">
                                <p style="text-align: end; font-weight:bold; margin-bottom: 0">{{ $itemsTotal }}</p>

                            </td>
                        </tr>
                        <tr>
                            <td colspan="11">
                                <button class="btn btn-primary add-row">Add Row</button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
                <button id="saveItems" type="submit" class="btn btn-outline-primary mt-2">Save Item Changes</button>
            </div>
        </div>
    @endif
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


            $('.add-row').click(function() {
                $('.dataTables_empty').hide();
                var newRow = `
            <tr>
                <td><input type="text" class="form-control sku"></td>
                <td><input type="text" class="form-control item-name"></td>
                <td><input type="text" class="form-control description"></td>
                <td><input type="text" class="form-control price"></td>
                <td><input type="text" class="form-control qty"></td>
                <td><input type="text" class="form-control length"></td>
                <td><input type="text" class="form-control width"></td>
                <td><input type="text" class="form-control height"></td>
                <td><input type="text" class="form-control weight"></td>
                <td><input type="text" class="form-control total-weight" readonly></td>
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

            var warehouseCapacity = {{ $selectedWarehouse['capacity'] }};

            var itemsTotal = {{ $itemsTotal }};

            // Fungsi untuk menghitung dan mengisi nilai total-weight
            function calculateTotalWeight() {
                var qty = parseFloat($(this).closest('tr').find('.qty').val());
                var weight = parseFloat($(this).closest('tr').find('.weight').val());

                if (!isNaN(qty) && !isNaN(weight)) {
                    var totalWeight = qty * weight;
                    $(this).closest('tr').find('.total-weight').val(totalWeight);
                    itemsTotal = calculateItemsTotal(); // Update itemsTotal
                    updateItemsTotalDisplay();
                    calculateGrandTotalWeight();
                }
            }

            function calculateGrandTotalWeight() {
                var grandTotalWeight = 0;

                $('.total-weight').each(function() {
                    var rowTotalWeight = parseFloat($(this).val());
                    if (!isNaN(rowTotalWeight)) {
                        grandTotalWeight += rowTotalWeight;
                    }
                });

                $('#grand-total-weight').text(grandTotalWeight.toFixed(2)).css({
                    'font-weight': 'bold',
                    'text-align': 'end'
                });

                if (grandTotalWeight > warehouseCapacity) {
                    $('#capacity-warning').show();
                } else {
                    $('#capacity-warning').hide();
                }
            }

            // Fungsi untuk menghitung total berat dari semua baris
            function calculateItemsTotal() {
                var total = 0;
                $('.total-weight').each(function() {
                    var rowTotal = parseFloat($(this).val());
                    if (!isNaN(rowTotal)) {
                        total += rowTotal;
                    }
                });
                return total;
            }

            // Fungsi untuk memperbarui tampilan $itemsTotal
            function updateItemsTotalDisplay() {
                $('#items-total').text(itemsTotal.toFixed(2));

                if (itemsTotal > warehouseCapacity) {
                    $('#capacity-warning').show();
                } else {
                    $('#capacity-warning').hide();
                }
            }

            // Mengaitkan peristiwa keypress/keydown ke bidang qty dan weight
            $(document).on('keyup', '.qty, .weight', calculateTotalWeight);

            // Inisialisasi tampilan awal
            updateItemsTotalDisplay();

            $('#saveItems').click(function() {
                var itemsData = [];

                // Loop melalui setiap baris item dan kumpulkan data
                $('#data-items tbody tr').each(function() {
                    var itemData = {
                        sku: $(this).find('.sku').val(),
                        item_name: $(this).find('.item-name').val(),
                        description: $(this).find('.description').val(),
                        price: $(this).find('.price').val(),
                        quantity: $(this).find('.qty').val(),
                        length: $(this).find('.length').val(),
                        width: $(this).find('.width').val(),
                        height: $(this).find('.height').val(),
                        weight: $(this).find('.weight').val(),
                        total_weight: $(this).find('.total-weight').val(),
                    };
                    itemsData.push(itemData);
                });
                var grandTotalWeight = parseFloat($('#grand-total-weight').text());
                var warehouseCapacity = parseFloat('{{ $selectedWarehouse['capacity'] }}');

                // Validasi jika grand total weight melebihi kapasitas
                if (grandTotalWeight > warehouseCapacity) {
                    iziToast.error({
                        title: 'Error',
                        message: 'Total weight exceeds capacity!',
                        position: 'topRight',
                    });
                } else {
                    // Kirim data item menggunakan Ajax
                    $.ajax({
                        url: "/warehouse/items/change",
                        type: "POST",
                        data: {
                            _token: "{{ csrf_token() }}",
                            items: itemsData,
                            warehouse_id: "{{ $selectedWarehouse['id'] }}",
                        },
                        success: function(response) {
                            iziToast.success({
                                title: 'Success',
                                message: response.message,
                                position: 'topRight',
                            });
                            setTimeout(() => {
                                window.location.reload()
                            }, 1500);
                        },
                        error: function(xhr) {
                            iziToast.error({
                                title: 'Error',
                                message: response.message,
                                position: 'topRight',
                            });
                        }
                    });

                }
            });

        });
    </script>
@endsection
