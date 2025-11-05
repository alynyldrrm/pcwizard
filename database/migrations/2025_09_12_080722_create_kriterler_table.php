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
        Schema::create('kriterler', function (Blueprint $table) {
            $table->id('KriterId'); //primary key 
            $table->unsignedBigInteger('CategoryId');
             $table->unsignedBigInteger('SubCategoryId')->nullable();
             $table->string('KriterAdi',50);
             $table->string('KriterDegeri',50);
            $table->timestamps();
            
                        // CategoryId cascade ile
            $table->foreign('CategoryId')
                  ->references('CategoryId')->on('categories')
                  ->onDelete('cascade');

            // SubCategoryId NO ACTION
            $table->foreign('SubCategoryId')
                  ->references('SubCategoryId')->on('sub_categories')
                  ->onDelete('no action'); // BURASI önemli MSSQL için
        });
           
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kriterler');
    }
};
