<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'deskripsi' => 'Kelas persiapan UKMPPD yang mencakup materi lengkap dan sesi tanya jawab.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'deskripsi' => 'Kelas intensif Post-Test UKMPPD dengan simulasi ujian online.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'deskripsi' => 'Kelas Pre-Test untuk memastikan kesiapan sebelum ujian UKMPPD.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'deskripsi' => 'Kelas tambahan dengan materi pendalaman soal kasus klinis dan pembahasan solusi.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'deskripsi' => 'Kelas untuk membahas materi praktikum OSCE dengan metode pembelajaran interaktif.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        // Insert data into the 'classes' table
        DB::table('classes')->insert($data);
    }
}
