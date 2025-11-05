<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCriteria extends Model
{
    use HasFactory;

    protected $table = 'ProductCriterias';
    protected $primaryKey = 'Id';
    public $timestamps = false;

    protected $fillable = [
        'ProductId',
        'CriteriaId',
        'CriteriaValue',
    ];

    // Product ilişkisi
    public function product()
    {
        return $this->belongsTo(Product::class, 'ProductId', 'ProductId');
    }

    // Kriter ilişkisi
    public function kriter()
    {
        return $this->belongsTo(Kriter::class, 'CriteriaId', 'KriterId');
    }
}