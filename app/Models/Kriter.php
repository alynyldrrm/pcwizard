<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kriter extends Model
{
    use HasFactory;

    protected $table = 'kriterler'; // tablo adı
    protected $primaryKey = 'KriterId'; // <- burası kritik
    public $incrementing = true; // auto increment aktif
    protected $keyType = 'int';  // integer primary key

    protected $fillable = [
        'CategoryId',
        'SubCategoryId', 
        'KriterAdi',
        'KriterDegeri',
    ];

    public function category() {
        return $this->belongsTo(Category::class, 'CategoryId');
    }

    public function subCategory() {
        return $this->belongsTo(SubCategory::class, 'SubCategoryId');
    }
}
