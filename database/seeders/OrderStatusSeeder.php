<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderStatusSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('orderstatus')->insert([
            ['id_OrderStatus' => 1, 'name' => 'Returned'],
            ['id_OrderStatus' => 2, 'name' => 'Issued'],
            ['id_OrderStatus' => 3, 'name' => 'Waiting'],
            ['id_OrderStatus' => 4, 'name' => 'Confirmed'],
        ]);
    }
}
