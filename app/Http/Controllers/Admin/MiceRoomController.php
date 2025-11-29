<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MiceRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class MiceRoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $miceRooms = MiceRoom::latest()->paginate(10);
        return view('admin.mice.index', compact('miceRooms'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.mice.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:mice_rooms',
            'description' => 'required|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'specifications.*.key' => 'nullable|string',
            'specifications.*.value' => 'nullable|string',
            'specifications.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $miceRoom = new MiceRoom($request->only('name', 'slug', 'description', 'meta_title', 'meta_description'));

        $specifications = [];
        if ($request->input('specifications')) {
            foreach ($request->input('specifications') as $index => $specificationData) {
                if (!empty($specificationData['key']) && !empty($specificationData['value'])) {
                    $newSpecification = [
                        'key' => $specificationData['key'],
                        'value' => $specificationData['value'],
                        'image' => null,
                    ];

                    if ($request->hasFile("specifications.{$index}.image")) {
                        $path = $request->file("specifications.{$index}.image")->store('mice/specifications', 'public');
                        $newSpecification['image'] = Storage::url($path);
                    }

                    $specifications[] = $newSpecification;
                }
            }
        }
        $miceRoom->specifications = json_encode($specifications);
        $miceRoom->save();

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('public/mice');
                $miceRoom->images()->create(['path' => Storage::url($path)]);
            }
        }

        return redirect()->route('admin.mice.index')->with('success', 'MICE room created successfully.');
    }

    public function update(Request $request, MiceRoom $miceRoom)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:mice_rooms,slug,' . $miceRoom->id,
            'description' => 'required|string',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'specifications.*.key' => 'nullable|string',
            'specifications.*.value' => 'nullable|string',
            'specifications.*.image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $miceRoom->update($request->only('name', 'slug', 'description', 'meta_title', 'meta_description'));

        $specifications = [];
        if ($request->input('specifications')) {
            foreach ($request->input('specifications') as $index => $specificationData) {
                 if (!empty($specificationData['key']) && !empty($specificationData['value'])) {
                    $newSpecification = [
                        'key' => $specificationData['key'],
                        'value' => $specificationData['value'],
                        'image' => $specificationData['image_path'] ?? null, // hidden input with old image path
                    ];

                    if ($request->hasFile("specifications.{$index}.image")) {
                        // Delete old image if it exists
                        if ($newSpecification['image']) {
                            $oldImagePath = str_replace('/storage/', '', $newSpecification['image']);
                            Storage::disk('public')->delete($oldImagePath);
                        }

                        $path = $request->file("specifications.{$index}.image")->store('mice/specifications', 'public');
                        $newSpecification['image'] = Storage::url($path);
                    }

                    $specifications[] = $newSpecification;
                }
            }
        }

        $miceRoom->specifications = json_encode($specifications);
        $miceRoom->save();

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('public/mice');
                $miceRoom->images()->create(['path' => Storage::url($path)]);
            }
        }

        return redirect()->route('admin.mice.index')->with('success', 'MICE room updated successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(MiceRoom $mouse)
    {
        return view('admin.mice.edit', ['miceRoom' => $mouse]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MiceRoom $mouse)
    {
        // Hapus semua file gambar yang berelasi dari storage
        foreach ($mouse->images as $image) {
            Storage::disk('public')->delete($image->path);
            $image->delete(); // Hapus record gambar dari tabel images
        }
        
        // Hapus record mice room
        $mouse->delete();
        
        return redirect()->route('admin.mice.index')->with('success', 'MICE Room deleted successfully.');
    }
}