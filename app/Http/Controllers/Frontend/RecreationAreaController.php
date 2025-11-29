<?php

 

namespace App\Http\Controllers\Frontend;

 

use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

use App\Models\RecreationArea;

 

class RecreationAreaController extends Controller

{

    /**

     * Menampilkan daftar semua recreation area.

     */

    public function index()

    {

        $recreationAreas = RecreationArea::where('is_active', true)

            ->with('images')

            ->orderBy('order')

            ->get();

 

        return view('frontend.recreation_areas.index', compact('recreationAreas'));

    }

 

    /**

     * Menampilkan detail recreation area berdasarkan slug.

     */

    public function show($slug)

    {

        $recreationArea = RecreationArea::where('slug', $slug)

            ->where('is_active', true)

            ->with('images')

            ->firstOrFail();

 

        return view('frontend.recreation_areas.show', compact('recreationArea'));

    }

}