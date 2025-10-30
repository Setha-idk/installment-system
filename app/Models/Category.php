<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Category extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'description',
    ];
    
    // --- Eloquent Relationships ---

    /**
     * Get the products that belong to this category.
     * * This defines a One-to-Many relationship (one category has many products).
     */
    public function products(): HasMany
    {
        // Eloquent automatically assumes the foreign key is 'category_id' on the Product model
        return $this->hasMany(Product::class);
    }
}