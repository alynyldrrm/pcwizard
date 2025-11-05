<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockLog extends Model
{
    use HasFactory;

    protected $table = 'stock_logs';
    protected $primaryKey = 'id';
    public $timestamps = false;

    protected $fillable = [
        'stock_id',
        'product_id',
        'admin_id',
        'miktar_change',
        'islem_tipi',
        'islem_tarihi',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'ProductId');
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class, 'stock_id', 'StockId');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id', 'id');
    }
}
