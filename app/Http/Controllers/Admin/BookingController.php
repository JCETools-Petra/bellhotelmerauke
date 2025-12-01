<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\Commission;
use Illuminate\Support\Facades\Gate;
use App\Models\Affiliate;
use App\Models\Setting;
use Illuminate\Support\Facades\Auth; // Tambahkan ini

class BookingController extends Controller
{
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
            'payment_status' => 'sometimes|in:pending,paid,failed', // Tambahkan 'sometimes' agar opsional jika tidak dikirim
        ]);

        // Update Status
        $booking->status = $request->status;
        if ($request->has('payment_status')) {
            $booking->payment_status = $request->payment_status;
        }
        $booking->save();

        $newStatus = $booking->status;

        // === LOGIKA KOMISI OTOMATIS ===
        
        // KONDISI A: Status Sah (Confirmed/Completed) -> BUAT Komisi
        if (in_array($newStatus, ['confirmed', 'completed']) && $booking->affiliate_id) {
            
            // Cek duplikasi agar tidak double
            $existingCommission = Commission::where('booking_id', $booking->id)->first();

            if (!$existingCommission) {
                $affiliate = $booking->affiliate;
                $commissionAmount = 0;
                $rate = 0;
                $notes = '';
                $shouldCreate = false;

                // 1. MICE Booking
                if ($booking->mice_kit_id) {
                    $rate = Setting::where('key', 'mice_commission_rate')->value('value') ?? 2.5;
                    $commissionAmount = $booking->total_price * ($rate / 100);
                    
                    $pkgName = $booking->miceKit->title ?? 'Unknown Package';
                    $notes = "MICE Event: {$booking->event_name}\nPackage: {$pkgName}";
                    $shouldCreate = true;
                }
                // 2. Room Booking
                elseif ($booking->room_id && $booking->room) {
                    $rate = $affiliate->commission_rate ?? 0;
                    $commissionAmount = $booking->total_price * ($rate / 100);
                    
                    $roomName = $booking->room->name;
                    $notes = "Room Booking: {$roomName}\nGuest: {$booking->guest_name}";
                    $shouldCreate = true;
                }

                // Simpan ke Database
                if ($shouldCreate && $commissionAmount > 0) {
                    Commission::create([
                        'affiliate_id'      => $affiliate->id,
                        'booking_id'        => $booking->id,
                        'commission_amount' => $commissionAmount,
                        'rate'              => $rate,
                        'status'            => 'unpaid',
                        'notes'             => $notes,
                    ]);
                }
            }
        } 
        
        // KONDISI B: Status TIDAK SAH (Pending, Cancelled, Awaiting) -> HAPUS Komisi
        else {
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

        if ($booking->payment_method !== 'pay_at_hotel' || $booking->status !== 'awaiting_arrival') {
            return back()->with('error', 'This booking is not a valid "Pay at Hotel" booking awaiting confirmation.');
        }

        // Update status (Logika komisi akan otomatis dipanggil via update method di atas jika kita panggil manual,
        // tapi disini kita buat manual saja agar lebih explisit seperti kode lama Anda)

        // 1. Update Status
        $booking->update([
            'status' => 'confirmed', 
            'payment_status' => 'paid' // Asumsi bayar di hotel = lunas saat konfirmasi
        ]);

        // 2. Trigger Pembuatan Komisi (Panggil fungsi internal atau biarkan update method yang handle jika lewat route)
        // Karena kita pakai $booking->update() langsung ke model, method update() controller TIDAK terpanggil otomatis.
        // Jadi kita harus panggil logika komisi secara manual di sini.
        
        $this->createCommissionForBooking($booking);

        return back()->with('success', "Booking #{$booking->id} confirmed & commission generated.");
    }

    private function createCommissionForBooking(Booking $booking)
    {
        if (!$booking->affiliate_id) return;
        
        // Cek duplikat dulu
        if(Commission::where('booking_id', $booking->id)->exists()) return;

        $affiliate = Affiliate::find($booking->affiliate_id);
        if (!$affiliate) return;

        // Logika hitung (Sama seperti di update)
        $commissionAmount = 0;
        $rate = 0;
        $notes = '';

        if ($booking->mice_kit_id) {
            $rate = Setting::where('key', 'mice_commission_rate')->value('value') ?? 2.5;
            $commissionAmount = $booking->total_price * ($rate / 100);
            $notes = "MICE Event (Pay at Hotel): {$booking->event_name}";
        } elseif ($booking->room_id) {
            $rate = $affiliate->commission_rate;
            $commissionAmount = $booking->total_price * ($rate / 100);
            $notes = "Room Booking (Pay at Hotel): #{$booking->id}";
        }

        if ($commissionAmount > 0) {
            Commission::create([
                'affiliate_id' => $affiliate->id,
                'booking_id' => $booking->id,
                'commission_amount' => $commissionAmount,
                'rate' => $rate,
                'status' => 'unpaid',
                'notes' => $notes,
            ]);
        }
    }
}