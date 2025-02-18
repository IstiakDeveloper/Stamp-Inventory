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
        Schema::create('head_office_dues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('head_office_sale_id')->constrained('head_office_sales')->onDelete('cascade');
            $table->date('date');
            $table->decimal('due_amount', 10, 2);
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('head_office_dues');
    }
};
