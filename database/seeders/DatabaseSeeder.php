<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call(RefUniversitasListSeeder::class);
        // $this->call(TransaksiUserTestSeeder::class);
        $this->call(PaketListSeeder::class);
        $this->call(TryoutSeeder::class);
        $this->call(TryoutPaketSeeder::class);
        $this->call(ClassSeeder::class);
        $this->call(ClassPaketSeeder::class);

    }
}
