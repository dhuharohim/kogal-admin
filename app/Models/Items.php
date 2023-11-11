<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class Items extends Model
{
    use HasFactory;
    protected $fillable = [
        'warehouse_id',
        'sku',
        'item_name',
        'price',
        'description',
        'length',
        'width',
        'height',
        'weight',
        'quantity',
    ];

    protected $appends = [
        'total_weight'
    ];

    public function getTotalWeightAttribute()
    {
        return $this->weight * $this->quantity;
    }

    public function warehouse() {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }
}
