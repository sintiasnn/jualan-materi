<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class TryoutPaketSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Sample data assuming IDs exist for 'paket_list' and 'tryouts'
        $data = [
            [
                'paket_id' => 1, // Assuming paket ID 1 exists
                'tryout_id' => 1, // General Tryout ID
                'activation_date' => Carbon::now(),
                'expiration_date' => Carbon::now()->addDays(30),
                'order' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'paket_id' => 1, // Assuming paket ID 1 exists
                'tryout_id' => 2, // Postest Tryout ID
                'activation_date' => Carbon::now(),
                'expiration_date' => Carbon::now()->addDays(30),
                'order' => 2,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'paket_id' => 2, // Assuming paket ID 2 exists
                'tryout_id' => 3, // Pretest Tryout ID
                'activation_date' => Carbon::now(),
                'expiration_date' => Carbon::now()->addDays(60),
                'order' => 1,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        // Insert data into the 'tryout_paket' table
        DB::table('tryout_paket')->insert($data);
    }
}
