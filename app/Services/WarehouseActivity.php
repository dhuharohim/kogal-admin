<?php

namespace App\Services;

use App\Models\WarehouseActivityLog;
use Throwable;

class WarehouseActivity
{
    public static function createLog($action, $warehouseId, $description, $actionBy) 
    {
        try{
            WarehouseActivityLog::create([
                'action' => $action,
                'action_by' => $actionBy,
                'warehouse_id' => $warehouseId,
                'description' => $description
            ]);
        } catch(Throwable $e) {
            return false;
        }
        
        return true;
    }
}