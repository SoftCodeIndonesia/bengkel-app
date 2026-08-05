<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        
        User::truncate();
        
        // Aktifkan kembali
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Buat user admin
        User::create([
            'name' => 'Admin Bengkel',
            'email' => 'admin@bengkel.com',
            'password' => Hash::make('admin123'),
            'email_verified_at' => now(),
        ]);
    }
}
