<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShipmentHistories extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable=[
        'shipment_header_id',
        'date',
        'time',
        'location_history',
        'status',
        'updated_by',
        'remarks'
    ];

    public function shipment()
    {
        return $this->belongsTo(ShipmentHeader::class, 'shipment_header_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by', 'id');
    }

}
