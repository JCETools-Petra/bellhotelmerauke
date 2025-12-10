<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\Commission;
use Illuminate\Support\Facades\Gate;
use App\Models\Affiliate;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth;
use App\Services\CommissionService; // Tambahkan import Service

class BookingController extends Controller
{
    protected $commissionService;

    // Inject CommissionService agar bisa digunakan di seluruh fungsi controller ini
    public function __construct(CommissionService $commissionService)
    {
        $this->commissionService = $commissionService;
    }

    public function index(Request $request)
    {
        $query = Booking::with(['room', 'miceKit', 'user', 'affiliate.user']);

        // === FILTER & SECURITY CHECK ===
        
        if ($request->type == 'mice') {
            // SECURITY: Front Office DILARANG akses MICE
            if (Auth::user()->role == 'frontoffice') {
                abort(403, 'Akses Ditolak: Front Office tidak diizinkan mengakses data MICE.');
            }
            $query->whereNotNull('mice_kit_id');
            $title = 'MICE Bookings';
        } else {
            // Default: Room Bookings
            $query->whereNotNull('room_id');
            $title = 'Room Bookings';
        }

        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        $bookings = $query->latest()->paginate(15);

        return view('admin.bookings.index', compact('bookings', 'title'));
    }

    public function create()
    {
        return redirect()->route('admin.bookings.index');
    }

    public function store(Request $request)
    {
        return redirect()->route('admin.bookings.index');
    }

    public function show(Booking $booking)
    {
        return redirect()->route('admin.bookings.index');
    }

    public function edit(Booking $booking)
    {
        return redirect()->route('admin.bookings.index');
    }

    public function update(Request $request, Booking $booking)
    {
        // === SECURITY CHECK: Front Office vs MICE ===
        // Jika user adalah Front Office DAN booking ini adalah MICE (punya mice_kit_id), tolak.
        if (Auth::user()->role == 'frontoffice' && $booking->mice_kit_id) {
            abort(403, 'Akses Ditolak: Front Office tidak berhak mengubah status booking MICE.');
        }

        // Validasi
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled,completed,awaiting_arrival',
            'payment_status' => 'sometimes|in:pending,paid,failed', 
        ]);

        // Update Status
        $booking->status = $request->status;
        if ($request->has('payment_status')) {
            $booking->payment_status = $request->payment_status;
        }
        $booking->save();

        // === LOGIKA KOMISI OTOMATIS (MENGGUNAKAN SERVICE) ===
        // Komisi dibuat jika:
        // 1. Status Booking = Confirmed atau Completed
        // 2. ATAU Payment Status = Paid (meskipun status booking belum completed)
        
        $isConfirmedOrCompleted = in_array($booking->status, ['confirmed', 'completed']);
        $isPaid = $booking->payment_status === 'paid';

        if (($isConfirmedOrCompleted || $isPaid) && $booking->affiliate_id) {
            // Panggil service untuk menghitung dan menyimpan komisi
            $this->commissionService->createForBooking($booking);
        } 
        // Hapus komisi jika status dibatalkan/pending (dan belum lunas)
        elseif (in_array($booking->status, ['cancelled', 'pending']) && !$isPaid) {
            Commission::where('booking_id', $booking->id)->delete();
        }

        return back()->with('success', 'Booking status updated successfully.');
    }

    public function destroy(Booking $booking)
    {
        // SECURITY CHECK: Front Office tidak boleh hapus MICE
        if (Auth::user()->role == 'frontoffice' && $booking->mice_kit_id) {
            abort(403, 'Akses Ditolak: Front Office tidak berhak menghapus booking MICE.');
        }

        $booking->delete();
        return back()->with('success', 'Booking deleted successfully.');
    }
    
    public function confirmPayAtHotel(Booking $booking)
    {
        // SECURITY CHECK
        if (Auth::user()->role == 'frontoffice' && $booking->mice_kit_id) {
            abort(403, 'Akses Ditolak.');
        }

        if ($booking->payment_method !== 'pay_at_hotel') {
            return back()->with('error', 'Fitur ini hanya untuk booking Pay at Hotel.');
        }

        // 1. Update Status jadi Confirmed & Paid
        $booking->update([
            'status' => 'confirmed', 
            'payment_status' => 'paid'
        ]);

        // 2. Trigger Pembuatan Komisi via Service
        // Menggunakan service yang sama agar perhitungannya konsisten
        if ($booking->affiliate_id) {
            $this->commissionService->createForBooking($booking);
        }

        return back()->with('success', "Booking #{$booking->booking_code} confirmed & commission generated.");
    }

    // Fungsi createCommissionForBooking() dihapus karena sudah digantikan oleh CommissionService
}