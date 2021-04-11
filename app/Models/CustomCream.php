<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CustomCream extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name'];
    public function supplements()
    {
        return $this->belongsToMany(Supplement::class);
    }
    public function flavors()
    {
        return $this->belongsToMany(Flavor::class);
    }
    public function carts()
    {
        return $this->belongsToMany(Cart::class);
    }
}
