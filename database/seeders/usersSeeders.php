<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class usersSeeders extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Atasan User',
                'username' => 'atasan',
                'password' => Hash::make('passwordatasan'), // jangan lupa untuk mengganti password ini
                'role' => 'atasan',
                // 'remember_token' => str_random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin User',
                'username' => 'admin',
                'password' => Hash::make('passwordadmin'), // jangan lupa untuk mengganti password ini
                'role' => 'admin',
                // 'remember_token' => str_random(10),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
