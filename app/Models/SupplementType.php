<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplementType extends Model
{
    use HasFactory;
    public function supplements()
    {
        return $this->hasMany(Supplement::class);
    }
}
