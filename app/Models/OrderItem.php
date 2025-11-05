<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'OrderItemId';
    protected $table = 'order_items';
    public $timestamps = false;

    protected $fillable = [
        'OrderId',
        'ProductId',
        'Quantity',
        'UnitPrice',
        'TotalPrice',
        'ProductName',
        'ProductImage',
        'ProductBrand',  // YENİ ALAN
        'ProductModel'   // YENİ ALAN
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'OrderId', 'OrderId');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'ProductId', 'ProductId');
    }
}