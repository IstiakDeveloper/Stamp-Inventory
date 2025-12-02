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
        Schema::create('total_stocks', function (Blueprint $table) {
            $table->id();
            $table->decimal('total_sets', 10, 5)->default(0);
            $table->decimal('total_pieces', 10, 5)->default(0);
            $table->timestamps();
        });

        \DB::table('total_stocks')->insert([
            'id' => 1,
            'total_sets' => 0,
            'total_pieces' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('total_stocks');
    }
};
