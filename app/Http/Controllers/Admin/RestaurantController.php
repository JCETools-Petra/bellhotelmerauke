<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Restaurant;
use App\Models\RestaurantImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class RestaurantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $restaurants = Restaurant::with('images')->get();
        return view('admin.restaurants.index', compact('restaurants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.restaurants.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'images' => 'nullable|array', 
            'images.*' => 'image|max:2048', 
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        $restaurant = Restaurant::create([
            'name' => $validated['name'],
            'slug' => $validated['slug'],
            'description' => $validated['description'],
        ]);

        // Unggah dan simpan setiap gambar
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Menggunakan disk 'public' secara eksplisit
                $path = $image->store('restaurants', 'public');
                
                $restaurant->images()->create([
                    'path' => $path, // Simpan path yang sudah benar
                ]);
            }
        }

        return redirect()->route('admin.restaurants.index')->with('success', 'Restaurant added successfully!');
    }


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Restaurant $restaurant)
    {
        return view('admin.restaurants.edit', compact('restaurant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Restaurant $restaurant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|max:2048',
        ]);
        
        $restaurant->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'description' => $validated['description'],
        ]);

        // Unggah dan simpan gambar baru
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                // Menggunakan disk 'public' secara eksplisit
                $path = $image->store('restaurants', 'public');
                
                $restaurant->images()->create([
                    'path' => $path, // Simpan path yang sudah benar
                ]);
            }
        }

        return redirect()->route('admin.restaurants.index')->with('success', 'Restaurant updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Restaurant $restaurant)
    {
        // Hapus file gambar dari storage
        foreach ($restaurant->images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete();
        }

        $restaurant->delete();
        
        return redirect()->route('admin.restaurants.index')->with('success', 'Restaurant deleted successfully!');
    }
    
    // Tambahkan metode ini di luar resource
    public function destroyImage(RestaurantImage $image)
    {
        Storage::disk('public')->delete($image->path);
        $image->delete();

        return back()->with('success', 'Image deleted successfully!');
    }
}