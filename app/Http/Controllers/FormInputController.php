<?php

namespace App\Http\Controllers;

use App\Models\Carrier;
use App\Models\Destination;
use App\Models\Items;
use App\Models\Mode;
use App\Models\Origin;
use App\Models\PaymentMode;
use App\Models\TypeOfShipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class FormInputController extends Controller
{
    public function index()
    {
        $modes = Mode::get();
        $typeOfShipments = TypeOfShipment::get();
        $paymentModes = PaymentMode::get();
        $carriers = Carrier::get();
        $origins = Origin::get();
        $destinations = Destination::get();
        $items = Items::get();

        return view('admin.cms.form-input.index', [
            'user' => auth()->user(),
            'modes' => $modes,
            'typeOfShipments' => $typeOfShipments,
            'paymentModes' => $paymentModes,
            'carriers' => $carriers,
            'origins' => $origins,
            'destinations' => $destinations,
            'items' => $items,
        ]);
    }

    public function storeShipmentForm(Request $request)
    {
        try {
            // Truncate semua tabel terkait
            Mode::truncate();
            TypeOfShipment::truncate();
            PaymentMode::truncate();
            Carrier::truncate();
            Origin::truncate();
            Destination::truncate();

            // Ambil data dari request dan buat ulang data
            $modesData = $request->input('modes');
            foreach ($modesData as $modeName) {
                Mode::create(['mode' => $modeName]);
            }

            $typeOfShipmentsData = $request->input('types');
            foreach ($typeOfShipmentsData as $typeOfShipmentName) {
                TypeOfShipment::create(['type_of_shipments' => $typeOfShipmentName]);
            }

            $paymentModesData = $request->input('payments');
            foreach ($paymentModesData as $paymentModeName) {
                PaymentMode::create(['name_payment_mode' => $paymentModeName]);
            }

            $carriersData = $request->input('carriers');
            foreach ($carriersData as $carrierName) {
                Carrier::create(['carrier_name' => $carrierName]);
            }

            $originsData = $request->input('origins');
            foreach ($originsData as $originName) {
                Origin::create(['name_origin' => $originName]);
            }

            $destinationsData = $request->input('destinations');
            foreach ($destinationsData as $destinationName) {
                Destination::create(['destination_name' => $destinationName]);
            }
        } catch (Throwable $e) {
            return response()->json([
                'error' => true,
                'message' => 'Sorry! Something went wrong'
            ]);
        }

        return response()->json([
            'error' => false,
            'message' => 'Data saved successfully'
        ]);
    }

    public function saveItem(Request $request)
    {
        Items::truncate();

        $data = $request->input('data');

        foreach ($data as $itemData) {
            // Membuat ulang data item dari data yang diterima
            Items::create([
                'item_name' => $itemData['itemName'],
                'description' => $itemData['description'],
                'length' => $itemData['length'],
                'width' => $itemData['width'],
                'height' => $itemData['height'],
                'weight' => $itemData['weight'],
            ]);
        }

        return response()->json([
            'error' => false,
            'message' => 'Data saved successfully'
        ]);
    }
}
