<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    // database/seeders/DatabaseSeeder.php

    public function run(): void
    {
        $this->call([
            StateSeeder::class,
            OrderStatusSeeder::class,
            CategoryTypeSeeder::class,
            OrderTypeSeeder::class,
            UserSeeder::class,
        ]);
    }

}
