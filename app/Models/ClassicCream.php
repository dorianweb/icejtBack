<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassicCream extends Model
{
    protected $fillable = ['name', 'description', 'flavor_id'];
    use HasFactory;
    public function flavor()
    {
        return $this->belongsTo(Flavor::class);
    }
    public function carts()
    {
        return $this->belongsToMany(Cart::class);
    }
}
