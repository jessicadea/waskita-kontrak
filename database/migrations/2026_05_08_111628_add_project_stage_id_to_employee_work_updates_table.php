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
        Schema::table('employee_work_updates', function (Blueprint $table) {
            $table->foreignId('project_stage_id')
                ->nullable()
                ->after('project_id')
                ->constrained('project_stages')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('employee_work_updates', function (Blueprint $table) {
            $table->dropConstrainedForeignId('project_stage_id');
        });
    }
};
