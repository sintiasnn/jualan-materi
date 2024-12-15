<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class TryoutSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'tipe' => 'general',
                'time_limit' => 60, // Time limit in minutes
                'passing_grade' => 75,
                'soal_count' => 20,
                'pembahasan_url' => 'https://example.com/general-pembahasan',
                'deskripsi' => 'Tryout UKMPPD General untuk latihan soal.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'tipe' => 'postest',
                'time_limit' => 90, // Time limit in minutes
                'passing_grade' => 80,
                'soal_count' => 25,
                'pembahasan_url' => 'https://example.com/postest-pembahasan',
                'deskripsi' => 'Tryout UKMPPD Postest untuk menguji pemahaman setelah belajar.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'tipe' => 'pretest',
                'time_limit' => 45, // Time limit in minutes
                'passing_grade' => 70,
                'soal_count' => 15,
                'pembahasan_url' => 'https://example.com/pretest-pembahasan',
                'deskripsi' => 'Tryout UKMPPD Pretest untuk menguji pengetahuan awal.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
        ];

        // Insert data into the 'tryouts' table
        DB::table('tryouts')->insert($data);
    }
}
