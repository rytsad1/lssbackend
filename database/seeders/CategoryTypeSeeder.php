<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategoryTypeSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categorytype')->insert([
            ['id_CategoryType' => 1, 'name' => 'Weapon'],
            ['id_CategoryType' => 2, 'name' => 'Uniform'],
            ['id_CategoryType' => 3, 'name' => 'Eqiupment'],
            ['id_CategoryType' => 4, 'name' => 'Office'],
        ]);
    }
}

