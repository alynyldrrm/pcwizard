<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            // SQL Server AFTER desteklemiyor, sadece sütunları ekleyin
            $table->decimal('DiscountedPrice', 10, 2)->nullable();
            $table->unsignedBigInteger('CampaignId')->nullable();
            $table->string('CampaignName', 100)->nullable();
            $table->decimal('DiscountAmount', 10, 2)->default(0);
            
            // Foreign key
            $table->foreign('CampaignId')
                  ->references('CampaignId')
                  ->on('Campaigns')
                  ->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('cart_items', function (Blueprint $table) {
            $table->dropForeign(['CampaignId']);
            $table->dropColumn(['DiscountedPrice', 'CampaignId', 'CampaignName', 'DiscountAmount']);
        });
    }
};