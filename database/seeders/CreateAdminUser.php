<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class CreateAdminUser extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name'     => 'Admin User',
            'email'    => 'admin@gmail.com',
            'password' => Hash::make('admin'), // 替換為實際的密碼
        ]);
    }
}
