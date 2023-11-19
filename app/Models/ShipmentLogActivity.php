<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShipmentLogActivity extends Model
{
    use HasFactory;
    protected $table = 'shipment_log_activity';

    protected $fillable = [
        'action',
        'action_by',
        'shipment_id',
        'description',
    ];

    public function user() {
        return $this->belongsTo(User::class, 'action_by', 'id');
    }

    public function shipment() 
    {
        return $this->belongsTo(ShipmentHeader::class, 'shipment_id', 'id');
    }
}
