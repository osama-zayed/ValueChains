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
    // الإجراءات
    Schema::create('procedures', function (Blueprint $table) {
        $table->id();
        $table->string('name');
        $table->string('hijri_created_at')->nullable();
        $table->foreignId('domain_id')->constrained('domains');
        $table->foreignId('chain_id')->constrained('chains');
        $table->foreignId('project_id')->constrained('projects');
        $table->foreignId('activity_id')->constrained('activities');
        $table->foreignId('user_id')->constrained('users');
        $table->decimal('procedure_weight', 5, 2); // وزن الإجراء
        $table->integer('procedure_duration_days'); // مدة الإجراء بالأيام
        $table->date('procedure_start_date'); // بداية تنفيذ الإجراء
        $table->date('procedure_end_date'); // نهاية تنفيذ الإجراء
        $table->decimal('cost', 10, 2); // التكلفة
        $table->string('funding_source'); // مصدر التمويل
        $table->boolean('status'); // الحالة
        $table->string('attached_file')->nullable(); // مرفق ملف
        $table->foreignId('ring_id')->references('id')->on('rings');
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('procedures');
    }
};
