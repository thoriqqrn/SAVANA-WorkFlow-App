<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->foreignId('department_id')->nullable()->after('program_id')->constrained()->nullOnDelete();
            $table->boolean('is_global')->default(false)->after('deadline');
        });

        // Update status column to include 'pending'
        // Note: Laravel doesn't support modifying enum directly, so we handle in code
    }

    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['department_id']);
            $table->dropColumn(['department_id', 'is_global']);
        });
    }
};
