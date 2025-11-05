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
    Schema::table('Orders', function (Blueprint $table) {
        if (!Schema::hasColumn('Orders', 'PaymentDate')) {
            $table->datetime('PaymentDate')->nullable()->after('OrderDate');
        }
        // PaymentMethod zaten var, tekrar ekleme!
    });
}

public function down(): void
{
    Schema::table('Orders', function (Blueprint $table) {
        $table->dropColumn(['PaymentDate']);
        // PaymentMethod'u düşürme çünkü zaten eski tabloda vardı
    });
}

};