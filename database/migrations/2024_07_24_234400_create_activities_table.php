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
        //الانشطة
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('hijri_created_at')->nullable();
            $table->decimal('target_value', 8, 2); // حقل القيمة المستهدفة
            $table->string('target_indicator'); // حقل مؤشر القيمة المستهدفة
            $table->decimal('activity_weight', 5, 2); // حقل وزن النشاط
            $table->foreignId('domain_id')->references('id')->on('domains');
            $table->foreignId('chain_id')->references('id')->on('chains');
            $table->foreignId('project_id')->references('id')->on('projects');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
