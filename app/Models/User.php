<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use OpenApi\Annotations as OA;

/**
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     title="User",
 *     description="User model",
 *     required={"id", "name", "email"},
 *     @OA\Property(property="id", type="integer", format="int64", description="ID user"),
 *     @OA\Property(property="name", type="string", description="Nama user"),
 *     @OA\Property(property="email", type="string", format="email", description="Email user"),
 *     @OA\Property(property="email_verified_at", type="string", format="date-time", nullable=true, description="Tanggal verifikasi email"),
 *     @OA\Property(property="created_at", type="string", format="date-time", description="Tanggal pembuatan user"),
 *     @OA\Property(property="updated_at", type="string", format="date-time", description="Tanggal update user")
 * )
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];
}
