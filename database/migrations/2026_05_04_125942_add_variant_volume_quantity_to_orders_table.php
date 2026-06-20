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
        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('variant_id')->nullable()->after('product_id')->constrained('product_variants')->nullOnDelete();
            $table->foreignId('volume_id')->nullable()->after('variant_id')->constrained('product_variant_volumes')->nullOnDelete();
            $table->integer('quantity')->default(1)->after('volume');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['variant_id']);
            $table->dropForeign(['volume_id']);
            $table->dropColumn(['variant_id', 'volume_id', 'quantity']);
        });
    }
};
