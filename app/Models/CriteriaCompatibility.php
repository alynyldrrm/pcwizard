<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CriteriaCompatibility extends Model
{
    use HasFactory;

    protected $primaryKey = 'Id';
    protected $table = 'criteria_compatibilities';
    public $timestamps = false;

    protected $fillable = [
        'CriteriaId1',
        'CriteriaValue1',
        'CriteriaId2',
        'CriteriaValue2'
    ];

    // İlişkiler - DÜZELTİLDİ
    public function kriter1() 
    {
        return $this->belongsTo(Kriter::class, 'CriteriaId1', 'KriterId');
    }

    public function kriter2() 
    {
        return $this->belongsTo(Kriter::class, 'CriteriaId2', 'KriterId');
    }
}