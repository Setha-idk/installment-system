<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Product extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * Includes 'uuid' which is a public identifier.
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'merchant_id',
        'image_id',
        'category_id',
        'name',
        'description',
        'stock',
        'price',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * Ensures stock is an integer and price is a float/decimal in PHP.
     * @var array<string, string>
     */
    protected $casts = [
        'stock' => 'integer',
        'price' => 'decimal:2', 
    ];

    // --- Eloquent Relationships ---

    /**
     * Get the merchant (user) that owns the product.
     */
    public function merchant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'merchant_id');
    }

    /**
     * Get the category that the product belongs to.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }
    
    /**
     * Get the primary image associated with the product.
     */
    public function image(): BelongsTo
    {
        // Assuming your image model is named 'Image'
        return $this->belongsTo(Image::class);
    }

    /**
     * Get the installment plans associated with the product.
     */
    public function plans(): HasMany
    {
        return $this->hasMany(Plan::class);
    }

    public function getRouteKeyName(): string
    {
        return 'uuid';
    }
}