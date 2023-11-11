<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class InvoiceShipmentItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable= [
        'invoice_shipment_id',
        'qty',
        'price',
        'item_id',
        'item_name',
        'description',
        'length',
        'width',
        'height',
        'weight',
        'total_weight',
        'total_price'
    ];


    public function invoiceShipment() {
        return $this->belongsTo(InvoiceShipment::class, 'invoice_shipment_id', 'id');
    }

    public function item() {
        return $this->belongsTo(Items::class, 'item_id', 'id');
    }
}
