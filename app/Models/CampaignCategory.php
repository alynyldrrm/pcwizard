<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignCategory extends Model
{
    use HasFactory;

    protected $table = 'CampaignCategories';
    public $timestamps = false;
    protected $fillable = ['CampaignId', 'CategoryId'];
}
