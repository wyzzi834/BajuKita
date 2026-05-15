<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::updateOrCreate([
            'email' => env('ADMIN_EMAIL', 'admin@bajukita.test'),
        ], [
            'name' => env('ADMIN_NAME', 'Admin BajuKita'),
            'role' => 'admin',
            'email_verified_at' => now(),
            'password' => Hash::make(env('ADMIN_PASSWORD', 'admin12345')),
            'remember_token' => Str::random(10),
        ]);

        User::updateOrCreate([
            'email' => env('OPERATOR_EMAIL', 'operator@bajukita.test'),
        ], [
            'name' => env('OPERATOR_NAME', 'Operator BajuKita'),
            'role' => 'operator',
            'email_verified_at' => now(),
            'password' => Hash::make(env('OPERATOR_PASSWORD', 'operator12345')),
            'remember_token' => Str::random(10),
        ]);
    }
}
