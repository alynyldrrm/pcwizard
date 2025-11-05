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
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id('CartItemId'); // Otomatik artan PK
            $table->unsignedBigInteger('UserId');
            $table->unsignedBigInteger('ProductId');
            $table->integer('Quantity')->default(1);
            $table->decimal('UnitPrice', 10, 2);
            $table->decimal('TotalPrice', 10, 2);
            $table->string('ProductName', 255);
            $table->string('ProductImage', 255)->nullable();
            $table->string('ProductBrand', 100)->nullable();
            $table->string('ProductModel', 100)->nullable();
            $table->unsignedBigInteger('CategoryId')->nullable();
            $table->timestamp('AddedDate')->useCurrent();
            
            $table->foreign('ProductId')->references('ProductId')->on('products')->onDelete('cascade');
            $table->foreign('CategoryId')->references('CategoryId')->on('categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cart_items');
    }
};
