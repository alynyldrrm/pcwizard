<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->boolean('is_discounted')->default(false)->after('price'); // İndirimli mi
            $table->decimal('discount_value', 8, 2)->nullable()->after('is_discounted'); // İndirim oranı/tutarı
            $table->boolean('is_featured')->default(false)->after('discount_value'); // Öne çıkan ürün
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['is_discounted', 'discount_value', 'is_featured']);
        });
    }
};
