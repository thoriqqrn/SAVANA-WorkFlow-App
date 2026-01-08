<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop old evaluations table
        Schema::dropIfExists('evaluations');
        
        // Create new evaluations with 1-5 scale and dual evaluator
        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('evaluator_id')->constrained('users')->onDelete('cascade');
            $table->enum('evaluator_type', ['kabinet', 'bph']);
            $table->string('period', 50); // Q1 2026, Semester 1, etc
            
            // Criteria with 1-5 scale
            $table->tinyInteger('kehadiran')->default(1); // 1-5
            $table->tinyInteger('kedisiplinan')->default(1);
            $table->tinyInteger('tanggung_jawab')->default(1);
            $table->tinyInteger('kerjasama')->default(1);
            $table->tinyInteger('inisiatif')->default(1);
            $table->tinyInteger('komunikasi')->default(1);
            
            $table->decimal('total_score', 3, 2)->default(0); // Average 1-5
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Unique constraint: 1 evaluator type per user per period
            $table->unique(['user_id', 'evaluator_type', 'period']);
        });
        
        // Grade parameters table
        Schema::create('grade_parameters', function (Blueprint $table) {
            $table->id();
            $table->decimal('min_score', 3, 2);
            $table->decimal('max_score', 3, 2);
            $table->string('grade', 2); // A, B, C, D, E
            $table->string('label'); // Sangat Baik, Baik, etc
            $table->string('color', 7)->default('#10B981'); // Hex color
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grade_parameters');
        Schema::dropIfExists('evaluations');
    }
};
