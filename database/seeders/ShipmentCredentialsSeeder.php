<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ShipmentCredentialsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('shipment_credentials')->insert([
            'user_id' => '1',
            'email' => 'admin32@gmail.com',
            'password' => '123',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now()
        ]);
    }
}
