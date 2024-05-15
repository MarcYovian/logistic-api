<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('college_students', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable(false);
            $table->string('nim', 20)->nullable(false);
            $table->string('major', 100)->nullable(false);
            $table->string('email', 100)->nullable(false)->unique('college_student_email_unique');
            $table->string('username', 100)->nullable(false)->unique('college_student_username_unique');
            $table->string('password', 100)->nullable(false);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('college_students');
    }
};
