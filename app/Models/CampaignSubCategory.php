<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignSubCategory extends Model
{
    use HasFactory;

    protected $table = 'CampaignSubCategories';
    public $timestamps = false;
    protected $fillable = ['CampaignId', 'SubCategoryId'];
}
