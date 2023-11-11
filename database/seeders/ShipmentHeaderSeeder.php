<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShipmentHeaderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($i = 1; $i <= 25; $i++) {
            DB::table('shipment_headers')->insert([
                'shipment_number' => 'SH00'.$i,
                'shipper_name' => 'Shipper '.$i,
                'shipper_phone' => '123-456-789'.$i,
                'shipper_address' => 'Address '.$i,
                'shipper_email' => 'shipper'.$i.'@example.com',
                'receiver_name' => 'Receiver '.$i,
                'receiver_phone' => '987-654-321'.$i,
                'receiver_address' => 'Address '.$i,
                'receiver_email' => 'receiver'.$i.'@example.com',
                'type' => 'Type '.$i,
                'payment_id' => $i,
                'carrier_id' => $i,
                'departure_time' => now(),
                'destination_id' => $i,
                'courier' => 'Courier '.$i,
                'mode_id' => $i,
                'total_freight' => $i * 100,
                'carrier_ref' => 'Ref '.$i,
                'origin_id' => $i,
                'pickup_date_time' => now(),
                'expected_delivery_date_time' => now()->addDays($i),
                'status' => 'Status '.$i,
                'updated_by' => $i,
                'remarks' => 'Remarks '.$i,
                'total_vol_weight' => $i * 0.5,
                'total_vol' => $i * 0.3,
                'total_actual_weight' => $i * 0.7,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
