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
        Schema::create('project_stages', function (Blueprint $table) {
            $table->id();

            $table->foreignId('project_id')->constrained()->cascadeOnDelete();

            $table->string('stage_name');

            $table->integer('weight_percent');

            $table->enum('status', [
                'todo',
                'in_progress',
                'pending_approval',
                'approved',
                'rejected'
            ])->default('todo');

            $table->foreignId('employee_id')->nullable()->constrained('employees')->nullOnDelete();

            $table->text('note')->nullable();

            $table->string('documentation_file')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_stages');
    }
};
