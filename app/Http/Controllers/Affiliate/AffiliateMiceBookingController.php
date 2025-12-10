<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\MiceKit;
use App\Models\Booking;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Helpers\FonnteApi;
use Carbon\Carbon;

class AffiliateMiceBookingController extends Controller
{
    // ... (index dan show tetap sama) ...
    public function index()
    {
        $miceKits = MiceKit::all();
        return view('frontend.affiliate.special_mice.index', compact('miceKits'));
    }

    public function show($id)
    {
        $miceKit = MiceKit::findOrFail($id);
        $user = Auth::user();
        $affiliate = $user->affiliate;
        
        if ($affiliate && $affiliate->mice_commission_rate > 0) {
            $commissionRate = $affiliate->mice_commission_rate;
        } else {
            $commissionRate = Setting::where('key', 'mice_commission_rate')->value('value') ?? 2.5;
        }

        return view('frontend.affiliate.special_mice.show', compact('miceKit', 'affiliate', 'commissionRate'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'mice_kit_id' => 'required|exists:mice_kits,id',
            'event_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'check_in_date' => 'required', 
            'pax' => 'required|integer|min:10',
            'total_price' => 'required|numeric|min:0', // Kita terima input user
            'note' => 'nullable|string',
        ]);

        $affiliate = Auth::user()->affiliate;
        if (!$affiliate) {
            return redirect()->back()->with('error', 'Affiliate account not found.');
        }

        $miceKit = MiceKit::findOrFail($validated['mice_kit_id']);

        // 2. HARGA DARI USER (Sesuai keinginan Anda)
        $userPrice = $validated['total_price']; 

        // 3. SAFETY CHECK: Hitung Harga Standar Database
        $standardPrice = $miceKit->price * $validated['pax'];
        
        // Logika Peringatan:
        // Jika harga user berbeda jauh (misal selisih lebih dari 1000 rupiah) dari harga standar
        // Kita tambahkan peringatan otomatis ke dalam 'note' agar admin sadar.
        $priceDifference = $standardPrice - $userPrice;
        $systemNote = "";

        if ($priceDifference > 1000) { 
            // Jika user input LEBIH MURAH dari standar
            $systemNote = "\n[SYSTEM ALERT: Harga Input User (Rp " . number_format($userPrice) . ") lebih rendah dari Harga Standar (Rp " . number_format($standardPrice) . "). Mohon cek kembali sebelum Approval.]";
        } elseif ($priceDifference < -1000) {
            // Jika user input LEBIH MAHAL (jarang terjadi, tapi mungkin tips?)
            $systemNote = "\n[SYSTEM INFO: Harga Input User lebih tinggi dari standar.]";
        }

        // Gabungkan catatan user dengan catatan sistem
        $finalNote = ($validated['note'] ?? '-') . $systemNote;


        // 4. KONVERSI TANGGAL
        try {
            $checkInDate = Carbon::createFromFormat('d-m-Y', $validated['check_in_date'])->format('Y-m-d');
        } catch (\Exception $e) {
            try {
                $checkInDate = Carbon::parse($validated['check_in_date'])->format('Y-m-d');
            } catch (\Exception $e) {
                return redirect()->back()->withInput()->withErrors(['check_in_date' => 'Format tanggal tidak valid.']);
            }
        }

        // Ambil rate dinamis
        if ($affiliate->mice_commission_rate > 0) {
             $commissionRate = $affiliate->mice_commission_rate;
        } else {
             $commissionRate = Setting::where('key', 'mice_commission_rate')->value('value') ?? 2.5;
        }
        
        // Estimasi komisi berdasarkan harga input user
        $estimatedCommission = $userPrice * ($commissionRate / 100);

        // 5. Buat Booking
        $booking = Booking::create([
            'booking_code'    => 'AFF-MICE-' . strtoupper(Str::random(6)),
            'user_id'         => Auth::id(),
            'affiliate_id'    => $affiliate->id,
            'mice_kit_id'     => $miceKit->id,
            'event_name'      => $validated['event_name'],
            'guest_name'      => $validated['event_name'], 
            'guest_phone'     => $validated['phone'],
            'guest_email'     => Auth::user()->email ?? '',
            'checkin_date'    => $checkInDate,
            'checkout_date'   => $checkInDate,
            'num_rooms'       => 1,
            'total_price'     => $userPrice, // Gunakan harga input user
            'pax'             => $validated['pax'],
            'note'            => $finalNote, // Note sudah berisi peringatan jika harga aneh
            'payment_method'  => 'pay_at_hotel',
            'payment_status'  => 'pending',
            'status'          => 'pending', 
        ]);

        // 6. Kirim Notifikasi
        $this->sendWhatsAppToSupervisor($booking, $miceKit, $affiliate, $estimatedCommission);
        $this->sendWhatsAppToCustomer($booking, $miceKit);

        return redirect()->route('affiliate.dashboard')
            ->with('success', 'Booking MICE berhasil dikirim! Menunggu konfirmasi Admin.');
    }

    // ... (fungsi sendWhatsApp tetap sama) ...
    private function sendWhatsAppToSupervisor($booking, $miceKit, $affiliate, $estimatedCommission)
    {
        $settings = Setting::pluck('value', 'key');
        $supervisorReceivers = explode(',', $settings['whatsapp_supervisor_receivers'] ?? '');

        $message = "🔔 *BOOKING MICE BARU (AFFILIATE)*\n\n";
        $message .= "📋 *Detail Booking:*\n";
        $message .= "Kode: {$booking->booking_code}\n";
        $message .= "Paket: {$miceKit->title}\n";
        $message .= "Event: {$booking->event_name}\n";
        $message .= "Tanggal: " . Carbon::parse($booking->checkin_date)->format('d F Y') . "\n";
        $message .= "Total Deal: Rp " . number_format($booking->total_price, 0, ',', '.') . "\n\n"; // Harga dari user
        $message .= "👤 *Affiliate:*\n";
        $message .= "Nama: {$affiliate->user->name}\n";
        $message .= "Potensi Komisi: Rp " . number_format($estimatedCommission, 0, ',', '.') . "\n";
        $message .= "📞 *Kontak Customer:*\n";
        $message .= "WA: {$booking->guest_phone}\n\n";
        
        // Tambahan info jika ada alert harga di note
        if (str_contains($booking->note, 'SYSTEM ALERT')) {
             $message .= "⚠️ *PERINGATAN HARGA:* Harga input berbeda dengan standar sistem. Cek dashboard.\n";
        }

        $message .= "⚠️ *Status:* Pending (Butuh Approval Admin)";
        
        foreach ($supervisorReceivers as $receiver) {
            $receiver = trim($receiver);
            if (!empty($receiver)) {
                FonnteApi::sendMessage($receiver, $message);
            }
        }
    }

    private function sendWhatsAppToCustomer($booking, $miceKit)
    {
        if (empty($booking->guest_phone)) return;

        $message = "Halo Kak, terima kasih telah melakukan pemesanan paket MICE melalui Bell Hotel Merauke. 🙏\n\n";
        $message .= "Berikut detail pesanan Anda:\n";
        $message .= "--------------------------------\n";
        $message .= "🔖 *Kode Booking:* {$booking->booking_code}\n";
        $message .= "📦 *Paket:* {$miceKit->title}\n";
        $message .= "📅 *Tanggal:* " . Carbon::parse($booking->checkin_date)->format('d F Y') . "\n";
        $message .= "👥 *Jumlah:* {$booking->pax} Pax\n";
        $message .= "💰 *Total Estimasi:* Rp " . number_format($booking->total_price, 0, ',', '.') . "\n";
        $message .= "--------------------------------\n\n";
        $message .= "Status saat ini: *Menunggu Konfirmasi Admin*\n";
        $message .= "Sales team kami akan segera menghubungi Anda untuk koordinasi lebih lanjut.\n\n";
        $message .= "_Bell Hotel Merauke_";

        FonnteApi::sendMessageWithDelay($booking->guest_phone, $message, 3);
    }
}