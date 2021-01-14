<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImagesController extends Controller
{
    public function store(Request $request)
    {
        $request->validate(['image'=>'required|image']);

        $path = $request->image->store('', 'images');

        return response()->json(['image_url'=>Storage::disk('images')->url($path)]);
    }
}
