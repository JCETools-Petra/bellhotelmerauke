<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\MiceInquiry;
use Illuminate\Http\Request;

class MiceInquiryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'mice_room_id' => 'required|exists:mice_rooms,id',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|regex:/^08[0-9]{8,11}$/',
            'event_type' => 'required|string',
            'event_other_description' => 'required_if:event_type,other|nullable|string',
        ]);
    
        $inquiry = MiceInquiry::create($validated);
    
        // Cek inquiry untuk SOTA ROOM
        $room = \App\Models\MiceRoom::find($validated['mice_room_id']);
        if ($room && $room->name === 'SOTA ROOM') {
            $adminPhone = env('ADMIN_WHATSAPP_NUMBER'); // Pastikan sudah di .env
            $message = "Inquiry for SOTA ROOM\nNama: {$validated['customer_name']}\nNomor: {$validated['customer_phone']}\nJenis acara: {$validated['event_type']}";
            \App\Helpers\FonnteApi::sendMessageWithDelay($adminPhone, $message, 2); // 2 detik delay
        }
    
        return back()->with('success', 'Thank you! Our sales team will contact you shortly.');
    }
}