<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('builds', function (Blueprint $table) {
            $table->id('BuildId');
            $table->unsignedBigInteger('UserId');
            $table->string('Name', 100);
            $table->dateTime('CreatedDate');
            $table->decimal('TotalPrice', 18, 2);
            
            // Foreign key user tablosuna bağlama (users tablosu default 'id' primary key)
            $table->foreign('UserId')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('builds');
    }
};
