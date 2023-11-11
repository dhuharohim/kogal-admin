<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShipmentItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable= [
        'shipment_header_id',
        'qty',
        'price',
        'item_id',
        'description',
        'length',
        'width',
        'height',
        'weight',
        'total_weight',
        'total_price'
    ];

    protected $appends=[
        'is_item_available'
    ];

    public function getIsItemAvailableAttribute() {
        $warehouse_item = Items::where('id', $this->item_id)->first();
        $checkAvailable = true;
        if($this->qty > $warehouse_item->quantity) {
            $checkAvailable = false;
        }
        return $checkAvailable;
    }

    public function shipmentHeader() {
        return $this->belongsTo(ShipmentHeader::class, 'shipment_header_id', 'id');
    }

    public function item() {
        return $this->belongsTo(Items::class, 'item_id', 'id');
    }
}
