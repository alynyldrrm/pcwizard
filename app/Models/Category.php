<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    
    protected $primaryKey = 'CategoryId';
    
    protected $fillable = [
        'CategoryName',
        'CategoryImage'
    ];
    
    // SubCategory ilişkisi
    public function subCategories()
    {
        return $this->hasMany(SubCategory::class, 'CategoryId', 'CategoryId');
    }
    // Category.php içine ekleyin
public function campaigns()
{
    return $this->belongsToMany(
        \App\Models\Campaign::class, 
        'CampaignCategories', 
        'CategoryId', 
        'CampaignId'
    );
}
}