<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Plan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * This protects against the Mass Assignment vulnerability.
     * Only fields listed here can be set using `Plan::create($data)` or `$plan->fill($data)`.
     * The foreign keys are included so they can be set when creating a plan.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'merchant_id',
        'name',
        'description',
        'installments_count',
        'interest_rate',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * This automatically converts database columns into specific PHP types when retrieved.
     * For example, 'interest_rate' will be a proper float/decimal in PHP.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'installments_count' => 'integer',
        'interest_rate' => 'decimal:2', // Cast as decimal with 2 digits of precision
    ];

    // --- Eloquent Relationships ---

    /**
     * Get the product that owns the plan.
     *
     * This relationship defines that a Plan belongs to one Product.
     * It uses the `product_id` foreign key.
     */
    public function product(): BelongsTo
    {
        // Assuming your product model is named 'Product'
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the merchant (user) who created or owns the plan.
     *
     * This relationship defines that a Plan belongs to one User (acting as the merchant).
     * It uses the `merchant_id` foreign key.
     */
    public function merchant(): BelongsTo
    {
        // Assuming your user model is named 'User'
        // We explicitly tell Eloquent to use the 'merchant_id' column
        // and link it to the primary key of the 'User' model.
        return $this->belongsTo(User::class, 'merchant_id');
    }
}