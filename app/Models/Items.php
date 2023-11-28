<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
        'total_weight',
        'most_ordered_item'
    ];

    public function getMostOrderedItemAttribute()
    {
        // Menggunakan query builder untuk mengambil item yang paling sering dipesan
        $mostOrderedItem = $this->select('item_name', DB::raw('SUM(quantity) as total_ordered'))
            ->groupBy('item_name')
            ->orderByDesc('total_ordered')
            ->first();

        return $mostOrderedItem ? $mostOrderedItem->item_name : 'No orders yet';
    }

    public function getTotalWeightAttribute()
    {
        return $this->weight * $this->quantity;
    }

    public function warehouse() {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }
}
