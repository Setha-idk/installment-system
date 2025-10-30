<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CategoryResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response; // Required for noContent()

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource (PUBLIC).
     */
    public function index(Request $request): JsonResponse
    {
        $categories = Category::latest()
                              ->paginate($request->get('per_page', 10));

        return CategoryResource::collection($categories)->response();
    }

    /**
     * Store a newly created resource in storage (PROTECTED).
     */
    public function store(Request $request): JsonResponse
    {
        // Simple, direct validation
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255', 'unique:categories,name'],
            'description' => ['nullable', 'string'],
        ]);

        $category = Category::create($validated);

        return (new CategoryResource($category))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource (PUBLIC).
     * Route Model Binding automatically fetches the Category.
     */
    public function show(Category $category): JsonResponse
    {
        // Eager load products if you want them on the detail view
        $category->load('products'); 

        return (new CategoryResource($category))->response();
    }

    /**
     * Update the specified resource in storage (PROTECTED).
     */
    public function update(Request $request, Category $category): JsonResponse
    {
        $validated = $request->validate([
            // Ensure unique name check ignores the current category's name
            'name'        => ['sometimes', 'string', 'max:255', 'unique:categories,name,' . $category->id], 
            'description' => ['nullable', 'string', 'sometimes'],
        ]);
        
        $category->update($validated);

        return (new CategoryResource($category))->response();
    }

    /**
     * Remove the specified resource from storage (PROTECTED).
     */
    public function destroy(Category $category): Response
    {
        $category->delete();

        // Returns a 204 No Content response
        return response()->noContent();
    }
}