<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MiceInquiry;
use App\Models\MiceRoom;
use App\Models\Affiliate;
use App\Models\Commission;
use App\Models\User;

class MiceInquiryController extends Controller
{
    /**
     * Menampilkan formulir untuk mencatat komisi MICE dan riwayatnya.
     */
    public function index()
    {
        // Ambil semua data yang diperlukan untuk form
        $miceRooms = MiceRoom::where('is_available', true)->orderBy('name')->get();
        
        // Ambil semua user yang memiliki role 'affiliate' dan aktif
        $affiliates = User::where('role', 'affiliate')
                        ->whereHas('affiliate', function ($query) {
                            $query->where('status', 'active');
                        })
                        ->orderBy('name')->get();

        // Ambil riwayat komisi yang berasal dari MICE (kita tandai di notes)
        $miceCommissions = Commission::where('notes', 'like', 'MICE Event:%')
                                ->with('affiliate.user')
                                ->latest()
                                ->paginate(15);

        return view('admin.mice_inquiries.index', compact('miceRooms', 'affiliates', 'miceCommissions'));
    }

    /**
     * Menyimpan data komisi MICE dari formulir.
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'mice_room_id' => 'required|exists:mice_rooms,id',
            'event_name' => 'required|string|max:255',
            'event_date' => 'required|date',
            'total_payment' => 'required|numeric|min:0',
        ]);

        // Temukan profil affiliate berdasarkan user_id
        $affiliate = Affiliate::where('user_id', $request->user_id)->first();
        if (!$affiliate) {
            return back()->with('error', 'Affiliate profile not found for the selected user.');
        }

        $total_payment = $request->total_payment;
        $commission_rate = 2.5;
        $commission_amount = ($total_payment * $commission_rate) / 100;

        $miceRoom = MiceRoom::findOrFail($request->mice_room_id);

        // Buat catatan untuk komisi
        $notes = sprintf(
            "MICE Event: %s\nRoom: %s\nDate: %s\nTotal Payment: %s",
            $request->event_name,
            $miceRoom->name,
            \Carbon\Carbon::parse($request->event_date)->format('d F Y'),
            'Rp ' . number_format($total_payment, 0, ',', '.')
        );

        // Simpan data ke tabel commissions
        Commission::create([
            'affiliate_id' => $affiliate->id,
            'booking_id' => null,
            'commission_amount' => $commission_amount,
            'rate' => $commission_rate,
            
            // --- PERUBAHAN DI SINI ---
            'status' => 'unpaid', // Diubah dari 'paid' menjadi 'unpaid'
            // -------------------------

            'notes' => $notes,
        ]);

        return redirect()->route('admin.mice-inquiries.index')->with('success', 'MICE commission has been successfully recorded.');
    }
    
    public function destroy($id)
    {
        // Langkah 1: Ambil data komisi secara manual dari database menggunakan ID.
        $commission = Commission::find($id);

        // Jika karena alasan tertentu data tidak ditemukan, kembali dengan error.
        if (!$commission) {
            return back()->with('error', 'Commission record not found.');
        }

        // Langkah 2: Lakukan pemeriksaan pada data yang sudah kita ambil secara manual.
        // Kita gunakan stripos untuk memastikan pemeriksaan tidak case-sensitive.
        if (!$commission->notes || stripos($commission->notes, 'MICE Event:') === false) {
            return back()->with('error', 'This commission record cannot be deleted from this page because it is not a MICE commission.');
        }

        // Langkah 3: Hapus data.
        $commission->delete();

        return redirect()->route('admin.mice-inquiries.index')->with('success', 'MICE commission record has been successfully deleted.');
    }
}