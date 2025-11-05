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
    Schema::table('orders', function (Blueprint $table) {
        $table->string('Phone')->nullable()->change();
        $table->string('Address')->nullable()->change();
        $table->string('City')->nullable()->change();
        $table->string('District')->nullable()->change();
        $table->string('PostalCode')->nullable()->change();
        $table->string('Email')->nullable()->change();
        $table->string('FirstName')->nullable()->change();
        $table->string('LastName')->nullable()->change();
        $table->string('PaymentMethod')->nullable()->change();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            //
        });
    }
};
