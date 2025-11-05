<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubCategory extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'SubCategoryId';
    
    protected $fillable = [
        'CategoryId',
        'SubCategoryName',
        'SubCategoryImage',
    ];
    
    // Category ilişkisi
    public function category()
    {
        return $this->belongsTo(Category::class, 'CategoryId', 'CategoryId');
    }

    // SubCategory.php içine ekleyin
public function campaigns()
{
    return $this->belongsToMany(
        \App\Models\Campaign::class, 
        'CampaignSubCategories', 
        'SubCategoryId', 
        'CampaignId'
    );
}
}