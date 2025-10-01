<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Image;
use App\Models\Category;
class Product extends Model
{
    protected $fillable = [
        'image_id',
        'name',
        'description',
        'stock',
        'price',
        'category_id'
    ];

    public function images() {
        return $this->hasMany(Image::class);
    }

    public function categories() {
        return $this->hasMany(Category::class);
    }
}
