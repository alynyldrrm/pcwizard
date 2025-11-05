<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Ana kampanya tablosu
        Schema::create('Campaigns', function (Blueprint $table) {
            $table->id('CampaignId');
            $table->string('Name', 100);
            $table->text('Description')->nullable();
            $table->enum('DiscountType', ['yuzde', 'sabit', 'ucretsiz_kargo', 'paket']);
            $table->decimal('DiscountValue', 10, 2);
            $table->string('CouponCode', 50)->nullable();
            $table->integer('UsageLimit')->nullable();
            $table->integer('PerUserLimit')->nullable();
            $table->decimal('MinCartTotal', 10, 2)->nullable();
            $table->date('StartDate');
            $table->date('EndDate');
            $table->boolean('IsActive')->default(true);
            $table->timestamps();
        });

        // Kampanya - Kategori ilişkisi
        Schema::create('CampaignCategories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('CampaignId');
            $table->unsignedBigInteger('CategoryId');

            $table->foreign('CampaignId')
                  ->references('CampaignId')->on('Campaigns')->onDelete('cascade');
            $table->foreign('CategoryId')
                  ->references('CategoryId')->on('categories')->onDelete('cascade'); // düzeltildi
        });

        // Kampanya - Alt Kategori ilişkisi
        Schema::create('CampaignSubCategories', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('CampaignId');
            $table->unsignedBigInteger('SubCategoryId');

            $table->foreign('CampaignId')
                  ->references('CampaignId')->on('Campaigns')->onDelete('cascade');
            $table->foreign('SubCategoryId')
                  ->references('SubCategoryId')->on('sub_categories')->onDelete('cascade'); // düzeltildi
        });

        // Kampanya - Ürün ilişkisi
        Schema::create('CampaignProducts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('CampaignId');
            $table->unsignedBigInteger('ProductId');

            $table->foreign('CampaignId')
                  ->references('CampaignId')->on('Campaigns')->onDelete('cascade');
            $table->foreign('ProductId')
                  ->references('ProductId')->on('Products')->onDelete('cascade'); // büyük P veritabanına göre
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('CampaignProducts');
        Schema::dropIfExists('CampaignSubCategories');
        Schema::dropIfExists('CampaignCategories');
        Schema::dropIfExists('Campaigns');
    }
};
