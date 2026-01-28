<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin
        User::factory()->create([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
            'role' => 'admin',
        ]);

        // User
        User::factory()->create([
            'name' => 'User Biasa',
            'email' => 'user@user.com',
            'password' => bcrypt('password'),
            'role' => 'user',
        ]);

        // Infos
        \DB::table('infos')->insert([
            ['text' => 'Sinergi, Warna, Harmoni, Prestasi', 'created_at' => now(), 'updated_at' => now()],
            ['text' => 'Menolak lupa untuk generasi masa depan', 'created_at' => now(), 'updated_at' => now()],
            ['text' => 'Kenali kisahnya, jaga nilai kemanusiaan', 'created_at' => now(), 'updated_at' => now()],
            ['text' => 'Bondowoso Melesat | Smakensa Hebat | Ok Dech', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }
}
