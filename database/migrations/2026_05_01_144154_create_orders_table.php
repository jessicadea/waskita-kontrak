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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();

            $table->string('company_name');
            $table->string('company_type');
            $table->string('project_name');
            $table->string('project_location');
            $table->text('product_spec');

            $table->integer('volume');
            $table->string('delivery_cond');
            $table->date('delivery_date');

            $table->enum('status_verify', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('verify_note')->nullable();

            $table->string('contract_file')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
