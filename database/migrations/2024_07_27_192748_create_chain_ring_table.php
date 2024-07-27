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
        Schema::create('chain_ring', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chain_id')->references('id')->on('chains');
            $table->foreignId('ring_id')->references('id')->on('rings');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chain_ring');
    }
};
