<?php

namespace Database\Seeders;

use App\Models\PayMethod;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PaymentSeeder extends Seeder
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
                'id' => 1, // owner
                'bisnis_id' => 1,
                'cabang_id' => 1,
                'name' => 'Tunai',
                'tipe' => 'lainnya',
                'norek' => 0,
                'is_active' => true
            ],
            [
                'id' => 2, // owner
                'bisnis_id' => 1,
                'cabang_id' => 1,
                'name' => 'BCA',
                'tipe' => 'Transfer Bank',
                'norek' => 0,
                'is_active' => true
            ],
        ];

        PayMethod::insert($data);
    }
}
