<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = [
            [
                'id' => 1,
                'role_id' => 1,
                'bisnis_id' => 1,
                'cabang_id' => 1,
                'name' => 'Visca Putra',
                'email' => 'bimasaktiputra95@gmail.com',
                'email_verified_at' => '2023-06-20 20:27:00',
                'password' => Hash::make('11223344'),
            ],
            [
                'id' => 2,
                'role_id' => 2,
                'bisnis_id' => 1,
                'cabang_id' => 1,
                'name' => 'Dodo Kencana',
                'email' => 'mrvisca2018@gmail.com',
                'email_verified_at' => '2023-06-20 20:27:00',
                'password' => Hash::make('11223344'),
            ],
        ];

        User::insert($data);
    }
}
