<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Image;

class ImageController extends Controller
{
    function index() {
        $images = Image::all();
        return response()->json($images, 200);
    }
}
