<?php

namespace Database\Seeders;

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
        // Admin::create([
        //     'name' => 'test',
        //     'email' => 'test@gmail.com',
        //     'username' => 'test',
        //     'password' => Hash::make('test'),
        //     'role' => 'logistik',
        //     'token' => 'test'
        // ]);

        Admin::factory(3)->create();
    }
}
