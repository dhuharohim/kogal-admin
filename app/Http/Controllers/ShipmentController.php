<?php

namespace App\Http\Controllers;

use App\Models\Carrier;
use App\Models\Destination;
use App\Models\Items;
use App\Models\Mode;
use App\Models\Origin;
use App\Models\PaymentMode;
use App\Models\ShipmentHeader;
use App\Models\ShipmentHistories;
use App\Models\ShipmentItem;
use App\Models\TypeOfShipment;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class ShipmentController extends Controller
{

    public function getAjax()
    {
        $shipments = ShipmentHeader::all();
        if (!$shipments) {
            return response()->json([''], 500);
        }
        return response()->json(['shipments' => $shipments], 200);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $shipments = ShipmentHeader::where('status', '!=', 'Invoiced')
            ->with(['origin', 'destination', 'mode', 'payment', 'carrier', 'type'])->get();
        return view('admin.shipment.index', [
            'shipments' => $shipments,
            'user' => auth()->user(),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $shipmentTypes = TypeOfShipment::all();
        $paymentModes = PaymentMode::all();
        $carriers = Carrier::all();
        $destinations = Destination::all();
        $modes = Mode::all();
        $origins = Origin::all();
        $items = Items::all();
        $selectedWarehouse = collect();
        $warehouses = Warehouse::all();
        if (!empty($request->warehouseId)) {
            $selectedWarehouse = Warehouse::where('id', $request->warehouseId)->first();
            $items = Items::where('warehouse_id', $selectedWarehouse->id)->get();
        }


        return view('admin.shipment.create', [
            'shipmentTypes' => $shipmentTypes,
            'paymentModes' => $paymentModes,
            'carriers' => $carriers,
            'destinations' => $destinations,
            'modes' => $modes,
            'origins' => $origins,
            'items' => $items,
            'user' => auth()->user(),
            'warehouses' => $warehouses,
            'selectedWarehouse' => $selectedWarehouse
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
        $shipperDetails = $request->shipper_details;
        $receiverDetails = $request->receiver_details;
        $shipmentDetails = $request->shipment_details;
        $shipmentItems = $request->shipment_items;
        if ($shipmentDetails['shipment_number'] == '' || $shipmentDetails['shipment_number'] == null) {
            $shipmentNumber = 'KJM' . date('YmdHis');
        }

        try {
            DB::transaction(function () use ($shipmentNumber, $shipperDetails, $receiverDetails, $shipmentDetails, $shipmentItems, $request) {
                $shipmentHeader = new ShipmentHeader();
                $shipmentHeader->shipment_number = $shipmentNumber;
                $shipmentHeader->shipper_name = $shipperDetails['name'];
                $shipmentHeader->shipper_phone = $shipperDetails['phone'];
                $shipmentHeader->shipper_address = $shipperDetails['address'];
                $shipmentHeader->shipper_email = $shipperDetails['email'];

                $shipmentHeader->receiver_name = $receiverDetails['name'];
                $shipmentHeader->receiver_phone = $receiverDetails['phone'];
                $shipmentHeader->receiver_address = $receiverDetails['address'];
                $shipmentHeader->receiver_email = $receiverDetails['email'];
                $shipmentHeader->warehouse_id = $request->warehouse_id;
                $shipmentHeader->type_of_shipment_id = $shipmentDetails['type'];
                $shipmentHeader->payment_id = $shipmentDetails['payment'];
                $shipmentHeader->carrier_id = $shipmentDetails['carrier'];
                $shipmentHeader->departure_time = $shipmentDetails['departure_time'];
                $shipmentHeader->destination_id = $shipmentDetails['destination'];
                $shipmentHeader->pickup_date_time = $shipmentDetails['pick_up_date_time'];
                $shipmentHeader->courier = $shipmentDetails['courier'];
                $shipmentHeader->mode_id = $shipmentDetails['mode'];
                $shipmentHeader->total_freight = $shipmentDetails['total_freight'];
                $shipmentHeader->carrier_ref = $shipmentDetails['carrier_ref'];
                $shipmentHeader->origin_id = $shipmentDetails['origin'];
                $shipmentHeader->expected_delivery_date_time = $shipmentDetails['expected_delivery'];

                $shipmentHeader->status = $shipmentDetails['status'];
                $shipmentHeader->updated_by = auth()->user()->id;
                $shipmentHeader->remarks = $shipmentDetails['remarks'];

                $shipmentHeader->total_vol_weight = 0;
                $shipmentHeader->total_vol = 0;
                $shipmentHeader->total_actual_weight = 0;

                if ($shipmentHeader->save()) {
                    foreach ($shipmentItems as $item) {
                        $shipmentItem = new ShipmentItem();
                        $shipmentItem->shipment_header_id = $shipmentHeader->id;
                        $shipmentItem->description = $item['description'];
                        $shipmentItem->item_id = $item['item_id'];
                        $shipmentItem->item_name = $item['item_name'];
                        $shipmentItem->qty = $item['quantity'];
                        $shipmentItem->weight = $item['weight'];
                        $shipmentItem->length = $item['length'];
                        $shipmentItem->height = $item['height'];
                        $shipmentItem->width = $item['width'];
                        $shipmentItem->total_weight = $item['total_weight'];
                        $shipmentItem->price = $item['price'];
                        $shipmentItem->total_price = $item['total_price'];
                        $shipmentItem->save();
                    }
                };

                $totalData = ShipmentItem::where('shipment_header_id', $shipmentHeader->id)->get();

                $totalLength = $totalData->sum('length');
                $totalWidth = $totalData->sum('width');
                $totalHeight = $totalData->sum('height');
                $totalWeight = $totalData->sum('total_weight');
                $totalPrice = $totalData->sum('total_price');

                $totalPriceVat = $totalPrice;
                if ($shipmentDetails['vat']) {
                    $totalPriceVat = $totalPrice * ((float) $shipmentDetails['vat'] / 100);
                }

                $totalVol = $totalLength * $totalWidth * $totalHeight;
                $totalVolW = $totalVol / $totalWeight;

                $shipmentHeader->update([
                    'total_vol_weight' => $totalVolW,
                    'total_vol' =>  $totalVol,
                    'total_actual_weight' => $totalWeight,
                    'vat' => $shipmentDetails['vat'],
                    'total_price_vat' => $totalPriceVat,
                ]);
            });
        } catch (Throwable $e) {
            return response()->json([
                'error' => true,
                'message' => 'Sorry some error occurred while creating shipment!',
                'e' => $e->getMessage()
            ]);
        }
        return response()->json([
            'error' => false,
            'message' => 'Successfully create a new shipment!'
        ]);
    }

    /**
     * Display the specified resource
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $shipment = ShipmentHeader::where('id', $id)->with(['shipmentItems', 'shipmentItems.item', 'origin', 'destination', 'mode', 'payment', 'carrier', 'type', 'shipmentHistories.updatedBy'])->first();

        return view('admin.shipment.show', [
            'user' => auth()->user(),
            'shipment' => $shipment
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Request $request)
    {
        $shipment = ShipmentHeader::where('id', $id)->with(['shipmentItems', 'shipmentItems.item', 'origin', 'destination', 'mode', 'payment', 'carrier', 'type'])->first();
        $shipmentTypes = TypeOfShipment::all();
        $paymentModes = PaymentMode::all();
        $carriers = Carrier::all();
        $destinations = Destination::all();
        $modes = Mode::all();
        $origins = Origin::all();
        // $items = Items::all();
        $warehouse = Warehouse::where('id', $shipment->warehouse_id)
            ->with('items')
            ->first();

        $newWarehouse = collect();
        $warehouses = Warehouse::all();
        $newItems = collect();

        if (!empty($request->warehouseId)) {
            $newWarehouse = Warehouse::where('id', $request->warehouseId)
                ->with('items')
                ->first();

            $newItems = $newWarehouse->items;
        }

        return view('admin.shipment.edit', [
            'shipmentTypes' => $shipmentTypes,
            'paymentModes' => $paymentModes,
            'carriers' => $carriers,
            'destinations' => $destinations,
            'modes' => $modes,
            'origins' => $origins,
            'items' => $warehouse->items,
            'newItems' => $newItems,
            'user' => auth()->user(),
            'shipment' => $shipment,
            'warehouse' => $warehouse,
            'warehouses' => $warehouses
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $shipmentHeader = ShipmentHeader::where('id', $request->shipment_id)->first();
        if (empty($shipmentHeader)) {
            return response()->json(['message' => 'Shipment not found'], 422);
        }

        try {
            DB::transaction(function () use ($shipmentHeader, $request) {
                $shipperDetails = $request->shipper_details;
                $receiverDetails = $request->receiver_details;
                $shipmentDetails = $request->shipment_details;
                $shipmentItems = $request->shipment_items;

                if ($shipmentDetails['shipment_number'] == '') {
                    $shipmentNumber = 'KJM' . date('YmdHis');
                }

                $shipmentNumber = $shipmentDetails['shipment_number'];
                $shipmentHeader->update([
                    'shipment_number' => $shipmentNumber,
                    'shipper_name' => $shipperDetails['name'],
                    'shipper_phone' => $shipperDetails['phone'],
                    'shipper_address' => $shipperDetails['address'],
                    'shipper_email' => $shipperDetails['email'],
                    'receiver_name' => $receiverDetails['name'],
                    'receiver_phone' => $receiverDetails['phone'],
                    'receiver_address' => $receiverDetails['address'],
                    'receiver_email' => $receiverDetails['email'],
                    'type_of_shipment_id' => $shipmentDetails['type'],
                    'payment_id' => $shipmentDetails['payment'],
                    'warehouse_id' => $request->warehouse_id,
                    'carrier_id' => $shipmentDetails['carrier'],
                    'departure_time' => $shipmentDetails['departure_time'],
                    'destination_id' => $shipmentDetails['destination'],
                    'courier' => $shipmentDetails['courier'],
                    'mode_id' => $shipmentDetails['mode'],
                    'total_freight' => $shipmentDetails['total_freight'],
                    'carrier_ref' => $shipmentDetails['carrier_ref'],
                    'origin_id' => $shipmentDetails['origin'],
                    'pickup_date_time' => $shipmentDetails['pick_up_date_time'],
                    'expected_delivery_date_time' => $shipmentDetails['expected_delivery'],
                    'remarks' => $shipmentDetails['remarks'],
                ]);

                if ($shipmentHeader->status == 'Draft' || $shipmentHeader->status == 'Warehouse Confirmation') {
                    $shipmentItemsDelete = ShipmentItem::where('shipment_header_id', $shipmentHeader->id)->get();

                    if (count($shipmentItemsDelete) > 0)
                        foreach ($shipmentItemsDelete as $shipmentItem) {
                            $shipmentItem->forceDelete();
                        }

                    foreach ($shipmentItems as $item) {
                        $shipmentItem = new ShipmentItem();
                        $shipmentItem->shipment_header_id = $shipmentHeader->id;
                        $shipmentItem->description = $item['description'];
                        $shipmentItem->item_id = $item['item_id'];
                        $shipmentItem->item_name = $item['item_name'];
                        $shipmentItem->qty = $item['quantity'];
                        $shipmentItem->weight = $item['weight'];
                        $shipmentItem->length = $item['length'];
                        $shipmentItem->height = $item['height'];
                        $shipmentItem->width = $item['width'];
                        $shipmentItem->total_weight = $item['total_weight'];
                        $shipmentItem->price = $item['price'];
                        $shipmentItem->total_price = $item['total_price'];
                        $shipmentItem->save();
                    }

                    $totalData = ShipmentItem::where('shipment_header_id', $shipmentHeader->id)->get();

                    $totalLength = $totalData->sum('length');
                    $totalWidth = $totalData->sum('width');
                    $totalHeight = $totalData->sum('height');
                    $totalWeight = $totalData->sum('total_weight');
                    $totalPrice = $totalData->sum('total_price');

                    $totalPriceVat = $totalPrice;
                    if ($shipmentDetails['vat']) {
                        $totalPriceVat = $totalPrice * ((float) $shipmentDetails['vat'] / 100);
                    }

                    $totalVol = $totalLength * $totalWidth * $totalHeight;
                    $totalVolW = $totalVol / $totalWeight;
                    $shipmentHeader->update([
                        'total_vol_weight' => $totalVolW,
                        'total_vol' =>  $totalVol,
                        'total_actual_weight' => $totalWeight,
                        'vat' => $shipmentDetails['vat'],
                        'total_price_vat' => $totalPriceVat,
                    ]);
                }
            }, 2);
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }



        return response()->json(['message' => 'Successfully Edit Shipment'], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $shipment = ShipmentHeader::where('id', $id)->with('shipmentItems')->first();

        $shipmentItems = $shipment->shipmentItems;
        foreach ($shipmentItems as $shipmentItem) {
            $shipmentItem->delete();
        }

        $shipment->delete();

        return response()->json([
            'error' => false,
            'message' => 'Successfully delete shipment'
        ]);
    }

    public function manageStatus()
    {
        return view('admin.shipment.manage-status.index', [
            'user' => auth()->user()
        ]);
    }

    public function ajaxItems(Request $request)
    {
        $warehouse = Warehouse::where('id', $request->warehouseId)->with('items')->first();

        if (empty($warehouse)) {
            return response()->json(['This warehouse does not exist'], 500);
        }

        return response()->json([
            'selectedWarehouse' => $warehouse,
            'items' => $warehouse->items
        ], 200);
    }

    public function addHistory($shipment_id, Request $request)
    {
        $shipmentHeader = ShipmentHeader::where('id', $shipment_id)
            ->with('shipmentHistories')
            ->first();

        if (empty($shipmentHeader)) {
            return response()->json(['message' => 'Shipment not found'], 422);
        }

        try {
            DB::transaction(function () use ($shipmentHeader, $request) {
                $shipmentHistories = collect();
                foreach ($request->shipmentHistories as $newHistory) {
                    $shipmentHistories[] = ShipmentHistories::create([
                        'shipment_header_id' => $shipmentHeader->id,
                        'date' => $newHistory['date'],
                        'time' => $newHistory['time'],
                        'location_history' => $newHistory['location'],
                        'status' => $newHistory['status'],
                        'updated_by' => auth()->user()->id,
                        'remarks' => $newHistory['remarks']
                    ]);
                }

                $shipmentHistories = $shipmentHistories->last();
                $shipmentHeader->update([
                    'status' => $shipmentHistories->status,
                    'remarks' => $shipmentHistories->remarks
                ]);
            }, 2);
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Successfully add history to this shipment'], 200);
    }

    public function deleteHistory($history_id, Request $request)
    {
        $shipmentHistory = ShipmentHistories::where('id', $history_id)
            ->with('shipment')
            ->first();

        if (empty($shipmentHistory)) {
            return response()->json(['message' => 'History not found'], 422);
        }

        $shipmentHeader = $shipmentHistory->shipment;

        try {
            DB::transaction(function () use ($shipmentHistory, $shipmentHeader) {
                $shipmentHistory->delete();

                $lastHistory = ShipmentHistories::whereNull('deleted_at')->where('shipment_header_id', $shipmentHistory->shipment_header_id)->get()->last();

                $shipmentHeader->update([
                    'status' => $lastHistory->status,
                    'remarks' => $lastHistory->remarks
                ]);
            }, 2);
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Successfully delete history'], 200);
    }

    public function editHistory($history_id, Request $request)
    {
        $shipmentHistory = ShipmentHistories::where('id', $history_id)->first();

        if (empty($shipmentHistory)) {
            return response()->json(['message' => 'History not found'], 422);
        }

        $shipmentHeader = ShipmentHeader::where('id', $shipmentHistory->shipment_header_id)
            ->with('shipmentHistories')
            ->first();

        if (empty($shipmentHeader)) {
            return response()->json(['message' => 'Shipment not found'], 422);
        }

        try {
            DB::transaction(function () use ($shipmentHistory, $request, $shipmentHeader) {
                $shipmentHistory->update([
                    'date' => $request->date,
                    'time' => $request->time,
                    'location_history' => $request->location,
                    'status' => $request->status,
                    'remarks' => $request->remarks
                ]);

                // check last history
                $lastHistory = $shipmentHeader->shipmentHistories->last();
                if ($shipmentHistory->id == $lastHistory->id) {
                    $shipmentHeader->update([
                        'status' => $shipmentHistory->status,
                        'remarks' => $shipmentHistory->remarks
                    ]);
                }
            }, 2);
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

        return response()->json(['message' => $shipmentHeader->shipmentHistories->last()], 200);
    }

    public function publish($id)
    {
        $shipmentHeader = ShipmentHeader::where('id', $id)->first();

        if (empty($shipmentHeader)) {
            return response()->json(['message' => 'Shipment not found'], 422);
        }

        try {
            $shipmentHeader->update([
                'status' => 'Warehouse Confirmation'
            ]);
        } catch (Throwable $e) {
            return response()->json(['message' => 'Sorry something went wrong'], 500);
        }

        return response()->json(['message' => 'Successfully publish this shipment'], 200);
    }

    public function requestAPI($shipment_number, Request $request)
    {
        $shipment = ShipmentHeader::where('shipment_number', $shipment_number)
            ->with(['shipmentItems', 'origin', 'destination', 'payment', 'carrier', 'mode', 'type', 'warehouse', 'shipmentHistories'])
            ->first();

        if (empty($shipment)) {
            return response()->json(['message' => 'Shipment not found'], 422);
        }

        return response()->json($shipment, 200);
    }
}
