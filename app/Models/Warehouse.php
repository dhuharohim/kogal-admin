<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Warehouse extends Model
{
    use HasFactory;
    protected $fillable = [
        'code_warehouse',
        'name_warehouse',
        'street',
        'city',
        'province',
        'postal_code',
        'capacity',
        'work_phone',
        'email_warehouse',
    ];

    public function items() {
        return $this->hasMany(Items::class, 'warehouse_id', 'id');
    }
}
