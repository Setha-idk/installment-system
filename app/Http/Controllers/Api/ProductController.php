<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\ProductResource;
use App\Http\Requests\StoreProductRequest; 
use App\Http\Requests\UpdateProductRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response; // Corrected import for destroy method

class ProductController extends Controller
{
    /**
     * Display a listing of the resource. (PUBLICLY ACCESSIBLE)
     */
    public function index(Request $request): JsonResponse
    {
        $query = Product::with(['category', 'image']);

        // Optional: Filter by merchant if the user is authenticated
        if (Auth::check()) {
            $query->where('merchant_id', Auth::id());
        }

        $products = $query->latest()->paginate($request->get('per_page', 10));

        return ProductResource::collection($products)->response();
    }

    /**
     * Store a newly created resource in storage. (PROTECTED)
     * Validation and authorization handled by StoreProductRequest.
     */
    public function store(StoreProductRequest $request): JsonResponse
    {
        $product = Product::create([
            ...$request->validated(), 
            'merchant_id' => Auth::id(), 
        ]);

        $product->load(['category', 'image']);

        return (new ProductResource($product))->response()->setStatusCode(201);
    }

    /**
     * Display the specified resource. (PUBLICLY ACCESSIBLE)
     * Route Model Binding fetches the Product.
     */
    public function show(Product $product): JsonResponse
    {
        // Load all related data for a detailed view
        $product->load(['category', 'image', 'plans']); 

        return (new ProductResource($product))->response();
    }

    /**
     * Update the specified resource in storage. (PROTECTED)
     * Authorization handled by UpdateProductRequest (ensuring ownership).
     */
    public function update(UpdateProductRequest $request, Product $product): JsonResponse
    {
        $product->update($request->validated());

        $product->load(['category', 'image']);

        return (new ProductResource($product))->response();
    }

    /**
     * Remove the specified resource from storage. (PROTECTED)
     * Return type is Response to handle the 204 No Content status correctly.
     */
    public function destroy(Product $product): Response
    {
        // Final Authorization check (redundant with Policy/Form Request, but safe)
        if ($product->merchant_id !== Auth::id()) {
             abort(403, 'You do not own this product.');
        }

        $product->delete();

        // Returns a 204 No Content standard response
        return response()->noContent();
    }
}