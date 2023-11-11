<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        // Admin
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        $types = [
            ['type_of_shipments' => 'Express'],
            ['type_of_shipments' => 'Regular'],
            ['type_of_shipments' => 'Overnight'],
            // Tambahkan tipe pengiriman lainnya di sini
        ];

        $paymentModes = [
            ['name_payment_mode' => 'Credit Card'],
            ['name_payment_mode' => 'Debit Card'],
            ['name_payment_mode' => 'PayPal'],
            // Tambahkan metode pembayaran lainnya di sini
        ];

        $carriers = [
            ['carrier_name' => 'FedEx'],
            ['carrier_name' => 'UPS'],
            ['carrier_name' => 'DHL'],
            // Tambahkan nama mobil carrier lainnya di sini
        ];

        $destinations = [
            ['destination_name' => 'New York'],
            ['destination_name' => 'Los Angeles'],
            ['destination_name' => 'Chicago'],
            // Tambahkan nama kota destinasi lainnya di sini
        ];

        $origins = [
            ['name_origin' => 'New York'],
            ['name_origin' => 'Los Angeles'],
            ['name_origin' => 'Chicago'],
            // Tambahkan nama kota destinasi lainnya di sini
        ];

        $modes = [
            ['mode' => 'Air Freight'],
            ['mode' => 'Sea Freight'],
            ['mode' => 'Land Freight'],
            // Tambahkan mode pengiriman lainnya di sini
        ];


        DB::table('origins')->insert($origins);
        DB::table('modes')->insert($modes);
        DB::table('destinations')->insert($destinations);
        DB::table('carriers')->insert($carriers);
        DB::table('payment_modes')->insert($paymentModes);
        DB::table('type_of_shipments')->insert($types);


        // $this->call(ShipmentHeaderSeeder::class);
        // $this->call(ShipmentItemSeeder::class);

    }
}
