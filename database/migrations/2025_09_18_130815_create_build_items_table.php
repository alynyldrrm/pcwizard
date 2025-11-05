<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('build_items', function (Blueprint $table) {
            $table->id('BuildItemId');
            $table->unsignedBigInteger('BuildId');
            $table->unsignedBigInteger('CategoryId');
            $table->unsignedBigInteger('ProductId');
            $table->integer('Quantity');
            $table->dateTime('AddedDate');

            // Foreign key bağlantıları
            $table->foreign('BuildId')->references('BuildId')->on('builds')->onDelete('cascade');
            $table->foreign('CategoryId')->references('CategoryId')->on('categories')->onDelete('cascade');
            $table->foreign('ProductId')->references('ProductId')->on('products')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('build_items');
    }
};
