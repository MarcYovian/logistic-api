<?php

use App\Enums\StatusBorrowing;
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
        Schema::create('detail_borrowings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('borrowing_id')->nullable(false);
            $table->unsignedBigInteger('asset_id')->nullable(false);
            $table->unsignedBigInteger('admin_id')->nullable();
            $table->timestamp('start_date')->nullable();
            $table->timestamp('end_date')->nullable();
            $table->text('description')->nullable();
            $table->integer('num')->nullable(false)->default(1);
            $table->enum('status', StatusBorrowing::values())->default(StatusBorrowing::PENDING->value);

            $table->foreign('borrowing_id')->references('id')->on('borrowings')->onDelete('cascade');
            $table->foreign('asset_id')->references('id')->on('assets')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('admins')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('detail_borrowings');
    }
};
