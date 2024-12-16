<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RedeemCode;
use Carbon\Carbon;

class RedeemCodeSeeder extends Seeder
{
    public function run()
    {
        $redeemCodes = [
            [
                'tipe' => 'paket',
                'code' => 'TEST100K',
                'discount_amount' => 100000,
                'max_quota' => 2,
                'used_quota' => 0,
                'activation_status' => false,
                'expiry_date' => Carbon::now()->addMonths(1),
            ],
            [
                'tipe' => 'paket',
                'code' => 'PROMO50K',
                'discount_amount' => 50000,
                'max_quota' => 5,
                'used_quota' => 0,
                'activation_status' => false,
                'expiry_date' => Carbon::now()->addMonths(2),
            ],
            [
                'tipe' => 'paket',
                'code' => 'EXPIRED25K',
                'discount_amount' => 25000,
                'max_quota' => 3,
                'used_quota' => 0,
                'activation_status' => false,
                'expiry_date' => Carbon::now()->subDays(1), // Already expired
            ],
            [
                'tipe' => 'paket',
                'code' => 'USED75K',
                'discount_amount' => 75000,
                'max_quota' => 1,
                'used_quota' => 1, // Already used
                'activation_status' => true,
                'expiry_date' => Carbon::now()->addMonths(1),
            ],
            [
                'tipe' => 'paket',
                'code' => 'QUOTA200K',
                'discount_amount' => 200000,
                'max_quota' => 3,
                'used_quota' => 2, // One use left
                'activation_status' => false,
                'expiry_date' => Carbon::now()->addMonths(3),
            ],
            [
                'tipe' => 'tryout',
                'code' => 'TRY150K',
                'discount_amount' => 150000,
                'max_quota' => 10,
                'used_quota' => 0,
                'activation_status' => false,
                'expiry_date' => Carbon::now()->addMonths(6),
            ],
            [
                'tipe' => 'class',
                'code' => 'CLASS80K',
                'discount_amount' => 80000,
                'max_quota' => 4,
                'used_quota' => 0,
                'activation_status' => false,
                'expiry_date' => Carbon::now()->addWeeks(2),
            ],
            [
                'tipe' => 'paket',
                'code' => 'ALMOST60K',
                'discount_amount' => 60000,
                'max_quota' => 5,
                'used_quota' => 4, // One use left
                'activation_status' => false,
                'expiry_date' => Carbon::now()->addDays(5),
            ],
            [
                'tipe' => 'paket',
                'code' => 'SOON40K',
                'discount_amount' => 40000,
                'max_quota' => 2,
                'used_quota' => 0,
                'activation_status' => false,
                'expiry_date' => Carbon::now()->addDays(1), // Expires tomorrow
            ],
            [
                'tipe' => 'paket',
                'code' => 'UNLIMITED30K',
                'discount_amount' => 30000,
                'max_quota' => 999, // Almost unlimited
                'used_quota' => 0,
                'activation_status' => false,
                'expiry_date' => Carbon::now()->addYear(),
            ],
        ];

        foreach ($redeemCodes as $code) {
            RedeemCode::create($code);
        }
    }
}
?>