<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TransaksiUser; // Make sure to import the model
use Carbon\Carbon; // If you need to use Carbon for date handling

class TransaksiUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Creating 3 sample records
        TransaksiUser::create([
            'kode_transaksi' => 'TRX-' . time(), // Unique transaction code based on current timestamp
            'user_id' => 1, // Assuming '1' is a valid user ID, replace with a real user ID
            'paket_id' => 1, // Assuming '1' is a valid paket ID
            'total_amount' => 100000, // Price of the package
            'status' => 'pending', // Initial transaction status
            'created_at' => Carbon::now(), // Current timestamp
            'updated_at' => Carbon::now(), // Current timestamp
        ]);

        TransaksiUser::create([
            'kode_transaksi' => 'TRX-' . (time() + 1), // Unique transaction code
            'user_id' => 1, // Assuming '2' is another valid user ID
            'paket_id' => 2, // Assuming '2' is a valid paket ID
            'total_amount' => 200000, // Price for another package
            'status' => 'completed', // Another transaction status
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
