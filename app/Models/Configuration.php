<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuration extends Model
{
    use HasFactory;

    protected $primaryKey = 'ConfigId';
    protected $table = 'Configurations';
    public $timestamps = false;

    protected $fillable = [
        'UserId',
        'ConfigData',
        'KayitTarihi'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'UserId', 'id');
    }
}
