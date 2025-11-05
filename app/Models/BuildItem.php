<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BuildItem extends Model
{
    use HasFactory;

    protected $primaryKey = 'BuildItemId';
    public $timestamps = false;

    protected $fillable = [
        'BuildId',
        'CategoryId',
        'ProductId',
        'Quantity',
        'AddedDate',
    ];

    public function build()
    {
        return $this->belongsTo(Build::class, 'BuildId', 'BuildId');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'ProductId', 'ProductId');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'CategoryId', 'CategoryId');
    }
}
