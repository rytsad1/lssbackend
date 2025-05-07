<?php

namespace Database\Seeders;



use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StateSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('state')->insert([
            ['id_State' => 1, 'name' => 'Active'],
            ['id_State' => 2, 'name' => 'Removed'],
            ['id_State' => 3, 'name' => 'Blocked'],
        ]);
    }
}

