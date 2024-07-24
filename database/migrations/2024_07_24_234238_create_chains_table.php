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
        //السلاسل
        Schema::create('chains', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('Goals');
            $table->string('hijri_created_at')->nullable();
            $table->foreignId('domain_id')->references('id')->on('domains');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chains');
    }
};
