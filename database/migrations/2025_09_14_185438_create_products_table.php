<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('Products', function (Blueprint $table) {
            $table->id('ProductId'); // IDENTITY(1,1) PRIMARY KEY
            $table->unsignedBigInteger('CategoryId'); // bigint NOT NULL - Laravel'in id() ile eşleşir
            $table->string('Ad', 100); // nvarchar(100) NOT NULL
            $table->decimal('Fiyat', 10, 2); // decimal(10,2) NOT NULL
            $table->string('Marka', 50)->nullable(); // nvarchar(50) NULL
            $table->string('Model', 50)->nullable(); // nvarchar(50) NULL
            $table->text('Ozellikler')->nullable(); // nvarchar(max) NULL
            $table->string('Resim', 255)->nullable(); // nvarchar(255) NULL
            $table->string('Aciklama', 500)->nullable(); // nvarchar(500) NULL
            $table->unsignedBigInteger('SubCategoryId')->nullable(); // bigint NULL
            
            // Foreign key constraints
            $table->foreign('CategoryId')->references('CategoryId')->on('categories');
            $table->foreign('SubCategoryId')->references('SubCategoryId')->on('sub_categories');
        });
    }

    public function down()
    {
        Schema::dropIfExists('Products');
    }
};