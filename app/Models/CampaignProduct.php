<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignProduct extends Model
{
    use HasFactory;

    protected $table = 'CampaignProducts';
    public $timestamps = false;
    protected $fillable = ['CampaignId', 'ProductId'];
}
