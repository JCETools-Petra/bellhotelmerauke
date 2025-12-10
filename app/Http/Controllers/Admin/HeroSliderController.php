<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlider; // GANTI INI
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroSliderController extends Controller
{
    public function index()
    {
        $sliders = HeroSlider::orderBy('order')->get(); // GANTI INI
        return view('admin.hero_sliders.index', compact('sliders')); // GANTI INI
    }

    public function create()
    {
        return view('admin.hero_sliders.create'); // GANTI INI
    }

    public function store(Request $request)
    {
        $request->validate([
            'image_path' => 'required|image|max:2048', // Validasi gambar
            'order' => 'nullable|integer',
            'is_active' => 'nullable|string',
        ]);

        $path = $request->file('image_path')->store('hero_sliders', 'public');

        HeroSlider::create([ // GANTI INI
            'image_path' => $path,
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()->route('admin.hero-sliders.index')->with('success', 'Hero Slider created successfully.'); // GANTI INI
    }

    public function edit(HeroSlider $heroSlider) // GANTI INI
    {
        
        return view('admin.hero_sliders.edit', ['slider' => $heroSlider]);
    }

    public function update(Request $request, HeroSlider $heroSlider) // GANTI INI
    {
        $request->validate([
            'image_path' => 'nullable|image|max:2048',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|string',
        ]);

        $data = [
            'order' => $request->order ?? 0,
            'is_active' => $request->has('is_active'),
        ];

        if ($request->hasFile('image_path')) {
            // Hapus gambar lama
            if ($heroSlider->image_path && Storage::disk('public')->exists($heroSlider->image_path)) {
                Storage::disk('public')->delete($heroSlider->image_path);
            }
            // Simpan gambar baru
            $data['image_path'] = $request->file('image_path')->store('hero_sliders', 'public');
        }

        $heroSlider->update($data);

        return redirect()->route('admin.hero-sliders.index')->with('success', 'Hero Slider updated successfully.'); // GANTI INI
    }

    public function destroy(HeroSlider $heroSlider) // GANTI INI
    {
        if ($heroSlider->image_path && Storage::disk('public')->exists($heroSlider->image_path)) {
            Storage::disk('public')->delete($heroSlider->image_path);
        }
        
        $heroSlider->delete();
        return redirect()->route('admin.hero-sliders.index')->with('success', 'Hero Slider deleted successfully.'); // GANTI INI
    }
}