<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Configurations
        Schema::create('configurations', function (Blueprint $table) {
            $table->id('ConfigId');
            $table->foreignId('UserId')->constrained('users','id');
            $table->text('ConfigData');
            $table->timestamp('KayitTarihi')->useCurrent();
        });

        // CriteriaCompatibilities
        Schema::create('criteria_compatibilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('CriteriaId1')->constrained('kriterler','KriterId');
            $table->string('CriteriaValue1',50);
            $table->foreignId('CriteriaId2')->constrained('kriterler','KriterId');
            $table->string('CriteriaValue2',50);
        });

        // Orders
        Schema::create('orders', function (Blueprint $table) {
            $table->id('OrderId');
            $table->foreignId('UserId')->nullable()->constrained('users','id');
            $table->string('Phone',20);
            $table->timestamp('OrderDate')->useCurrent();
            $table->string('Address',500);
            $table->string('City',50);
            $table->string('District',50);
            $table->string('PostalCode',10);
            $table->string('Email',100);
            $table->string('FirstName',50);
            $table->string('LastName',50);
            $table->string('OrderNumber',20);
            $table->string('OrderStatus',20)->default('Beklemede');
            $table->string('PaymentMethod',20);
            $table->decimal('TotalAmount',10,2);
            $table->timestamp('DeliveredDate')->nullable();
            $table->timestamp('ShippedDate')->nullable();
            $table->foreignId('ConfigurationConfigId')->nullable()->constrained('configurations','ConfigId');
            $table->timestamps();
        });

        // OrderItems
        Schema::create('order_items', function (Blueprint $table) {
            $table->id('OrderItemId');
            $table->foreignId('OrderId')->constrained('orders','OrderId');
            $table->foreignId('ProductId')->constrained('products','ProductId');
            $table->integer('Quantity');
            $table->decimal('UnitPrice',10,2);
            $table->decimal('TotalPrice',10,2);
            $table->string('ProductName',100);
            $table->string('ProductImage',255);
        });

        // Stocks
        Schema::create('stocks', function (Blueprint $table) {
            $table->id('StockId');
            $table->foreignId('ProductId')->constrained('products','ProductId');
            $table->integer('Miktar');
            $table->timestamp('GuncellemeTarihi')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stocks');
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
        Schema::dropIfExists('criteria_compatibilities');
        Schema::dropIfExists('configurations');
    }

    
};
