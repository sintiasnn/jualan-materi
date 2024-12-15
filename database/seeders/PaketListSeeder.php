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
                'image' => 'paket-tryout1.jpg',
                'nama_paket' => 'Paket Tryout UKMPPD Batch 1 2025',
                'audience' => 'ukmppd',
                'tipe' => 'tryout',
                'harga' => 150000,
                'discount' => 50000, // Discount
                'tier' => 'paid',
                'deskripsi' => 'Tryout untuk persiapan ujian UKMPPD batch 1 tahun 2025.',
                'active_status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => 'paket-tryout1.jpg',
                'nama_paket' => 'Paket Tryout UKMPPD Batch 2 2025',
                'audience' => 'ukmppd',
                'tipe' => 'class',
                'harga' => 200000,
                'discount' => 0, // Discount
                'tier' => 'paid',
                'deskripsi' => 'Tryout untuk persiapan ujian UKMPPD batch 2 tahun 2025.',
                'active_status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => 'paket-kelas1.jpg',
                'nama_paket' => 'Paket Kelas Intensif UKMPPD Batch 1 2025',
                'audience' => 'ukmppd',
                'tipe' => 'class',
                'harga' => 1000000,
                'discount' => 0, // Gratis
                'tier' => 'free',
                'deskripsi' => 'Kelas intensif untuk mahasiswa UKMPPD batch 1 2025.',
                'active_status' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'image' => 'paket-kelas1.jpg',
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
                'image' => 'paket-kelas1.jpg',
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
