<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ImageController extends Controller
{

    public function show($filename)
    {
        $path = storage_path("app/public/logos/{$filename}");

        $file = Storage::get($path);
        $type = Storage::mimeType($path);

        return response($file)->header('Content-Type', $type);
    }
}
