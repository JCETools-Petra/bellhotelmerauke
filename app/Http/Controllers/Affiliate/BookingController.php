<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Room;
use App\Models\Booking;
use App\Models\PriceOverride;
use App\Models\Commission;
use App\Models\Setting; // Ditambahkan
use App\Helpers\FonnteApi; // Ditambahkan
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
// Tambahkan ini jika belum ada
use Midtrans\Config as MidtransConfig;
use Midtrans\Snap as MidtransSnap;

class BookingController extends Controller
{
    /**
     * Display a listing of the resource.
     * FITUR INI TETAP ADA
     */
    public function index()
    {
        $affiliate = Auth::user()->affiliate;
        if (!$affiliate) {
            return redirect()->route('affiliate.dashboard')->with('error', 'Affiliate data not found. Please contact support.');
        }

        $bookings = Booking::where('affiliate_id', $affiliate->id)
                            ->with('room')
                            ->latest()
                            ->paginate(10);
                            
        return view('frontend.affiliate.bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new resource.
     * FITUR INI TETAP ADA (menampilkan harga awal di form)
     */
    public function create(Request $request)
    {
        $searchParams = $request->all();
        $rooms = Room::where('is_available', true)->get();
        $checkinDate = null;

        if (isset($searchParams['checkin'])) {
            $checkinDate = Carbon::createFromFormat('d-m-Y', $searchParams['checkin'])->format('Y-m-d');
        }

        foreach ($rooms as $room) {
            $currentPrice = $room->price;
            if ($checkinDate) {
                $override = PriceOverride::where('room_id', $room->id)
                                        ->where('date', $checkinDate)
                                        ->first();
                if ($override) {
                    $currentPrice = $override->price;
                }
            }
            if ($room->discount_percentage > 0) {
                $discountAmount = $currentPrice * ($room->discount_percentage / 100);
                $currentPrice -= $discountAmount;
            }
            $room->price = $currentPrice;
        }
        
        return view('frontend.affiliate.bookings.create', compact('rooms', 'searchParams'));
    }

    /**
     * Store a newly created resource in storage.
     * FITUR INI DIPERBARUI DENGAN LOGIKA PEMBAYARAN
     */
    public function store(Request $request)
    {
        $affiliate = Auth::user()->affiliate;
        if (!$affiliate) {
            abort(403, 'Your affiliate account is not properly configured.');
        }

        $validated = $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'checkin' => 'required|date_format:d-m-Y',
            'checkout' => 'required|date_format:d-m-Y|after:checkin',
            'num_rooms' => 'required|integer|min:1',
            'guest_name' => 'required|string|max:255',
            'guest_phone' => 'required|string|max:20',
            'guest_email' => 'nullable|email|max:255',
            'payment_method' => 'required|in:online,pay_at_hotel',
        ]);

        $room = Room::findOrFail($validated['room_id']);
        $checkin = Carbon::createFromFormat('d-m-Y', $validated['checkin']);
        $checkout = Carbon::createFromFormat('d-m-Y', $validated['checkout']);
        
        // Logika perhitungan harga dinamis Anda sudah benar dan dipertahankan
        $totalPrice = 0;
        $discountPercentage = $room->discount_percentage;

        for ($date = $checkin->copy(); $date->lt($checkout); $date->addDay()) {
            $override = $room->priceOverrides()->where('date', $date->format('Y-m-d'))->first();
            $nightlyPrice = $override ? $override->price : $room->price;
            
            if ($discountPercentage > 0 && !$override) { // Diskon hanya berlaku jika tidak ada harga khusus
                $discountAmount = $nightlyPrice * ($discountPercentage / 100);
                $nightlyPrice -= $discountAmount;
            }
            $totalPrice += $nightlyPrice;
        }

        $finalPrice = $totalPrice * $validated['num_rooms'];

        $booking = Booking::create([
            'room_id' => $room->id,
            'affiliate_id' => $affiliate->id,
            'guest_name' => $validated['guest_name'],
            'guest_phone' => $validated['guest_phone'],
            
            // --- PERBAIKAN DI SINI ---
            // Menggunakan ?? '' untuk memastikan nilai tidak null jika validasi mengizinkan null
            'guest_email' => $validated['guest_email'] ?? '', 
            
            'checkin_date' => $checkin->format('Y-m-d'),
            'checkout_date' => $checkout->format('Y-m-d'),
            'num_rooms' => $validated['num_rooms'],
            'total_price' => $finalPrice,
            
            // --- PERUBAHAN STATUS ---
            // Jika bayar online, status 'pending'. Jika bayar di hotel, status 'awaiting_arrival'.
            'status' => $validated['payment_method'] === 'online' ? 'pending' : 'awaiting_arrival',
            
            'payment_method' => $validated['payment_method'],
            'access_token' => Str::random(32),
        ]);

        // ==========================================================
        // PENAMBAHAN LOGIKA BERDASARKAN METODE PEMBAYARAN
        // ==========================================================
        
        // Jika bayar online, lanjutkan ke Midtrans (logika lama Anda)
        if ($validated['payment_method'] === 'online') {
            // (Logika redirect ke booking.payment tetap sama)
            return redirect()->route('booking.payment', ['booking' => $booking->access_token]);
        }
        
        // Jika bayar di hotel, kirim notifikasi dan redirect ke halaman sukses
        if ($validated['payment_method'] === 'pay_at_hotel') {
            $this->sendPayAtHotelNotifications($booking);

            return redirect()->route('booking.success', $booking->access_token)
                             ->with('success_title', 'Booking Diterima!')
                             ->with('success_message', 'Booking Anda telah kami terima dan menunggu konfirmasi. Pembayaran akan dilakukan di hotel.');
        }
    }

    /**
     * ==========================================================
     * METODE BARU UNTUK MENGIRIM NOTIFIKASI WHATSAPP
     * ==========================================================
     */
    private function sendPayAtHotelNotifications(Booking $booking)
    {
        $settings = Setting::pluck('value', 'key');
        
        $adminReceiver = $settings['whatsapp_admin_receiver'] ?? null;
        $supervisorReceivers = explode(',', $settings['whatsapp_supervisor_receivers'] ?? '');
        $adminTemplate = $settings['whatsapp_pay_at_hotel_admin_template'] ?? '';
        $customerTemplate = $settings['whatsapp_pay_at_hotel_customer_template'] ?? '';

        // Siapkan data pengganti
        $replacements = [
            '{booking_id}' => $booking->id,
            '{affiliate_name}' => $booking->affiliate->user->name ?? 'N/A',
            '{guest_name}' => $booking->guest_name,
            '{guest_phone}' => $booking->guest_phone,
            '{guest_email}' => $booking->guest_email,
            '{room_name}' => $booking->room->name,
            '{checkin_date}' => Carbon::parse($booking->checkin_date)->format('d F Y'),
            '{checkout_date}' => Carbon::parse($booking->checkout_date)->format('d F Y'),
            '{total_price}' => 'Rp ' . number_format($booking->total_price, 0, ',', '.'),
        ];
        
        // Kirim ke customer jika nomor HP ada
        if (!empty($customerTemplate) && !empty($booking->guest_phone)) {
            $customerMessage = str_replace(array_keys($replacements), array_values($replacements), $customerTemplate);
            FonnteApi::sendMessage($booking->guest_phone, $customerMessage);
        }
        
        // Kirim ke semua nomor admin dan supervisor
        if (!empty($adminTemplate)) {
            $adminMessage = str_replace(array_keys($replacements), array_values($replacements), $adminTemplate);
            
            $allReceivers = array_merge([$adminReceiver], $supervisorReceivers);
            $uniqueReceivers = array_filter(array_unique(array_map('trim', $allReceivers)));

            foreach ($uniqueReceivers as $receiver) {
                if (!empty($receiver)) {
                    FonnteApi::sendMessage($receiver, $adminMessage);
                }
            }
        }
    }
}