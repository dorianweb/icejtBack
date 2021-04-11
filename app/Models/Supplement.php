<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Supplement extends Model
{
    use HasFactory, SoftDeletes;

    const TYPE_NAPPAGE = 'nappage';
    const TYPE_TOPPIN = 'toppin';
    const UNIT_UNIT = 'u';
    const UNIT_WEIGHT = 'g';
    const UNIT_LIQUID = 'cl';

    protected $fillable = ['name', 'weight', 'unit', 'supplement_type'];

    public function supplement_type()
    {
        return $this->belongsTo(SupplementType::class);
    }
    public function custom_creams()
    {
        return $this->belongsToMany(CustomCream::class);
    }
}
