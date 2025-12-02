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
        Schema::create('reject_or_frees', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->decimal('sets', 10, 5);
            $table->decimal('purchase_price_per_set', 15, 2);
            $table->decimal('purchase_price_total', 15, 2);
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reject_or_frees');
    }
};
