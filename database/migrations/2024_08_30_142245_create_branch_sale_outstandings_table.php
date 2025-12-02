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
        Schema::create('branch_sale_outstandings', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('branch_sale_id');
            $table->unsignedBigInteger('branch_id');
            $table->date('date');
            $table->decimal('outstanding_balance', 15, 2)->default(0);
            $table->decimal('extra_money', 15, 2)->default(0);
            $table->timestamps();

            $table->foreign('branch_sale_id')->references('id')->on('branch_sales')->onDelete('cascade');
            $table->foreign('branch_id')->references('id')->on('branches')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_sale_outstandings');
    }
};
