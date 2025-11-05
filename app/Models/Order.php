<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'OrderId';
    protected $table = 'Orders';
    public $timestamps = false;

    protected $fillable = [
        'UserId',
        'OrderNumber',
        'OrderStatus',
        'TotalAmount',
        'OrderDate',
        'PaymentDate',
        'PaymentMethod',
        'ConfigurationConfigId',
        'ConfigurationName'
    ];

    // Tarih alanlarını otomatik Cast etme
    protected $casts = [
        'OrderDate' => 'datetime',
        'PaymentDate' => 'datetime',
        'TotalAmount' => 'decimal:2'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'UserId', 'id');
    }

    public function configuration()
    {
        return $this->belongsTo(Configuration::class, 'ConfigurationConfigId', 'ConfigId');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'OrderId', 'OrderId');
    }

    // OrderDate accessor (ek güvenlik için)
    public function getOrderDateAttribute($value)
    {
        if (!$value) return null;
        return Carbon::parse($value);
    }
}