<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DomainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $domain = [
            [
                'code' => 1,
                'keterangan' => 'Farmakoterapi & Farmasi Klinis',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 2,
                'keterangan' => 'Manajemen & Pelayanan Farmasi',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 3,
                'keterangan' => 'Regulasi Farmasi',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 4,
                'keterangan' => 'Teknologi Farmasi',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 5,
                'keterangan' => 'Kimia Farmasi',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 6,
                'keterangan' => 'Biologi & Kedokteran Farmasi',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 7,
                'keterangan' => 'Farmasi Bahan Alam',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'code' => 8,
                'keterangan' => 'Farmasi Industri',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert ke tabel paket_list
        DB::table('domain')->insert($domain);
    }
}
