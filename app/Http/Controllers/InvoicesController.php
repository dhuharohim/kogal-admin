<?php

namespace App\Http\Controllers;

use App\Models\InvoiceShipment;
use App\Models\InvoiceShipmentItem;
use App\Models\ShipmentHeader;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class InvoicesController extends Controller
{
    public function index()
    {
        $invoices = InvoiceShipment::with(['mode', 'type', 'origin', 'destination'])->get();

        return view('admin.shipment.invoices.index', [
            'user' => auth()->user(),
            'invoices' => $invoices
        ]);
    }

    public function create()
    {
        $shipments = ShipmentHeader::whereIn('status', ['Delivered', 'Returned'])->get();

        return view('admin.shipment.invoices.create', [
            'user' => auth()->user(),
            'shipments' => $shipments
        ]);
    }

    public function dataShipment($shipment_id)
    {
        $shipment = ShipmentHeader::where('id', $shipment_id)->first();

        if (empty($shipment)) {
            return response()->json(['message' => 'Shipment not found'], 422);
        }

        return response()->json(['shipment' => $shipment], 200);
    }

    public function multipleCreation(Request $request)
    {
        try {
            DB::transaction(function () use ($request) {
                $shipmentHeaders = [];
                foreach ($request->shipmentIds as $shipment) {
                    $shipment = ShipmentHeader::where('id', [$shipment['shipment_id']])
                        ->with('shipmentItems')
                        ->first();
                    $shipment->update([
                        'status' => 'Invoiced'
                    ]);

                    $shipmentHeaders[] = $shipment;
                }

                foreach ($shipmentHeaders as $header) {
                    $invoiceHeader = InvoiceShipment::create([
                        'shipment_header_id' => $header->id,
                        'shipment_number' => $header->shipment_number,
                        'invoice_number' => 'INV-' . $header->shipment_number,
                        'shipper_name' => $header->shipper_name,
                        'shipper_phone' => $header->shipper_phone,
                        'shipper_address' => $header->shipper_address,
                        'shipper_email' => $header->shipper_email,
                        'receiver_name' => $header->receiver_name,
                        'receiver_phone' => $header->receiver_phone,
                        'receiver_address' => $header->receiver_address,
                        'receiver_email' => $header->receiver_email,
                        'type_of_shipment_id' => $header->type_of_shipment_id,
                        'payment_id' => $header->payment_id,
                        'warehouse_id' => $header->warehouse_id,
                        'carrier_id' => $header->carrier_id,
                        'departure_time' => $header->departure_time,
                        'destination_id' => $header->destination_id,
                        'courier' => $header->courier,
                        'mode_id' => $header->mode_id,
                        'total_freight' => $header->total_freight,
                        'carrier_ref' => $header->carrier_ref,
                        'origin_id' => $header->origin_id,
                        'pickup_date_time' => $header->pickup_date_time,
                        'expected_delivery_date_time' => $header->expected_delivery_date_time,

                        'status' => 'Invoiced',
                        'updated_by' => auth()->user()->id,
                        'remarks' => $header->remarks,
                        'total_vol_weight' => $header->total_vol_weight,
                        'total_vol' => $header->total_vol,
                        'total_actual_weight' => $header->total_actual_weight,
                        'total_price' => $header->total_price,
                        'vat' => $header->vat,
                        'total_price_vat' => $header->total_price_vat
                    ]);

                    foreach ($header->shipmentItems as $item) {
                        InvoiceShipmentItem::create([
                            'invoice_shipment_id' => $invoiceHeader->id,
                            'qty' => $item->qty,
                            'price' => $item->price,
                            'item_id' => $item->item_id,
                            'item_name' => $item->item_name,
                            'description' => $item->description,
                            'length' => $item->length,
                            'width' => $item->width,
                            'height' => $item->height,
                            'weight' => $item->weight,
                            'total_weight' => $item->total_weight,
                            'total_price' => $item->total_price
                        ]);
                    }
                }
            }, 2);
        } catch (Throwable $e) {
            return response()->json(['message' => $e->getMessage()], 500);
        }

        return response()->json(['message' => 'Successfully converted'], 200);
    }

    public function show($invoice_id, Request $request)
    {
        $invoice = InvoiceShipment::where('id', $invoice_id)
            ->with(['origin', 'destination', 'payment', 'carrier', 'type', 'mode', 'invoiceItems.item'])
            ->first();

        if (empty($invoice)) {
            abort(404);
        }

        return view('admin.shipment.invoices.show', [
            'user' => auth()->user(),
            'invoice' => $invoice,
            'edit' => $request->edit == 1 ? true : false
        ]);
    }

    public function print($invoice_id)
    {
        $invoice = InvoiceShipment::where('id', $invoice_id)
            ->with(['origin', 'destination', 'payment', 'carrier', 'type', 'mode', 'invoiceItems.item'])
            ->first();

        if (empty($invoice)) {
            abort(404);
        }

        return view('admin.shipment.invoices.print', [
            'user' => auth()->user(),
            'invoice' => $invoice
        ]);
    }

    public function downloadPdf(Request $request)
    {
        $htmlInvoice = $request->input('html');
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($htmlInvoice);

        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $pdfOutput = $dompdf->output();

        // Set header respons untuk mengirimkan PDF sebagai unduhan
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="invoice.pdf"');
        echo $pdfOutput;

        return;
    }

    public function update($invoice_id, Request $request)
    {
        $invoice = InvoiceShipment::where('id', $invoice_id)->first();
        if (empty($invoice)) {
            return response()->json(['message' => 'Invoice not found'], 422);
        }

        try {
            DB::transaction(function () use ($invoice, $request) {
                $invoice->update([
                    'invoice_number' => $request->invoice_number,
                    'remarks' => $request->remarks
                ]);
            });
        } catch (Throwable $e) {
            return response()->json(['message' => 'Something went wrong'], 500);
        }

        return response()->json(['message' => 'Successfully change'], 200);
    }

    public function getInvoice($invoice_id)
    {
        $invoice = InvoiceShipment::where('id', $invoice_id)
            ->with(['origin', 'destination', 'payment', 'carrier', 'type', 'mode', 'invoiceItems.item'])
            ->first();

        if (empty($invoice)) {
            abort(404);
        }

        return view('admin.shipment.invoices.print', [
            'user' => auth()->user(),
            'invoice' => $invoice
        ]);
    }
}
