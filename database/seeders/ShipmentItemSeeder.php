<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShipmentItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $shipmentHeaderIds = DB::table('shipment_headers')->pluck('id');

        foreach ($shipmentHeaderIds as $shipmentHeaderId) {
            // Membuat 3 data shipment_item untuk setiap shipment_header
            for ($i = 1; $i <= 3; $i++) {
                DB::table('shipment_items')->insert([
                    'shipment_header_id' => $shipmentHeaderId,
                    'name_item' => 'name'.$i,
                    'qty' => $i,
                    'piece_id' => $i,
                    'description' => 'Description ' . $i,
                    'length' => $i * 10,
                    'width' => $i * 5,
                    'height' => $i * 3,
                    'weight' => $i * 2,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
        }
    }
}
