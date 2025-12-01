<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MiceKit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MiceKitController extends Controller
{
    public function index()
    {
        $miceKits = MiceKit::latest()->get();
        return view('admin.mice_kits.index', compact('miceKits'));
    }

    public function create()
    {
        return view('admin.mice_kits.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:file,video',
            'file' => 'required_if:type,file|nullable|file|max:102400', // Max 10MB untuk file biasa
            'video_file' => 'required_if:type,video|nullable|mimetypes:video/mp4,video/mov,video/ogg,video/quicktime|max:51200', // Max 50MB untuk video
        ]);
    
        $pathOrLink = '';
        $originalFilename = null;
    
        if ($request->type === 'file' && $request->hasFile('file')) {
            $file = $request->file('file');
            $pathOrLink = $file->store('mice_kit', 'private');
            $originalFilename = $file->getClientOriginalName();
        } elseif ($request->type === 'video' && $request->hasFile('video_file')) {
            // Logika baru untuk menyimpan video
            $file = $request->file('video_file');
            $pathOrLink = $file->store('mice_kit_videos', 'private'); // Simpan di folder terpisah
            $originalFilename = $file->getClientOriginalName();
        }
    
        MiceKit::create([
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
            'path_or_link' => $pathOrLink,
            'original_filename' => $originalFilename,
        ]);
    
        return redirect()->route('admin.mice-kits.index')->with('success', 'MICE Kit item created successfully.');
    }

    public function destroy(MiceKit $miceKit)
    {
        if ($miceKit->type === 'file') {
            Storage::disk('private')->delete($miceKit->path_or_link);
        }
        $miceKit->delete();

        return back()->with('success', 'MICE Kit item deleted successfully.');
    }
}