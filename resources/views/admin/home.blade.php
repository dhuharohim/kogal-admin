@extends('layouts.admin')

@section('content')
    <div class="pagetitle">
        <h1>Dashboard</h1>
        <nav>
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </nav>
    </div><!-- End Page Title -->
    <section class="section dashboard">
        <div class="row">
            <!-- Left side columns -->
            <div class="col-lg-8">
                <div class="row">

                    @if ($user->role == 'admin' || $user->role == 'shipment')
                        <!-- Sales Card -->
                        <div class="col-xxl-4 col-md-6">
                            <div class="card info-card sales-card">

                                <div class="filter">
                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                            class="bi bi-three-dots"></i></a>
                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                        <li class="dropdown-header text-start">
                                            <h6>Filter</h6>
                                        </li>

                                        <li><a class="dropdown-item" href="/home?filterTotalVolWeight=Today">Today</a></li>
                                        <li><a class="dropdown-item" href="/home?filterTotalVolWeight=Month">This Month</a>
                                        </li>
                                        <li><a class="dropdown-item" href="/home?filterTotalVolWeight=Year">This Year</a>
                                        </li>
                                    </ul>
                                </div>

                                <div class="card-body">
                                    <h5 class="card-title">Top Volumetric Weight <span> | {{ $status_vol_weight }} </span>
                                    </h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class="bx bxs-flask"></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>{{ $shipment_total_vol_weight }}</h6>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div><!-- End Sales Card -->

                        <!-- Revenue Card -->
                        <div class="col-xxl-4 col-md-6">
                            <div class="card info-card revenue-card">

                                <div class="filter">
                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                            class="bi bi-three-dots"></i></a>
                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                        <li class="dropdown-header text-start">
                                            <h6>Filter</h6>
                                        </li>

                                        <li><a class="dropdown-item" href="/home?filterTotal=Today">Today</a></li>
                                        <li><a class="dropdown-item" href="/home?filterTotal=Month">This Month</a></li>
                                        <li><a class="dropdown-item" href="/home?filterTotal=Year">This Year</a></li>
                                    </ul>
                                </div>

                                <div class="card-body">
                                    <h5 class="card-title">Top Total Volume <span>| {{ $status_total }}</span></h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class='bx bx-cylinder'></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>{{ $shipment_total }}</h6>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div><!-- End Revenue Card -->

                        <!-- Customers Card -->
                        <div class="col-xxl-4 col-xl-12">

                            <div class="card info-card customers-card">

                                <div class="filter">
                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                            class="bi bi-three-dots"></i></a>
                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                        <li class="dropdown-header text-start">
                                            <h6>Filter</h6>
                                        </li>

                                        <li><a class="dropdown-item" href="/home?filterPrice=Today">Today</a></li>
                                        <li><a class="dropdown-item" href="/home?filterPrice=Month">This Month</a></li>
                                        <li><a class="dropdown-item" href="/home?filterPrice=Year">This Year</a></li>
                                    </ul>
                                </div>

                                <div class="card-body">
                                    <h5 class="card-title">Top Price w/ VAT <span>| {{ $status_price_vat }}</span></h5>

                                    <div class="d-flex align-items-center">
                                        <div
                                            class="card-icon rounded-circle d-flex align-items-center justify-content-center">
                                            <i class='bx bx-money-withdraw'></i>
                                        </div>
                                        <div class="ps-3">
                                            <h6>{{ $shipment_price_vat }}</h6>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div><!-- End Customers Card -->


                        <!-- Recent Sales -->
                        <div class="col-12">
                            <div class="card recent-sales overflow-auto">
                                <div class="filter">
                                    <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                            class="bi bi-three-dots"></i></a>
                                    <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                        <li class="dropdown-header text-start">
                                            <h6>Filter</h6>
                                        </li>

                                        <li><a class="dropdown-item" href="/home?filterRecent=Today">Today</a></li>
                                        <li><a class="dropdown-item" href="/home?filterRecent=Month">This Month</a></li>
                                        <li><a class="dropdown-item" href="/home?filterRecent=Year">This Year</a></li>
                                    </ul>
                                </div>

                                <div class="card-body">
                                    <h5 class="card-title">Recent Shipments <span>| {{ $status_shipment }}</span></h5>

                                    <table class="table table-borderless datatable">
                                        <thead>
                                            <tr>
                                                <th scope="col">#</th>
                                                <th scope="col">Type</th>
                                                <th scope="col">Origin</th>
                                                <th scope="col">Destination</th>
                                                <th scope="col">Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($shipments as $shipment)
                                                <tr>
                                                    <th scope="row"><a
                                                            href="{{ route('shipment.show', $shipment->id) }}">#{{ $shipment->shipment_number }}</a>
                                                    </th>
                                                    <td>{{ $shipment->type->type_of_shipments }}</td>
                                                    <td>{{ $shipment->origin->name_origin }}</td>
                                                    <td>{{ $shipment->destination->destination_name }}</td>
                                                    <td><span
                                                            class="badge @if (in_array($shipment->status, ['Warehouse Confirmation', 'Draft', 'On Hold'])) bg-warning 
                                                @elseif(in_array($shipment->status, ['Picked Up', 'In Transit', 'Confirmed']))
                                                bg-info
                                                @elseif(in_array($shipment->status, ['Delivered', 'On Delivery']))
                                                bg-primary
                                                @else
                                                bg-danger @endif
                                                ">{{ $shipment->status }}</span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                            </div>
                        </div><!-- End Recent Sales -->
                    @endif

                </div>
            </div><!-- End Left side columns -->

            <!-- Right side columns -->
            @if ($user->role !== 'warehouse')
                <div class="col-lg-4">
                    <!-- Recent Activity -->
                    <div class="card">
                        <div class="filter">
                            <a class="icon" href="#" data-bs-toggle="dropdown"><i
                                    class="bi bi-three-dots"></i></a>
                            <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                                <li class="dropdown-header text-start">
                                    <h6>Filter</h6>
                                </li>

                                <li><a class="dropdown-item" href="/home?filterLog=Today">Today</a></li>
                                <li><a class="dropdown-item" href="/home?filterLog=Month">This Month</a></li>
                                <li><a class="dropdown-item" href="/home?filterLog=Year">This Year</a></li>
                            </ul>
                        </div>

                        <div class="card-body">
                            <h5 class="card-title">Recent Activity <span>| {{ $status_activity }}</span></h5>

                            <div class="activity">
                                @foreach ($shipment_histories as $activity)
                                    <div class="activity-item d-flex">
                                        <div class="activite-label">{{ $activity->time_elapsed }}</div>
                                        <i class='bi bi-circle-fill activity-badge text-success align-self-start'></i>
                                        <div class="activity-content">
                                            <b>{{ $activity->user->name }}</b>
                                            {{ $activity->action }}
                                        </div>
                                    </div><!-- End activity item-->
                                @endforeach
                            </div>

                        </div>
                    </div><!-- End Recent Activity -->
                </div><!-- End Right side columns -->
            @endif
            @if($user->role !== 'shipment')
            <!-- Recent Sales -->
            <div class="col-12">
                <div class="card recent-sales overflow-auto">
                    <div class="filter">
                        <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
                        <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                            <li class="dropdown-header text-start">
                                <h6>Filter</h6>
                            </li>

                            <li><a class="dropdown-item" href="/home?filterWarehouse=Today">Today</a></li>
                            <li><a class="dropdown-item" href="/home?filterWarehouse=Month">This Month</a></li>
                            <li><a class="dropdown-item" href="/home?filterWarehouse=Year">This Year</a></li>
                        </ul>
                    </div>

                    <div class="card-body">
                        <h5 class="card-title">Recent Warehouse Activities <span>| {{ $status_warehouse_activity }}</span></h5>

                        <table class="table table-borderless datatable">
                            <thead>
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">Action</th>
                                    <th scope="col">By</th>
                                    <th scope="col">Warehouse</th>
                                    <th scope="col">Description</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($warehouse_activities as $warehouse)
                                    <tr>
                                        <th scope="row">{{ $loop->iteration }}</a>
                                        </th>
                                        <td>{{ $warehouse->action }}</td>
                                        <td>{{ $warehouse->user->name }}</td>
                                        <td>{{ $warehouse->warehouse->code_warehouse }} - {{ $warehouse->warehouse->name_warehouse }}</td>
                                        <td>{{ $warehouse->description }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div><!-- End Recent Sales -->
            @endif
        </div>
    </section>
@endsection

@section('js')
@endsection
