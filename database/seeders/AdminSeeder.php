<?php

namespace Database\Seeders;

use App\Enums\AdminType;
use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'type' => AdminType::SUPERUSER->value,
            'token' => 'admin',
            'is_active' => true
        ]);
        Admin::create([
            'name' => 'marcell',
            'email' => 'marcell@gmail.com',
            'username' => 'marcell',
            'password' => Hash::make('password'),
            'type' => AdminType::LOGISTIK->value,
            'token' => 'marcell',
            'is_active' => true
        ]);
        Admin::create([
            'name' => 'ferdy',
            'email' => 'ferdy@gmail.com',
            'username' => 'ferdy',
            'password' => Hash::make('password'),
            'type' => AdminType::SSC->value,
            'token' => 'ferdy',
            'is_active' => true
        ]);

        Admin::factory(3)->create();
    }
}
