<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WarehouseActivityLog extends Model
{
    use HasFactory;
    protected $table = 'warehouse_activity_log';

    protected $fillable = [
        'action',
        'action_by',
        'warehouse_id',
        'description',
    ];

    protected $appends = ['time_elapsed'];

    public function getTimeElapsedAttribute()
    {
        return $this->created_at->diffForHumans();
    }
    
    public function user() {
        return $this->belongsTo(User::class, 'action_by', 'id');
    }

    public function warehouse() 
    {
        return $this->belongsTo(Warehouse::class, 'warehouse_id', 'id');
    }
}
