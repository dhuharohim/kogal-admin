<?php

namespace App\Services;

use App\Models\ShipmentLogActivity;
use Throwable;

class ShipmentActivity 
{
    public function createLog($action, $shipmentId, $description, $actionBy) 
    {
        try{
            ShipmentLogActivity::create([
                'action' => $action,
                'action_by' => $actionBy,
                'shipment_id' => $shipmentId,
                'description' => $description
            ]);
        } catch(Throwable $e) {
            return false;
        }
        
        return true;
    }
}