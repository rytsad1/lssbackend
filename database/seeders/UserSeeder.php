<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('user')->insert([
            [
                'id_User' => 1,
                'Name' => 'Testas',
                'Surname' => 'Vartotojas',
                'Email' => 'test@example.com',
                'Username' => 'testuser',
                'Password' => Hash::make('test1234'),
                'State' => 1, // turi egzistuoti toks State ID
                'fkOrderHistoryid_OrderHistory' => null, // gali būti dummy arba null jei nullable
                'fkBillOfLadingid_BillOfLading' => null,
            ],
        ]);
    }
}

