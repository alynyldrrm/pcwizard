<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Campaign extends Model
{
    use HasFactory;

    protected $table = 'Campaigns';
    protected $primaryKey = 'CampaignId';
    public $timestamps = true;

    protected $fillable = [
        'Name',
        'Description',
        'DiscountType',
        'DiscountValue',
        'CouponCode',
        'UsageLimit',
        'PerUserLimit',
        'MinCartTotal',
        'StartDate',
        'EndDate',
        'IsActive'
    ];

    // Campaign - Categories (many-to-many)
    public function categories()
    {
        return $this->belongsToMany(Category::class, 'CampaignCategories', 'CampaignId', 'CategoryId');
    }

    // Campaign - SubCategories (many-to-many)
    public function subCategories()
    {
        return $this->belongsToMany(SubCategory::class, 'CampaignSubCategories', 'CampaignId', 'SubCategoryId');
    }

    // Campaign - Products (many-to-many)
    public function products()
    {
        return $this->belongsToMany(Product::class, 'CampaignProducts', 'CampaignId', 'ProductId');
    }

    // app/Models/Campaign.php içine ekleyin
public function isActive()
{
    $now = now();
    return $this->IsActive && 
           $this->StartDate <= $now && 
           $this->EndDate >= $now;
}

public function canApplyToProduct($productId)
{
    // Ürün bazlı kontrol
    if ($this->products()->where('ProductId', $productId)->exists()) {
        return true;
    }
    
    // Kategori bazlı kontrol
    $product = Product::find($productId);
    if ($product && $this->categories()->where('CategoryId', $product->CategoryId)->exists()) {
        return true;
    }
    
    // Alt kategori bazlı kontrol
    if ($product && $this->subCategories()->where('SubCategoryId', $product->SubCategoryId)->exists()) {
        return true;
    }
    
    return false;
}

public function calculateDiscount($amount)
{
    switch ($this->DiscountType) {
        case 'yuzde':
            return $amount * ($this->DiscountValue / 100);
        case 'sabit':
            return min($this->DiscountValue, $amount);
        case 'ucretsiz_kargo':
            return 0; // Kargo ücreti ayrı hesaplanacak
        default:
            return 0;
    }
}
}
