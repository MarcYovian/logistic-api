<?php

namespace Database\Seeders;

use App\Models\DetailBorrowing;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DetailBorrowingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DetailBorrowing::factory(100)->create();
    }
}
