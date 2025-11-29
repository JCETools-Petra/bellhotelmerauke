<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;
use App\Models\Commission;
use Illuminate\Support\Facades\Gate;
use App\Models\Affiliate;

class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['room', 'affiliate.user'])->latest()->paginate(15); // Muat juga data affiliate
        return view('admin.bookings.index', compact('bookings'));
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
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled',
        ]);

        $newStatus = $request->status;
        $booking->status = $newStatus;
        $booking->save();

        // Cek jika status diubah menjadi "confirmed" DAN booking ini memiliki affiliate
        if ($newStatus === 'confirmed' && $booking->affiliate_id) {
            // Cek untuk memastikan komisi belum pernah dibuat untuk booking ini
            $existingCommission = Commission::where('booking_id', $booking->id)->first();

            if (!$existingCommission) {
                $affiliate = $booking->affiliate;
                $room = $booking->room;

                if ($affiliate && $room) {
                    $commissionAmount = ($room->price * $booking->num_rooms) * ($affiliate->commission_rate / 100);

                    Commission::create([
                        'affiliate_id' => $affiliate->id,
                        'booking_id' => $booking->id,
                        'amount' => $commissionAmount,
                        'status' => 'unpaid',
                    ]);
                }
            }
        }
        // Jika status diubah menjadi "cancelled", hapus komisi yang mungkin sudah ada
        elseif ($newStatus === 'cancelled') {
            Commission::where('booking_id', $booking->id)->delete();
        }

        return back()->with('success', 'Booking status updated successfully.');
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return back()->with('success', 'Booking deleted successfully.');
    }
    
    public function confirmPayAtHotel(Booking $booking)
    {
        // 1. Pastikan booking ini memang 'bayar di hotel' dan statusnya benar
        if ($booking->payment_method !== 'pay_at_hotel' || $booking->status !== 'awaiting_arrival') {
            return back()->with('error', 'This booking is not a valid "Pay at Hotel" booking awaiting confirmation.');
        }

        // 2. Buat komisi untuk afiliasi
        $this->createCommissionForBooking($booking);

        // 3. Ubah status booking menjadi 'confirmed'
        $booking->update(['status' => 'confirmed']);

        return back()->with('success', "Booking #{$booking->id} has been confirmed and commission has been generated.");
    }

    private function createCommissionForBooking(Booking $booking)
    {
        // Pastikan booking ini memiliki afiliasi
        if (!$booking->affiliate_id) {
            return;
        }

        $affiliate = Affiliate::find($booking->affiliate_id);
        if (!$affiliate || $affiliate->commission_rate <= 0) {
            return;
        }

        // Hitung jumlah komisi
        $commissionAmount = $booking->total_price * ($affiliate->commission_rate / 100);

        // Buat catatan komisi
        Commission::create([
            'affiliate_id' => $affiliate->id,
            'booking_id' => $booking->id,
            'commission_amount' => $commissionAmount,
            'rate' => $affiliate->commission_rate,
            'status' => 'unpaid', // Komisi siap untuk dibayarkan nanti
            'notes' => 'Commission from Booking ID #' . $booking->id,
        ]);
    }
}