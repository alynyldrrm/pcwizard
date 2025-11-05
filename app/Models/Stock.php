<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $primaryKey = 'StockId';
    protected $table = 'Stocks';
    public $timestamps = false;

    protected $fillable = [
        'ProductId',
        'Miktar',
        'GuncellemeTarihi'
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'ProductId', 'ProductId');
    }
}
