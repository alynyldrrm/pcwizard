<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('stock_logs', function (Blueprint $table) {
            $table->id(); // log ID
            $table->unsignedBigInteger('stock_id'); // hangi stok
            $table->unsignedBigInteger('product_id'); // hangi ürün
            $table->unsignedBigInteger('admin_id'); // hangi admin
            $table->integer('miktar_change'); // artış veya azalış miktarı
            $table->enum('islem_tipi', ['giris', 'cikis']); // işlem türü
            $table->timestamp('islem_tarihi')->useCurrent(); // işlem tarihi

            // Foreign key ilişkileri
            $table->foreign('stock_id')->references('StockId')->on('stocks')->onDelete('cascade');
            $table->foreign('product_id')->references('ProductId')->on('Products')->onDelete('cascade');
            $table->foreign('admin_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stock_logs');
    }
};
