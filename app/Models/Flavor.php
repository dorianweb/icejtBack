<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(
 * required={"id"},
 * @OA\Xml(name="Flavor"),
 * @OA\Property(property="id", type="integer", readOnly="true", example="1"),
 * @OA\Property(property="color", type="string", readOnly="true", description="hexadecimal color for this flavor"),
 * @OA\Property(property="name", type="string", maxLength=255,  readOnly="true", example="Coco"),
 * @OA\Property(property="price", type="number", readOnly="true",description="1200"),
 * @OA\Property(property="created_at", type="string", format="date-time", description="Initial creation timestamp", readOnly="true"),
 * @OA\Property(property="updated_at", type="string", format="date-time", description="Last update timestamp", readOnly="true"),
 * @OA\Property(property="deleted_at", type="string", format="date-time", description="Soft delete timestamp", readOnly="true"),
 * )
 * Class User
 */
class Flavor extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = ['name', 'price', 'color'];
    public function custom_creams()
    {
        return $this->belongsToMany(CustomCream::class);
    }
    public function classic_creams()
    {
        return $this->hasOne(ClassicCream::class);
    }
}
