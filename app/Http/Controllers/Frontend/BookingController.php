<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Midtrans\Config;
use Midtrans\Snap;
use Illuminate\Support\Str;
use App\Helpers\FonnteApi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\Commission;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'guest_name' => 'required|string|max:255',
            'guest_phone' => 'required|string|max:20',
            'guest_email' => 'required|email|max:255',
            'checkin' => 'required|date_format:d-m-Y',
            'checkout' => 'required|date_format:d-m-Y|after:checkin',
            'num_rooms' => 'required|integer|min:1',
        ]);

        $room = Room::findOrFail($validated['room_id']);
        $checkinDate = Carbon::createFromFormat('d-m-Y', $validated['checkin']);
        $checkoutDate = Carbon::createFromFormat('d-m-Y', $validated['checkout']);
        
        // --- AWAL LOGIKA HARGA BARU ---
        $totalPrice = 0;
        
        // Cek apakah pengguna yang login adalah afiliasi
        $isAffiliate = Auth::check() && Auth::user()->role === 'affiliate';
        $discountPercentage = $isAffiliate ? $room->discount_percentage : 0;

        // Hitung harga per malam, terapkan override dan diskon
        for ($date = $checkinDate->copy(); $date->lt($checkoutDate); $date->addDay()) {
            $override = $room->priceOverrides()->where('date', $date->format('Y-m-d'))->first();
            $nightlyPrice = $override ? $override->price : $room->price;

            // Terapkan diskon jika ada
            if ($discountPercentage > 0) {
                $discountAmount = $nightlyPrice * ($discountPercentage / 100);
                $nightlyPrice -= $discountAmount;
            }
            $totalPrice += $nightlyPrice;
        }

        $finalPrice = $totalPrice * $validated['num_rooms'];
        // --- AKHIR LOGIKA HARGA BARU ---

        $booking = Booking::create([
            'room_id' => $validated['room_id'],
            'guest_name' => $validated['guest_name'],
            'guest_phone' => $validated['guest_phone'],
            'guest_email' => $validated['guest_email'],
            'checkin_date' => $checkinDate->format('Y-m-d'),
            'checkout_date' => $checkoutDate->format('Y-m-d'),
            'num_rooms' => $validated['num_rooms'],
            'total_price' => $finalPrice, // Gunakan harga final yang sudah benar
            'status' => 'pending',
            'access_token' => Str::random(32),
            // Simpan affiliate_id jika yang booking adalah afiliasi
            'affiliate_id' => $isAffiliate ? Auth::user()->affiliate->id : null,
        ]);

        // Arahkan ke pembayaran (logika lama tetap sama)
        if (settings('booking_method', 'direct') == 'direct') {
            return redirect()->route('booking.payment', ['booking' => $booking->access_token]);
        } else {
            // (Optional) Kirim notifikasi WhatsApp jika metode manual
            // $this->sendAdminBookingNotification($booking);
            return redirect()->back()->with('success', 'Permintaan booking Anda telah berhasil dikirim!');
        }
    }

    /**
     * Menampilkan halaman pembayaran (METHOD YANG HILANG).
     */
    public function payment(Booking $booking)
    {
        if ($booking->status !== 'pending') {
            return redirect()->route('home')->with('error', 'This booking has already been processed.');
        }

        $snapToken = null;
        try {
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');
            Config::$isSanitized = true;
            Config::$is3ds = true;

            $guest_email = $booking->guest_email ?? 'guest@example.com';
            $guest_phone = $booking->guest_phone ?? '081234567890';
            $durationInDays = Carbon::parse($booking->checkin_date)->diff(Carbon::parse($booking->checkout_date))->days;

            $params = [
                'transaction_details' => [
                    'order_id' => 'BOOK-' . $booking->id . '-' . time(),
                    'gross_amount' => round($booking->total_price),
                ],
                'customer_details' => [
                    'first_name' => $booking->guest_name,
                    'email' => $guest_email,
                    'phone' => $guest_phone,
                ],
                'item_details' => [[
                    'id' => $booking->id,
                    'price' => round($booking->total_price),
                    'quantity' => 1,
                    'name' => "Booking {$booking->room->name} ({$booking->num_rooms} kamar, {$durationInDays} malam)",
                ]],
            ];

            $snapToken = Snap::getSnapToken($params);
            $booking->snap_token = $snapToken;
            $booking->save();

        } catch (\Exception $e) {
            Log::error('Midtrans Snap Token Error: ' . $e->getMessage());
            if (Auth::check() && Auth::user()->role == 'affiliate') {
                return redirect()->route('affiliate.dashboard')->with('error', 'Failed to create payment session. Please check your Midtrans configuration.');
            }
            return redirect()->route('home')->with('error', 'Sorry, we are unable to process the payment at this moment.');
        }

        return view('frontend.booking.payment', compact('booking', 'snapToken'));
    }

    /**
     * Menampilkan halaman sukses setelah pembayaran.
     */
    public function success(Booking $booking) // Diubah untuk menerima model Booking langsung dari route
    {
        return view('frontend.booking_success', compact('booking'));
    }
    
    /**
     * Mengirim notifikasi booking baru ke admin via WhatsApp untuk metode manual.
     */
    private function sendAdminBookingNotification(Booking $booking)
    {
        try {
            $adminPhoneNumber = env('ADMIN_WHATSAPP_NUMBER');
            if ($adminPhoneNumber) {
                $checkinDate = Carbon::parse($booking->checkin_date)->format('d M Y');
                $checkoutDate = Carbon::parse($booking->checkout_date)->format('d M Y');

                $adminMessage = "🔔 *Permintaan Booking Baru!*\n\n" .
                                "*Booking ID:* {$booking->id}\n" .
                                "*Nama Tamu:* {$booking->guest_name}\n" .
                                "*Telepon:* {$booking->guest_phone}\n" .
                                "*Email:* {$booking->guest_email}\n" .
                                "*Kamar:* {$booking->room->name}\n" .
                                "*Jumlah Kamar:* {$booking->num_rooms}\n" .
                                "*Check-in:* {$checkinDate}\n" .
                                "*Check-out:* {$checkoutDate}\n" .
                                "*Total Harga:* Rp " . number_format($booking->total_price, 0, ',', '.');
                
                FonnteApi::sendMessage($adminPhoneNumber, $adminMessage);
                Log::info('Admin booking notification sent for booking ID: ' . $booking->id);
            } else {
                Log::warning('ADMIN_WHATSAPP_NUMBER is not configured. Could not send booking notification.');
            }
        } catch (\Exception $e) {
            Log::error('Failed to send admin booking notification: ' . $e->getMessage());
        }
    }
}