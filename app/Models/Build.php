<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Build extends Model
{
    use HasFactory;

    protected $primaryKey = 'BuildId';
    public $timestamps = false;

    protected $fillable = [
        'UserId',
        'Name',
        'CreatedDate',
        'TotalPrice',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'UserId', 'id');
    }

    public function items()
    {
        return $this->hasMany(BuildItem::class, 'BuildId', 'BuildId');
    }
}
