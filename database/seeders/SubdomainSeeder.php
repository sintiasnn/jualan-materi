<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubdomainSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subdomain = [
            [
                'domain_code' => 1,
                'code' => 'a',
                'keterangan' => 'Sistem Saraf & Psikiatri',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'domain_code' => 1,
                'code' => 'b',
                'keterangan' => 'Sistem Kardiovaskular',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'domain_code' => 1,
                'code' => 'c',
                'keterangan' => 'Sistem Pernapasan',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'domain_code' => 1,
                'code' => 'd',
                'keterangan' => 'Sistem Pencernaan',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'domain_code' => 1,
                'code' => 'e',
                'keterangan' => 'Sistem Hormon/Endokrin',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'domain_code' => 1,
                'code' => 'f',
                'keterangan' => 'Sistem Hematologi/Darah',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'domain_code' => 1,
                'code' => 'g',
                'keterangan' => 'Sistem Reproduksi',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'domain_code' => 1,
                'code' => 'h',
                'keterangan' => 'Sistem Metabolisme & Ekskresi',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'domain_code' => 1,
                'code' => 'i',
                'keterangan' => 'Sistem Imun',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'domain_code' => 1,
                'code' => 'j',
                'keterangan' => 'Sistem Indera',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'domain_code' => 1,
                'code' => 'k',
                'keterangan' => 'Sistem Muskuloskeletal',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'domain_code' => 1,
                'code' => 'l',
                'keterangan' => 'Sistem Infeksi',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'domain_code' => 1,
                'code' => 'm',
                'keterangan' => 'Onkologi',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'domain_code' => 2,
                'code' => 'a',
                'keterangan' => 'Manajemen Sediaan Farmasi',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'domain_code' => 2,
                'code' => 'b',
                'keterangan' => 'Manajemen Alat Kesehatan dan Rumah Tangga',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'domain_code' => 2,
                'code' => 'c',
                'keterangan' => 'Manajemen Apotek',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'domain_code' => 2,
                'code' => 'd',
                'keterangan' => 'Compounding & Dispensing',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'domain_code' => 2,
                'code' => 'e',
                'keterangan' => 'Swamedikasi',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'domain_code' => 2,
                'code' => 'f',
                'keterangan' => 'Farmakoekonomi',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'domain_code' => 2,
                'code' => 'g',
                'keterangan' => 'Farmasi Rumah Sakit',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'domain_code' => 3,
                'code' => 'a',
                'keterangan' => 'Cara Pembuatan Obat yang Baik (CPOB)',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'domain_code' => 3,
                'code' => 'b',
                'keterangan' => 'Regulasi Pembuatan Kosmetik',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'domain_code' => 3,
                'code' => 'c',
                'keterangan' => 'Registrasi Obat',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'domain_code' => 3,
                'code' => 'd',
                'keterangan' => 'Regulasi Distribusi Obat (CDOB)',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'domain_code' => 3,
                'code' => 'e',
                'keterangan' => 'Regulasi Praktik Pelayanan Kefarmasian',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],

            [
                'domain_code' => 4,
                'code' => 'a',
                'keterangan' => 'Farmasetika',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'domain_code' => 4,
                'code' => 'b',
                'keterangan' => 'Farmasi Fisik',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'domain_code' => 4,
                'code' => 'c',
                'keterangan' => 'Formulasi Teknologi Sediaan Padat',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'domain_code' => 4,
                'code' => 'd',
                'keterangan' => 'Formulasi Teknologi Sediaan Semisolid',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'domain_code' => 4,
                'code' => 'e',
                'keterangan' => 'Formulasi Teknologi Sediaan Cair',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'domain_code' => 4,
                'code' => 'f',
                'keterangan' => 'Formulasi Teknologi Sediaan Steril',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'domain_code' => 4,
                'code' => 'g',
                'keterangan' => 'Biofarmasetika',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'domain_code' => 4,
                'code' => 'h',
                'keterangan' => 'Sistem Penghantaran Obat',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'domain_code' => 5,
                'code' => 'a',
                'keterangan' => 'Kimia Analisis Konvensional',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'domain_code' => 5,
                'code' => 'b',
                'keterangan' => 'Kimia Analisis Modern',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'domain_code' => 5,
                'code' => 'c',
                'keterangan' => 'Kimia Organik',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'domain_code' => 5,
                'code' => 'd',
                'keterangan' => 'Kimia Farmasi Dasar',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'domain_code' => 6,
                'code' => 'a',
                'keterangan' => 'Farmakologi, Interaksi Obat, Kehamilan',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'domain_code' => 6,
                'code' => 'b',
                'keterangan' => 'Biologi Sel & Molekuler',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'domain_code' => 6,
                'code' => 'c',
                'keterangan' => 'Mikrobiologi Farmasi',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'domain_code' => 6,
                'code' => 'd',
                'keterangan' => 'Immunologi & Infeksius di Farmasi',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'domain_code' => 7,
                'code' => 'a',
                'keterangan' => 'Farmakognosi',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'domain_code' => 7,
                'code' => 'b',
                'keterangan' => 'Fitokimia',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'domain_code' => 7,
                'code' => 'c',
                'keterangan' => 'Teknologi Ekstraksi Bahan Alam',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'domain_code' => 7,
                'code' => 'd',
                'keterangan' => 'Obat Tradisional',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'domain_code' => 8,
                'code' => 'a',
                'keterangan' => 'Kualifikasi & Validasi',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'domain_code' => 8,
                'code' => 'b',
                'keterangan' => 'Validasi Metode Analisis',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'domain_code' => 8,
                'code' => 'c',
                'keterangan'=> 'Sarana Kritis Penunjang Farmasi',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'domain_code' => 8,
                'code' => 'd',
                'keterangan' => 'Stabilitas Produk Obat',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];
        // Insert ke tabel paket_list
        DB::table('subdomain')->insert($subdomain);
    }
}
