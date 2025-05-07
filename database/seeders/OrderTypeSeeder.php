<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderTypeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('ordertype')->insert([
            ['id_OrderType' => 1, 'name' => 'Return'],
            ['id_OrderType' => 2, 'name' => 'Issue'],
        ]);
    }
}
