<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RefUniversitasListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $universities = [
            ['universitas_name' => 'Universitas Gadjah Mada', 'singkatan' => 'UGM'],
            ['universitas_name' => 'Universitas Indonesia', 'singkatan' => 'UI'],
            ['universitas_name' => 'Universitas Sumatera Utara', 'singkatan' => 'USU'],
            ['universitas_name' => 'Universitas Airlangga', 'singkatan' => 'UNAIR'],
            ['universitas_name' => 'Universitas Hasanuddin', 'singkatan' => 'UNHAS'],
            ['universitas_name' => 'Universitas Andalas', 'singkatan' => 'UNAND'],
            ['universitas_name' => 'Universitas Padjadjaran', 'singkatan' => 'UNPAD'],
            ['universitas_name' => 'Universitas Diponegoro', 'singkatan' => 'UNDIP'],
            ['universitas_name' => 'Universitas Sriwijaya', 'singkatan' => 'UNSRI'],
            ['universitas_name' => 'Universitas Lambung Mangkurat', 'singkatan' => 'ULM'],
            ['universitas_name' => 'Universitas Syiah Kuala', 'singkatan' => 'USK'],
            ['universitas_name' => 'Universitas Sam Ratulangi', 'singkatan' => 'UNSRAT'],
            ['universitas_name' => 'Universitas Udayana', 'singkatan' => 'UNUD'],
            ['universitas_name' => 'Universitas Nusa Cendana', 'singkatan' => 'UNDANA'],
            ['universitas_name' => 'Universitas Mulawarman', 'singkatan' => 'UNMUL'],
            ['universitas_name' => 'Universitas Mataram', 'singkatan' => 'UNRAM'],
            ['universitas_name' => 'Universitas Riau', 'singkatan' => 'UNRI'],
            ['universitas_name' => 'Universitas Cenderawasih', 'singkatan' => 'UNCEN'],
            ['universitas_name' => 'Universitas Brawijaya', 'singkatan' => 'UB'],
            ['universitas_name' => 'Universitas Jambi', 'singkatan' => 'UNJA'],
            ['universitas_name' => 'Universitas Pattimura', 'singkatan' => 'UNPATTI'],
            ['universitas_name' => 'Universitas Tanjung Pura', 'singkatan' => 'UNTAN'],
            ['universitas_name' => 'Universitas Jenderal Soedirman', 'singkatan' => 'UNSOED'],
            ['universitas_name' => 'Universitas Palangka Raya', 'singkatan' => 'UPR'],
            ['universitas_name' => 'Universitas Jember', 'singkatan' => 'UNEJ'],
            ['universitas_name' => 'Universitas Lampung', 'singkatan' => 'UNILA'],
            ['universitas_name' => 'Universitas Sebelas Maret', 'singkatan' => 'UNS'],
            ['universitas_name' => 'Universitas Tadulako', 'singkatan' => 'UNTAD'],
            ['universitas_name' => 'Universitas Halu Oleo', 'singkatan' => 'UHO'],
            ['universitas_name' => 'Universitas Bengkulu', 'singkatan' => 'UNIB'],
            ['universitas_name' => 'Universitas Khairun', 'singkatan' => 'UNKHAIR'],
            ['universitas_name' => 'Universitas Papua', 'singkatan' => 'UNIPA'],
            ['universitas_name' => 'Universitas Malikussaleh', 'singkatan' => 'UNIMA'],
            ['universitas_name' => 'Universitas Pembangunan Nasional Veteran Jakarta', 'singkatan' => 'UPNVJ'],
            ['universitas_name' => 'Universitas Islam Sumatera Utara', 'singkatan' => 'UINSU'],
            ['universitas_name' => 'Universitas HKBP Nommensen', 'singkatan' => 'UHN'],
            ['universitas_name' => 'Universitas Muhammadiyah Sumatera Utara', 'singkatan' => 'UMSU'],
            ['universitas_name' => 'Universitas Methodist Indonesia', 'singkatan' => 'UMI'],
            ['universitas_name' => 'Universitas Prima Indonesia', 'singkatan' => 'UNPRI'],
            ['universitas_name' => 'Universitas Muhammadiyah Palembang', 'singkatan' => 'UMPalembang'],
            ['universitas_name' => 'Universitas Malahayati', 'singkatan' => 'UNMAL'],
            ['universitas_name' => 'Universitas Katolik Indonesia Atma Jaya', 'singkatan' => 'UNIKA Atma Jaya'],
            ['universitas_name' => 'Universitas Kristen Indonesia', 'singkatan' => 'UKI'],
            ['universitas_name' => 'Universitas Kristen Krida Wacana', 'singkatan' => 'UKRIDA'],
            ['universitas_name' => 'Universitas Muhammadiyah Jakarta', 'singkatan' => 'UMJ'],
            ['universitas_name' => 'Universitas Tarumanagara', 'singkatan' => 'UNTAR'],
            ['universitas_name' => 'Universitas Trisakti', 'singkatan' => 'USAKTI'],
            ['universitas_name' => 'Universitas Yarsi', 'singkatan' => 'YARSI'],
            ['universitas_name' => 'Universitas Pelita Harapan', 'singkatan' => 'UPH'],
            ['universitas_name' => 'Universitas Gunadarma', 'singkatan' => 'UG'],
            ['universitas_name' => 'Universitas Muhammadiyah Prof Dr Hamka', 'singkatan' => 'UHAMKA'],
            ['universitas_name' => 'Universitas Islam Bandung', 'singkatan' => 'UNISBA'],
            ['universitas_name' => 'Universitas Kristen Maranatha', 'singkatan' => 'UKM'],
            ['universitas_name' => 'Universitas Swadaya Gunung Djati', 'singkatan' => 'UGJ'],
            ['universitas_name' => 'Universitas Jenderal Achmad Yani', 'singkatan' => 'UNJAYA'],
            ['universitas_name' => 'Universitas Islam Indonesia', 'singkatan' => 'UII'],
        ];

        DB::table('ref_universitas_list')->insert($universities);
    }
}
