<?php

namespace App\Http\Controllers;

use App\Models\Items;
use App\Models\ShipmentHeader;
use App\Models\ShipmentHistories;
use App\Models\ShipmentItem;
use App\Models\Warehouse;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class WarehouseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $warehouses = Warehouse::whereNull('deleted_at')->get();
        return view('admin.warehouse.index', [
            'user' => auth()->user(),
            'warehouses' => $warehouses
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.warehouse.create', [
            'user' => auth()->user(),
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $warehouseData = $request->warehouse;
        try {
            DB::transaction(function () use ($warehouseData) {
                Warehouse::create([
                    'code_warehouse' => $warehouseData['code_warehouse'],
                    'name_warehouse' => $warehouseData['name_warehouse'],
                    'street' => $warehouseData['street'],
                    'city' => $warehouseData['city'],
                    'province' => $warehouseData['province'],
                    'postal_code' => $warehouseData['postal_code'],
                    'capacity' => $warehouseData['capacity'],
                    'work_phone' => $warehouseData['work_phone'],
                    'email_warehouse' => $warehouseData['email']
                ]);
            });
        } catch (Throwable $e) {
            return response()->json([
                'error' => true,
                'message' => 'Sorry some error occurred while creating warehouse!'
            ]);
        }

        return response()->json([
            'error' => false,
            'message' => 'Successfully create a new warehouse!'
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $warehouse = Warehouse::find($id)->first();

        return view('admin.warehouse.show', [
            'user' => auth()->user(),
            'warehouse' => $warehouse
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $warehouse = Warehouse::where('id', $id)->first();
        $warehouseUpdate = $request->warehouse;
        if (empty($warehouse)) {
            return response()->json([
                'error' => true,
                'message' => 'The requested resource does not exist'
            ]);
        }

        try {
            DB::transaction(function () use ($warehouse, $warehouseUpdate) {
                $warehouse->update([
                    'code_warehouse' => $warehouseUpdate['code_warehouse'],
                    'name_warehouse' => $warehouseUpdate['name_warehouse'],
                    'street' => $warehouseUpdate['street'],
                    'city' => $warehouseUpdate['city'],
                    'postal_code' => $warehouseUpdate['postal_code'],
                    'province' => $warehouseUpdate['province'],
                    'capacity' => $warehouseUpdate['capacity'],
                    'email_warehouse' => $warehouseUpdate['email'],
                    'work_phone' => $warehouseUpdate['work_phone']
                ]);
            }, 5);
        } catch (Throwable $e) {
            return response()->json([
                'error' => true,
                'message' => 'Sorry something went wrong'
            ]);
        }

        return response()->json([
            'error' => false,
            'message' => 'Warehouse update successfully'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $warehouse = Warehouse::where('id', $id)->first();

        $warehouseItems = Items::where('warehouse_id', $warehouse->id)->get();

        if (empty($warehouse)) {
            return response()->json([
                'error' => true,
                'message' => 'Warehouse not found',
            ]);
        }

        try {
            DB::transaction(function () use ($warehouse, $warehouseItems) {
                foreach ($warehouseItems as $deleteItem) {
                    $deleteItem->forceDelete();
                }
                $warehouse->delete();
            });
        } catch (Throwable $e) {
            return response()->json([
                'error' => true,
                'message' => 'Sorry! Something went wrong',
            ]);
        }

        return response()->json([
            'error' => false,
            'message' => 'Successfully delete warehouse',
        ]);
    }

    public function requestShipment(Request $request)
    {
        $warehouses = Warehouse::all();
        $selectedWarehouse = collect();
        $dataRequest = collect();
        if (!empty($request->warehouseId)) {
            $selectedWarehouse = Warehouse::where('id', $request->warehouseId)->first();
            $dataRequest = ShipmentHeader::where('warehouse_id', $request->warehouseId)
                ->whereIn('status', ['Warehouse Confirmation', 'Confirmed', 'Rejected'])->get();
        }


        return view('admin.warehouse.request-shipment.index', [
            'user' => auth()->user(),
            'request_shipments' => $dataRequest,
            'warehouses' => $warehouses,
            'selectedWarehouse' => blank($selectedWarehouse) ? collect(['id' => 0, 'capacity' => 0]) : $selectedWarehouse,
        ]);
    }

    public function showRequestShipment($shipment_number)
    {
        $shipment = ShipmentHeader::where('shipment_number', $shipment_number)
            ->with(['shipmentItems', 'origin', 'destination', 'type', 'mode', 'carrier', 'shipmentItems.item', 'warehouse'])
            ->first();


        if (empty($shipment)) {
            abort(404);
        }

        // check
        $isAvailable = true;
        foreach ($shipment->shipmentItems as $itemOrdered) {
            if (!$itemOrdered->is_item_available) {
                $isAvailable = false;
            }
        }

        return view('admin.warehouse.request-shipment.view', [
            'user' => auth()->user(),
            'shipment' => $shipment,
            'available_items' => $isAvailable
        ]);
    }

    public function shipmentConfirmation($shipment_number, Request $request)
    {
        $shipmentHeader = ShipmentHeader::where('shipment_number', $shipment_number)
            ->with('warehouse')
            ->first();

        if (empty($shipmentHeader)) {
            return response()->json(['message' => 'Shipment not found'], 422);
        }

        try {
            DB::transaction(function () use ($shipmentHeader, $request) {
                if ($request->type == 'Approve') {
                    $shipmentHeader->update([
                        'status' => 'Confirmed',
                    ]);

                    ShipmentHistories::create([
                        'shipment_header_id' => $shipmentHeader->id,
                        'date' => Carbon::now()->format('Y-m-d'),
                        'time' => Carbon::now()->format('H:i:s'),
                        'location_history' => $shipmentHeader->warehouse->name_warehouse,
                        'status' => 'Confirmed',
                        'updated_by' => auth()->user()->id,
                        'remarks' => $request->remarks
                    ]);

                    // update stock
                    $shipmentItems = ShipmentItem::whereIn('item_id', $request->itemIds)->get();

                    foreach ($shipmentItems as $shipmentItem) {
                        $item = Items::where('id', $shipmentItem->item_id)->first();
                    
                        if ($item) {
                            $item->update([
                                'quantity' => $item->quantity - $shipmentItem->qty
                            ]);
                        }
                    }
                } else {
                    $shipmentHeader->update([
                        'status' => 'Rejected',
                    ]);

                    ShipmentHistories::create([
                        'shipment_header_id' => $shipmentHeader->id,
                        'date' => Carbon::now()->format('Y-m-d'),
                        'time' => Carbon::now()->format('H:i:s'),
                        'location_history' => $shipmentHeader->warehouse->name_warehouse,
                        'status' => 'Rejected',
                        'updated_by' => auth()->user()->id,
                        'remarks' => $request->remarks
                    ]);
                }
            }, 2);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Some error occured'], 500);
        }

        return response()->json(['message' => 'Successfully '. $request->type == 'Approve' ? 'approve': 'rejected' . ' this shipment.'], 200);
    }

    public function pickedUp($shipment_number, Request $request)
    {
        $this->validate($request, [
            'date' => 'required',
            'time' => 'required',
            'location' => 'required',
            'remarks' => 'required'
        ]);

        $shipmentHeader = ShipmentHeader::where('shipment_number', $shipment_number)
            ->with('warehouse')
            ->first();

        if (empty($shipmentHeader)) {
            return response()->json(['message' => 'Shipment not found.'], 422);
        }

        try {
            DB::transaction(function () use ($shipmentHeader, $request) {
                $shipmentHeader->update([
                    'status' => 'Picked Up',
                ]);

                ShipmentHistories::create([
                    'shipment_header_id' => $shipmentHeader->id,
                    'date' => $request->date,
                    'time' => $request->time,
                    'location_history' => $request->location,
                    'status' => 'Picked Up',
                    'updated_by' => auth()->user()->id,
                    'remarks' => $request->remarks
                ]);
            }, 2);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Failed to submit pick up'], 500);
        }

        return response()->json(['message' => 'Successfully submit pick up'], 200);
    }
}
