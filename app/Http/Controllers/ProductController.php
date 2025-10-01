<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    function index() {
        $products = Product::all();
        return response()->json($products, 200);
    }

    function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image_id' => 'nullable|exists:images,id',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'merchant_id' => 'required|exists:users,id',
        ]);

        $product = Product::create($validated);
        return response()->json($product, 201);
    }
}
