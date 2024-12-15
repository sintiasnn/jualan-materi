<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaketListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pakets = [
            [
                'image' => 'default.jpg',
                'nama_paket' => 'Paket Tryout UKMPPD Batch 4',
                'audience' => 'ukmppd',
                'tipe' => 'tryout',
                'harga' => 150000,
                'discount' => 50000, // Discount
                'tier' => 'paid',
                'deskripsi' => 'Tryout untuk persiapan ujian UKMPPD batch 4 tahun 2024.',
                'active_status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => 'default.jpg',
                'nama_paket' => 'Paket Kelas Intensif AIPKI',
                'audience' => 'aipki',
                'tipe' => 'class',
                'harga' => 200000,
                'discount' => 25000, // Discount
                'tier' => 'paid',
                'deskripsi' => 'Kelas intensif AIPKI untuk tahun 2025.',
                'active_status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => 'default.jpg',
                'nama_paket' => 'Paket Preklinik Basic',
                'audience' => 'preklinik',
                'tipe' => 'class',
                'harga' => 0,
                'discount' => 0, // Gratis
                'tier' => 'free',
                'deskripsi' => 'Kelas dasar untuk mahasiswa preklinik.',
                'active_status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => 'default.jpg',
                'nama_paket' => 'Simulasi OSCE Batch 1',
                'audience' => 'osce',
                'tipe' => 'osce',
                'harga' => 250000,
                'discount' => 50000, // Discount
                'tier' => 'paid',
                'deskripsi' => 'Simulasi ujian OSCE batch 1.',
                'active_status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => 'default.jpg',
                'nama_paket' => 'Kelas Koas Intensif',
                'audience' => 'koas',
                'tipe' => 'class',
                'harga' => 175000,
                'discount' => 0, // No discount
                'tier' => 'paid',
                'deskripsi' => 'Kelas intensif untuk koas dalam menghadapi ujian klinik.',
                'active_status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        // Insert ke tabel paket_list
        DB::table('paket_list')->insert($pakets);
    }
}
