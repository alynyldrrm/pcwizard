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
        Schema::table('order_items', function (Blueprint $table) {
            // Eksik alanları ekle
            if (!Schema::hasColumn('order_items', 'ProductBrand')) {
                $table->string('ProductBrand')->nullable()->after('ProductImage');
            }
            
            if (!Schema::hasColumn('order_items', 'ProductModel')) {
                $table->string('ProductModel')->nullable()->after('ProductBrand');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('order_items', function (Blueprint $table) {
            $table->dropColumn(['ProductBrand', 'ProductModel']);
        });
    }
};