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
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100)->nullable(false);
            $table->string('type', 100)->nullable(false);
            $table->longText('description')->nullable();
            $table->string('image_Path')->nullable();
            $table->unsignedBigInteger('admin_id')->nullable(false);
            $table->timestamps();

            $table->foreign('admin_id')->on('admins')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('assets');
    }
};
