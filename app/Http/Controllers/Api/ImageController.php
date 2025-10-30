<?php

namespace App\Http\Controllers\Api;

use App\Models\Image;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\ImageResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response; 

class ImageController extends Controller
{
    /**
     * Display a listing of image records (PROTECTED).
     */
    public function index(Request $request): JsonResponse
    {
        $images = Image::latest()->paginate($request->get('per_page', 10));

        return ImageResource::collection($images)->response();
    }

    /**
     * Store a newly created image record and upload file (PROTECTED).
     * NOTE: This assumes file is sent via 'image_file' in the request.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'image_file' => ['required', 'image', 'max:2048'], // Max 2MB image validation
        ]);

        // 1. Store the file on the disk (e.g., 'public' disk)
        $path = $request->file('image_file')->store('product_images', 'public');

        // 2. Create the database record
        $image = Image::create([
            'path' => $path,
        ]);

        return (new ImageResource($image))->response()->setStatusCode(201);
    }

    /**
     * Display the specified image record (PROTECTED - for internal use).
     */
    public function show(Image $image): JsonResponse
    {
        return (new ImageResource($image))->response();
    }

    /**
     * Remove the specified image record and delete the file (PROTECTED).
     */
    public function destroy(Image $image): Response
    {
        // 1. Delete the file from storage first
        Storage::disk('public')->delete($image->path);
        
        // 2. Delete the database record
        $image->delete();

        return response()->noContent();
    }
    
    // update is usually omitted for image records
}