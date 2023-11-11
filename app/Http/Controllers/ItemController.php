<?php

namespace App\Http\Controllers;

use App\Models\Items;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class ItemController extends Controller
{
    public function getItem($id)
    {
        $item = Items::findOrFail($id); // Assuming your Item model is named 'Item'
        return response()->json($item);
    }


    public function index(Request $request)
    {
        $warehouses = Warehouse::whereNull('deleted_at')->get();
        $items = collect();
        $selectedWarehouse = collect();
        if (!empty($request->warehouseId)) {
            $items = Items::where('warehouse_id', $request->warehouseId)->whereNull('deleted_at')->get();
            $selectedWarehouse = Warehouse::where('id', $request->warehouseId)->first();

            // get capacity
            $itemsQty = $items->sum('quantity');
            $itemsWeight = $items->sum('weight');
            $itemsTotal = $itemsQty * $itemsWeight;
        }

        return view('admin.warehouse.items.index', [
            'user' => auth()->user(),
            'items' => $items,
            'warehouses' => $warehouses,
            'selectedWarehouse' => blank($selectedWarehouse) ? collect(['id' => 0, 'capacity' => 0]) : $selectedWarehouse,
            'itemsTotal' => $itemsTotal ?? 0
        ]);
    }

    public function change(Request $request)
    {
        $itemsData = $request->input('items');

        if(empty($itemsData)) {
            return response()->json(['message' => 'Item changes saved successfully']);
        }

        $warehouseItems = Items::where('warehouse_id', $request->warehouse_id)->get();


        try {

            DB::transaction(function () use ($warehouseItems, $request, $itemsData) {
                foreach ($warehouseItems as $deleteItem) {
                    $deleteItem->forceDelete();
                }
        
                foreach ($itemsData as $itemData) {
                    $item = new Items();
                    $item->warehouse_id = $request->warehouse_id;
                    $item->sku = $itemData['sku'];
                    $item->item_name = $itemData['item_name'];
                    $item->description = $itemData['description'];
                    $item->price = $itemData['price'];
                    $item->quantity = $itemData['quantity'];
                    $item->length = $itemData['length'];
                    $item->width = $itemData['width'];
                    $item->height = $itemData['height'];
                    $item->weight = $itemData['weight'];
                    $item->save();
                }
            });
            
        } catch(Throwable $e) {
            return response()->json([
                'error' => true,
                'message' => 'Item changes saved failed'
            ], 500);
        }
        return response()->json([
            'error' => false,
            'message' => 'Item changes saved successfully'
        ], 200);
    }
}
