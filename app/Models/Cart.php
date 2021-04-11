<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['state', 'user_id'];
    public const STATE_CREATED = '0';
    public const STATE_VALIDATED = '1';
    public const STATE_ORDERED = '2';
    public const STATE_DELIVERED = '3';
    function  user()
    {
        return  $this->belongsTo(User::class);
    }
    public function custom_creams()
    {
        return $this->belongsToMany(CustomCream::class);
    }
    public function classic_creams()
    {
        return $this->belongsToMany(ClassicCream::class);
    }
}
