<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Category",
 *     title="Category",
 *     description="Category model",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Technology"),
 * )
 */
class Category extends Model
{
    use HasFactory;
    protected $fillable=['name'];

    public function plants(){
        return $this->hasMany(Plant::class);
    }
}
