<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Asset;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AssetSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // $admin = Admin::where('username', 'test')->first();
        // Asset::create([
        //     'name' => 'test',
        //     'type' => 'test',
        //     'description' => 'test',
        //     'image_Path' => 'test',
        //     'admin_id' => $admin->id
        // ]);

        Asset::factory(10)->create();
    }
}
