<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('evaluation_criteria', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->unsignedTinyInteger('max_score')->default(100);
            $table->unsignedTinyInteger('weight')->default(1); // Weight for total calculation
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('evaluations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('evaluator_id')->constrained('users')->cascadeOnDelete();
            $table->string('period')->nullable(); // e.g., "Q1 2026", "January 2026"
            $table->unsignedTinyInteger('discipline')->default(0);
            $table->unsignedTinyInteger('responsibility')->default(0);
            $table->unsignedTinyInteger('teamwork')->default(0);
            $table->unsignedTinyInteger('initiative')->default(0);
            $table->unsignedSmallInteger('total_score')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('evaluations');
        Schema::dropIfExists('evaluation_criteria');
    }
};
