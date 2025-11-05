<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $table = 'cart_items';
    protected $primaryKey = 'CartItemId';
    public $timestamps = false;
    
    protected $fillable = [
        'UserId',
        'ProductId', 
        'Quantity',
        'UnitPrice',
          'DiscountedPrice', // YENİ
        'TotalPrice',
        'ProductName',
        'ProductImage',
        'ProductBrand',
        'ProductModel',
        'CategoryId',
         'CampaignId', // YENİ
        'CampaignName', // YENİ
        'DiscountAmount', // YENİ
        'AddedDate'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'ProductId', 'ProductId');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'CategoryId', 'CategoryId');
    }
       // Kampanya ilişkisi
    public function campaign()
    {
        return $this->belongsTo(\App\Models\Campaign::class, 'CampaignId', 'CampaignId');
    }
}

