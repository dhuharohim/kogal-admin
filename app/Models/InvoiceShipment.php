<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceShipment extends Model
{
    use HasFactory;

    protected $fillable= [
        'shipment_header_id',
        'invoice_number',
        'shipment_number',
        'shipper_name',
        'shipper_phone',
        'shipper_address',
        'shipper_email',
        'receiver_name',
        'receiver_phone',
        'receiver_address',
        'receiver_email',
        'type_of_shipment_id',
        'payment_id',
        'warehouse_id',
        'carrier_id',
        'departure_time',
        'destination_id',
        'courier',
        'mode_id',
        'total_freight',
        'carrier_ref',
        'origin_id',
        'pickup_date_time',
        'expected_delivery_date_time',

        'status',
        'updated_by',
        'remarks',

        'total_vol_weight',
        'total_vol',
        'total_actual_weight',
        'total_price',
        'vat',
        'total_price_vat'
    ];

    public function origin()
    {
        return $this->belongsTo(Origin::class);
    }

    public function destination()
    {
        return $this->belongsTo(Destination::class);
    }

    public function payment()
    {
        return $this->belongsTo(PaymentMode::class, 'payment_id', 'id');
    }

    public function carrier()
    {
        return $this->belongsTo(Carrier::class);
    }

    public function mode()
    {
        return $this->belongsTo(Mode::class);
    }

    public function type()
    {
        return $this->belongsTo(TypeOfShipment::class, 'type_of_shipment_id', 'id');
    }

    public function warehouse() {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }

    public function shipmentHeader() 
    {
        return $this->belongsTo(ShipmentHeader::class, 'shipment_header_id', 'id');
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceShipmentItem::class, 'invoice_shipment_id');
    }
}
