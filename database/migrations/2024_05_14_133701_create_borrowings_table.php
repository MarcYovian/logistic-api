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
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('student_id')->nullable(false);
            $table->string('ukm_name', 100)->nullable(false);
            $table->string('event_name', 200)->nullable(false);
            $table->integer('num_of_participants')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();

            $table->foreign('student_id')->references('id')->on('students')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};
