<?php

namespace Database\Seeders;

use App\Models\Bisnis;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BisnisSeeder extends Seeder
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
                'owner_id' => 1,
                'name' => 'Visca Corporation',
                'default_warehouse' => 1
            ]
        ];

        Bisnis::insert($data);
    }
}
