<?php

namespace App\Http\Controllers;

use App\Models\Items;
use App\Models\ShipmentHeader;
use App\Models\ShipmentHistories;
use App\Models\ShipmentLogActivity;
use App\Models\Warehouse;
use App\Models\WarehouseActivityLog;
use App\Services\WarehouseActivity;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
        $warehouseActivities = new WarehouseActivityLog();
        $statusWarehouse = 'All';
        if (!empty($request->filterWarehouse)) {
            if ($request->filterWarehouse == 'Today') {
                $shipmentTotalVolWeight = ShipmentHeader::whereDate('created_at', Carbon::today());
                $statusWarehouse = 'Today';
            } elseif ($request->filterWarehouse == 'Month') {
                $shipmentTotalVolWeight = ShipmentHeader::whereMonth('created_at', Carbon::now()->month);
                $statusWarehouse = 'Month';
            } elseif ($request->filterWarehouse == 'Year') {
                $shipmentTotalVolWeight = ShipmentHeader::whereYear('created_at', Carbon::now()->year);
                $statusWarehouse = 'Year';
            }
        }

        $warehouseActivities = $warehouseActivities->with(['user', 'warehouse'])->get();

        $shipmentTotalVolWeight = new ShipmentHeader();
        $statusTotalVolWeight = 'All';

        if (!empty($request->filterTotalVolWeight)) {
            if ($request->filterTotalVolWeight == 'Today') {
                $shipmentTotalVolWeight = ShipmentHeader::whereDate('created_at', Carbon::today())->orderBy('total_vol_weight', 'desc')->get();
                $statusTotalVolWeight = 'Today';
            } elseif ($request->filterTotalVolWeight == 'Month') {
                $shipmentTotalVolWeight = ShipmentHeader::whereMonth('created_at', Carbon::now()->month)->orderBy('total_vol_weight', 'desc')->get();
                $statusTotalVolWeight = 'Month';
            } elseif ($request->filterTotalVolWeight == 'Year') {
                $shipmentTotalVolWeight = ShipmentHeader::whereYear('created_at', Carbon::now()->year)->orderBy('total_vol_weight', 'desc')->get();
                $statusTotalVolWeight = 'Year';
            }
        } else {
            // Jika tidak ada filter, ambil semua data
            $shipmentTotalVolWeight = ShipmentHeader::orderBy('total_vol_weight', 'desc')->get();
        }

        // Ambil data pertama setelah melakukan sort
        if (!blank($shipmentTotalVolWeight)) {
            $shipmentTotalVolWeight = $shipmentTotalVolWeight->first()->total_vol_weight;
        } else {
            $shipmentTotalVolWeight = 'Not found';
        }

        $shipmentTotal = new ShipmentHeader();
        $statusTotal = 'All';
        if (!empty($request->filterTotal)) {
            if ($request->filterTotal == 'Today') {
                $shipmentTotal = $shipmentTotal->whereDate('created_at', Carbon::today())->orderBy('total_vol', 'desc')->get();
                $statusTotal = 'Today';
            } elseif ($request->filterTotal == 'Month') {
                $shipmentTotal = $shipmentTotal->whereMonth('created_at', Carbon::now()->month)->orderBy('total_vol', 'desc')->get();
                $statusTotal = 'Month';
            } elseif ($request->filterTotal == 'Year') {
                $shipmentTotal = $shipmentTotal->whereYear('created_at', Carbon::now()->year)->orderBy('total_vol', 'desc')->get();
                $statusTotal = 'Year';
            }
        } else {
            $shipmentTotal = ShipmentHeader::orderBy('total_vol', 'desc')->get();
        }

        if (!blank($shipmentTotal)) {
            $shipmentTotal = $shipmentTotal->first()->total_vol;
        } else {
            $shipmentTotal = 'Not found';
        }


        $shipmentPriceVat = new ShipmentHeader();
        $statusPrice = 'All';
        if (!empty($request->filterPrice)) {
            if ($request->filterPrice == 'Today') {
                $statusPrice = 'Today';
                $shipmentPriceVat = $shipmentPriceVat->whereDate('created_at', Carbon::today())->orderBy('total_price_vat', 'desc')->get();;
            } elseif ($request->filterPrice == 'Month') {
                $shipmentPriceVat = $shipmentPriceVat->whereMonth('created_at', Carbon::now()->month)->orderBy('total_price_vat', 'desc')->get();;
                $statusPrice = 'Month';
            } elseif ($request->filterPrice == 'Year') {
                $shipmentPriceVat = $shipmentPriceVat->whereYear('created_at', Carbon::now()->year)->orderBy('total_price_vat', 'desc')->get();;
                $statusPrice = 'Year';
            }
        } else {
            $shipmentPriceVat = ShipmentHeader::orderBy('total_price_vat', 'desc')->get();
        }

        if (!blank($shipmentPriceVat)) {
            $shipmentPriceVat = $shipmentPriceVat->first()->total_price_vat;
        } else {
            $shipmentPriceVat = 'Not Found';
        }

        $shipments = ShipmentHeader::with(['type', 'origin', 'destination'])->where('status', '!=', 'Invoiced');
        $statusShipment = 'All';
        if (!empty($request->filterRecent)) {
            if ($request->filterRecent == 'Today') {
                $shipments = $shipments->whereDate('created_at', Carbon::today())->orderBy('total_price_vat', 'desc');
                $statusShipment = 'Today';
            } elseif ($request->filterRecent == 'Month') {
                $shipments = $shipments->whereMonth('created_at', Carbon::now()->month)->orderBy('total_price_vat', 'desc');
                $statusShipment = 'Month';
            } elseif ($request->filterRecent == 'Year') {
                $shipments = $shipments->whereYear('created_at', Carbon::now()->year)->orderBy('total_price_vat', 'desc');
                $statusShipment = 'Year';
            }
        }

        $shipments = $shipments->paginate(8);

        $shipmentActivity = ShipmentLogActivity::with('user');
        $statusActivity = 'All';
        if (!empty($request->filterLog)) {
            if ($request->filterLog == 'Today') {
                $shipmentActivity = $shipmentActivity->whereDate('created_at', Carbon::today());
                $statusActivity = 'Today';
            } elseif ($request->filterLog == 'Month') {
                $shipmentActivity = $shipmentActivity->whereMonth('created_at', Carbon::now()->month);
                $statusActivity = 'Month';
            } elseif ($request->filterLog == 'Year') {
                $shipmentActivity = $shipmentActivity->whereYear('created_at', Carbon::now()->year);
                $statusActivity = 'Year';
            }
        }

        $shipmentActivity = $shipmentActivity->get();

        return view('admin.home', [
            'user' => auth()->user(),
            'warehouse_activities' => $warehouseActivities,
            'status_warehouse_activity' => $statusWarehouse,
            'shipment_total' => $shipmentTotal,
            'shipment_price_vat' => $shipmentPriceVat,
            'shipment_histories' => $shipmentActivity,
            'shipment_total_vol_weight' => $shipmentTotalVolWeight,
            'status_total' => $statusTotal,
            'status_vol_weight' => $statusTotalVolWeight,
            'status_price_vat' => $statusPrice,
            'shipments' => $shipments,
            'status_shipment' => $statusShipment,
            'status_activity' => $statusActivity
        ]);
    }

    public function data(Request $request)
    {


        return response()->json([], 200);
    }
}
