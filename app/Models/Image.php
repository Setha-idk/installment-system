<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Image extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'path',
    ];

    // --- Eloquent Relationships ---

    /**
     * Get the products that use this image as their primary image.
     *
     * Your products table has a nullable 'image_id' which points to this table,
     * creating a One-to-Many inverse relationship here.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }
}