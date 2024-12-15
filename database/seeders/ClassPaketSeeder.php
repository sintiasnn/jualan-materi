<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ClassPaketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'class_id' => 1,
                'paket_id' => 1,
                'activation_date' => Carbon::now(),
                'expiration_date' => Carbon::now()->addDays(30),
                'order' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'class_id' => 2,
                'paket_id' => 2,
                'activation_date' => Carbon::now(),
                'expiration_date' => Carbon::now()->addDays(45),
                'order' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'class_id' => 3,
                'paket_id' => 1,
                'activation_date' => Carbon::now(),
                'expiration_date' => Carbon::now()->addDays(60),
                'order' => 3,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'class_id' => 4,
                'paket_id' => 3,
                'activation_date' => Carbon::now(),
                'expiration_date' => Carbon::now()->addDays(30),
                'order' => 4,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'class_id' => 5,
                'paket_id' => 2,
                'activation_date' => Carbon::now(),
                'expiration_date' => Carbon::now()->addDays(90),
                'order' => 5,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        // Insert data into the 'class_paket' table
        DB::table('class_paket')->insert($data);
    }
}
