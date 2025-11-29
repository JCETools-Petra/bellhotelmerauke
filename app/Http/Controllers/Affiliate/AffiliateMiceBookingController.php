<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\MiceKit;
use App\Models\Booking;
use App\Models\Commission;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Helpers\FonnteApi;

class AffiliateMiceBookingController extends Controller
{
    public function index()
    {
        $miceKits = MiceKit::all();
        return view('frontend.affiliate.special_mice.index', compact('miceKits'));
    }

    public function show($id)
    {
        $miceKit = MiceKit::findOrFail($id);

        // Ambil data affiliate user yang sedang login
        $user = Auth::user();
        $affiliate = $user->affiliate;

        // PENTING: Ambil commission rate dari settings (tidak bisa diubah user)
        $commissionRate = \App\Models\Setting::where('key', 'mice_commission_rate')->value('value') ?? 2.5;

        return view('frontend.affiliate.special_mice.show', compact('miceKit', 'affiliate', 'commissionRate'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mice_kit_id' => 'required|exists:mice_kits,id',
            'event_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'check_in_date' => 'required|date|after:today',
            'pax' => 'required|integer|min:10',
            'total_price' => 'required|numeric|min:0',
            'note' => 'nullable|string',
        ]);

        $affiliate = Auth::user()->affiliate;
        if (!$affiliate) {
            return redirect()->back()->with('error', 'Affiliate account not found.');
        }

        $miceKit = MiceKit::findOrFail($validated['mice_kit_id']);
        $finalPrice = $validated['total_price'];

        // Ambil commission rate dari settings (dilindungi, tidak bisa diubah user)
        $commissionRate = Setting::where('key', 'mice_commission_rate')->value('value') ?? 2.5;
        $commissionAmount = $finalPrice * ($commissionRate / 100);

        // Buat booking
        $booking = Booking::create([
            'booking_code'    => 'AFF-MICE-' . strtoupper(Str::random(6)),
            'user_id'         => Auth::id(),
            'affiliate_id'    => $affiliate->id,
            'mice_kit_id'     => $miceKit->id,
            'event_name'      => $validated['event_name'],
            'guest_name'      => $validated['event_name'], // Gunakan event_name sebagai guest_name
            'guest_phone'     => $validated['phone'],
            'guest_email'     => Auth::user()->email ?? '',
            'checkin_date'    => $validated['check_in_date'],
            'checkout_date'   => $validated['check_in_date'], // MICE biasanya 1 hari
            'num_rooms'       => 1, // Default untuk MICE
            'total_price'     => $finalPrice,
            'pax'             => $validated['pax'],
            'note'            => $validated['note'],
            'payment_method'  => 'pay_at_hotel',
            'payment_status'  => 'pending',
            'status'          => 'pending', // Menunggu approval admin
        ]);

        // Buat commission dengan status 'unpaid' (menunggu admin approve booking)
        $notes = "MICE Event: {$validated['event_name']}\n";
        $notes .= "Package: {$miceKit->name}\n";
        $notes .= "PAX: {$validated['pax']}\n";
        $notes .= "Event Date: " . \Carbon\Carbon::parse($validated['check_in_date'])->format('d F Y');

        Commission::create([
            'affiliate_id' => $affiliate->id,
            'booking_id' => $booking->id,
            'commission_amount' => $commissionAmount,
            'rate' => $commissionRate,
            'status' => 'unpaid', // Menunggu admin approve
            'notes' => $notes,
        ]);

        // Kirim WhatsApp ke supervisor
        $this->sendWhatsAppToSupervisor($booking, $miceKit, $affiliate, $commissionRate);

        return redirect()->route('affiliate.dashboard')
            ->with('success', 'Booking MICE berhasil! Komisi akan tercatat setelah pembayaran dikonfirmasi admin.');
    }

    /**
     * Kirim WhatsApp notification ke supervisor
     */
    private function sendWhatsAppToSupervisor($booking, $miceKit, $affiliate, $commissionRate)
    {
        $settings = Setting::pluck('value', 'key');
        $supervisorReceivers = explode(',', $settings['whatsapp_supervisor_receivers'] ?? '');

        $message = "🔔 *BOOKING MICE BARU DARI AFFILIATE*\n\n";
        $message .= "📋 *Detail Booking:*\n";
        $message .= "Kode: {$booking->booking_code}\n";
        $message .= "Paket: {$miceKit->name}\n";
        $message .= "Event: {$booking->event_name}\n";
        $message .= "Tanggal: " . \Carbon\Carbon::parse($booking->checkin_date)->format('d F Y') . "\n";
        $message .= "PAX: {$booking->pax} orang\n";
        $message .= "Total Harga: Rp " . number_format($booking->total_price, 0, ',', '.') . "\n\n";
        $message .= "👤 *Affiliate:*\n";
        $message .= "Nama: {$affiliate->user->name}\n";
        $message .= "Komisi Rate: {$commissionRate}%\n";
        $message .= "Komisi: Rp " . number_format($booking->total_price * ($commissionRate / 100), 0, ',', '.') . "\n\n";
        $message .= "📞 *Kontak Customer:*\n";
        $message .= "WhatsApp: {$booking->guest_phone}\n\n";
        $message .= "💳 *Payment:* Pay at Hotel\n";
        $message .= "📌 *Status:* Menunggu Konfirmasi Admin";

        // Kirim ke semua supervisor
        foreach ($supervisorReceivers as $receiver) {
            $receiver = trim($receiver);
            if (!empty($receiver)) {
                FonnteApi::sendMessage($receiver, $message);
            }
        }
    }
}

