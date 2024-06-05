<?php

use App\Enums\Major;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\Rules\Enum;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable(false);
            $table->string('nim', 12)->nullable(false);
            $table->enum('major', Major::values())->nullable(false);
            $table->string('email', 100)->nullable(false)->unique('student_email_unique');
            $table->string('username', 100)->nullable(false)->unique('student_username_unique');
            $table->string('password', 100)->nullable(false);
            $table->string('token', 100)->nullable()->unique('student_token_unique');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('students');
    }
};
