<?php

namespace Database\Seeders;

use App\Models\BorrowingDate;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BorrowingDateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        BorrowingDate::factory(200)->create();
    }
}
