<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
    Schema::create('sub_categories', function (Blueprint $table) {
    $table->id('SubCategoryId');
    $table->unsignedBigInteger('CategoryId');
    $table->string('SubCategoryName', 50);
    $table->string('SubCategoryImage', 255)->nullable();
    $table->timestamps();

    // Foreign key'i açık yazıyoruz
    $table->foreign('CategoryId')
          ->references('CategoryId')
          ->on('categories')
          ->onDelete('cascade');
});
    }

    public function down(): void
    {
        Schema::dropIfExists('sub_categories');
    }
};
