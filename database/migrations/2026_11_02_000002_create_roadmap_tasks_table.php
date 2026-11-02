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
        Schema::create('roadmap_tasks', function (Blueprint $table) {
            $table->id();
            $table->unsignedTinyInteger('phase_number'); // 1, 2, 3, 4, 5
            $table->string('phase_title'); // Tahap 1: Fondasi Konversi & Materi Presentasi
            $table->string('target_days'); // Hari 1 – 5
            $table->string('task_code'); // Task 1.1, Task 1.2
            $table->string('role'); // programmer, desainer, analis, tim
            $table->string('title');
            $table->text('description')->nullable();
            $table->string('deliverable')->nullable();
            $table->string('status')->default('pending'); // pending, in_progress, completed
            $table->timestamp('completed_at')->nullable();
            $table->text('notes')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            $table->index(['phase_number', 'sort_order']);
            $table->index('role');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roadmap_tasks');
    }
};
