<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Asset;
use App\Models\BorrowingDate;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $this->call([
            AdminSeeder::class,
            AssetSeeder::class,
            StudentSeeder::class,
            BorrowingSeeder::class,
            DetailBorrowingSeeder::class
        ]);
    }
}
