<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $table = 'Products';
    protected $primaryKey = 'ProductId';
    public $timestamps = false;

    protected $fillable = [
        'CategoryId',
        'SubCategoryId',
        'Ad',
        'Fiyat',
        'Marka',
        'Model',
        'Ozellikler',
        'Resim',
        'Aciklama',
            'is_discounted',   // İndirimli mi
    'discount_value',  // İndirim miktarı
    'is_featured',     // Öne çıkan mı

    ];

    // Mevcut ilişkiler
    public function category()
    {
        return $this->belongsTo(Category::class, 'CategoryId', 'CategoryId');
    }

    public function subCategory()
    {
        return $this->belongsTo(SubCategory::class, 'SubCategoryId', 'SubCategoryId');
    }

    public function criterias()
    {
        return $this->hasMany(ProductCriteria::class, 'ProductId', 'ProductId');
    }

    // ============ KAMPANYA İLİŞKİLERİ VE METODLARI ============
    
    public function campaigns()
    {
        return $this->belongsToMany(
            \App\Models\Campaign::class, 
            'CampaignProducts', 
            'ProductId', 
            'CampaignId'
        );
    }

    // Aktif kampanyaları getir
    public function getActiveCampaignsAttribute()
    {
        $today = now()->toDateString();
        $activeCampaigns = collect();
        
        // 1. Direkt ürün kampanyaları
        $directCampaigns = $this->campaigns()
            ->where('IsActive', true)
            ->where('StartDate', '<=', $today)
            ->where('EndDate', '>=', $today)
            ->get();
        
        $activeCampaigns = $activeCampaigns->merge($directCampaigns);
        
        // 2. Kategori kampanyaları
        if ($this->CategoryId) {
            $categoryCampaigns = \App\Models\Campaign::where('IsActive', true)
                ->where('StartDate', '<=', $today)
                ->where('EndDate', '>=', $today)
                ->whereHas('categories', function($q) {
                    $q->where('CategoryId', $this->CategoryId);
                })
                ->get();
            
            $activeCampaigns = $activeCampaigns->merge($categoryCampaigns);
        }
        
        // 3. Alt kategori kampanyaları
        if ($this->SubCategoryId) {
            $subCategoryCampaigns = \App\Models\Campaign::where('IsActive', true)
                ->where('StartDate', '<=', $today)
                ->where('EndDate', '>=', $today)
                ->whereHas('subCategories', function($q) {
                    $q->where('SubCategoryId', $this->SubCategoryId);
                })
                ->get();
            
            $activeCampaigns = $activeCampaigns->merge($subCategoryCampaigns);
        }
        
        return $activeCampaigns->unique('CampaignId');
    }

    // En iyi indirimi getir
    public function getBestDiscountAttribute()
    {
        $campaigns = $this->active_campaigns;
        
        if ($campaigns->isEmpty()) {
            return null;
        }
        
        $bestDiscount = null;
        $maxDiscountAmount = 0;
        
        foreach ($campaigns as $campaign) {
            // Minimum sepet kontrolü (şimdilik atlıyoruz, sepette kontrol edilecek)
            
            if ($campaign->DiscountType == 'yuzde') {
                $discountAmount = ($this->Fiyat * $campaign->DiscountValue) / 100;
            } elseif ($campaign->DiscountType == 'sabit') {
                $discountAmount = $campaign->DiscountValue;
            } else {
                continue;
            }
            
            if ($discountAmount > $maxDiscountAmount) {
                $maxDiscountAmount = $discountAmount;
                $bestDiscount = $campaign;
            }
        }
        
        return $bestDiscount;
    }

    // İndirimli fiyatı getir
    public function getDiscountedPriceAttribute()
    {
        $campaign = $this->best_discount;
        
        if (!$campaign) {
            return $this->Fiyat;
        }
        
        if ($campaign->DiscountType == 'yuzde') {
            return $this->Fiyat - (($this->Fiyat * $campaign->DiscountValue) / 100);
        } elseif ($campaign->DiscountType == 'sabit') {
            return max(0, $this->Fiyat - $campaign->DiscountValue);
        }
        
        return $this->Fiyat;
    }

    // İndirim yüzdesini getir
    public function getDiscountPercentageAttribute()
    {
        if ($this->Fiyat == 0) {
            return 0;
        }
        
        $discountedPrice = $this->discounted_price;
        $discountAmount = $this->Fiyat - $discountedPrice;
        
        return round(($discountAmount / $this->Fiyat) * 100);
    }

    // İndirim miktarını getir
    public function getDiscountAmountAttribute()
    {
        return $this->Fiyat - $this->discounted_price;
    }

    // Ürün indirimli mi
public function isDiscounted()
{
    return $this->is_discounted;
}

// Ürün öne çıkan mı
public function isFeatured()
{
    return $this->is_featured;
}

// İndirimli fiyat (manuel belirlenmiş ise)
public function getManualDiscountedPrice()
{
    if ($this->is_discounted && $this->discount_value) {
        return max(0, $this->Fiyat - $this->discount_value);
    }
    return $this->Fiyat;
}

}