<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Image;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function destroy(Image $image)
    {
        // Hapus file dari storage
        Storage::disk('public')->delete($image->path);

        // Hapus record dari database
        $image->delete();

        return back()->with('success', 'Image deleted successfully.');
    }
}