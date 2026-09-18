<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="Product",
 *     type="object",
 *     title="Product",
 *     description="Product model",
 *
 *     @OA\Property(property="id", type="integer", format="int64", description="Product ID", example=1),
 *     @OA\Property(property="name", type="string", description="Product name", example="Sample Product"),
 *     @OA\Property(property="price", type="number", format="float", description="Product price", example=19.99),
 *     @OA\Property(property="description", type="string", nullable=true, description="Product description", example="This is a sample product description."),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Creation timestamp", example="2024-01-01T12:00:00Z"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Update timestamp", example="2024-01-02T12:00:00Z")
 * )
 */
class Product extends Model
{
    protected $fillable = [
        'name',
        'price',
        'description',
    ];
}
