<?php

namespace Database\Seeders;

use App\Enums\Major;
use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Student::create([
            'name' => "Marcellinus Yovian Indrastata",
            'nim' => '1202210019',
            'major' => Major::TEKNOLOGI_INFORMASI->value,
            'email' => 'marcyoin@gmail.com',
            'username' => 'yann',
            'password' => Hash::make('password'),
            'token' => 'token'
        ]);
        Student::factory(50)->create();
    }
}
