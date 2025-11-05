<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ProductCriterias', function (Blueprint $table) {
            $table->id('Id'); // IDENTITY(1,1) PRIMARY KEY
            $table->unsignedBigInteger('ProductId'); // bigint NOT NULL
            $table->unsignedBigInteger('CriteriaId'); // bigint NOT NULL
            $table->string('CriteriaValue', 50); // nvarchar(50) NOT NULL
            
            // Foreign key constraints
            $table->foreign('ProductId')->references('ProductId')->on('Products');
            $table->foreign('CriteriaId')->references('KriterId')->on('kriterler');
        });
    }

    public function down()
    {
        Schema::dropIfExists('ProductCriterias');
    }
};