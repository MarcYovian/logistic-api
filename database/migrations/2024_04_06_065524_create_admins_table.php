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
        Schema::create('admins', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->string('email')->nullable(false)->unique('admin_email_unique');
            $table->string('username', 100)->nullable(false)->unique('admin_username_unique');
            $table->string('password', 100)->nullable(false);
            $table->enum('role', ['logistik', 'ssc']);
            $table->string('token', 100)->nullable()->unique('admin_token_unique');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('admins');
    }
};
