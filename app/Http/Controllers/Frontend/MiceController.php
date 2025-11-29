<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\MiceRoom;

class MiceController extends Controller
{
    /**
     * Display a listing of the MICE rooms.
     */
    public function index()
    {
        $miceRooms = MiceRoom::where('is_available', true)->paginate(10);
        return view('frontend.mice.index', compact('miceRooms'));
    }

    /**
     * Display the specified MICE room.
     */
    public function show($slug)
    {
        $miceRoom = MiceRoom::where('slug', $slug)->where('is_available', true)->firstOrFail();

        // BENAR: Kirim data ke view dengan nama 'mice'
        return view('frontend.mice.show', ['mice' => $miceRoom]);
    }
}