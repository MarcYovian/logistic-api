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
            'name' => 'marcell',
            'email' => 'marcell@gmail.com',
            'username' => 'marcell',
            'password' => Hash::make('password'),
            'type' => AdminType::LOGISTIK->value,
            'token' => 'marcell'
        ]);

        Admin::factory(3)->create();
    }
}
