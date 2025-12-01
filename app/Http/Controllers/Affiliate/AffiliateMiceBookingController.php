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
        
        // Ambil commission rate dari settings
        $commissionRate = Setting::where('key', 'mice_commission_rate')->value('value') ?? 2.5;

        return view('frontend.affiliate.special_mice.show', compact('miceKit', 'affiliate', 'commissionRate'));
    }

    public function store(Request $request)
    {
        // 1. Validasi Input
        $validated = $request->validate([
            'mice_kit_id' => 'required|exists:mice_kits,id',
            'event_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            // Hapus validasi date strict di sini agar kita bisa parse manual jika perlu
            'check_in_date' => 'required', 
            'pax' => 'required|integer|min:10',
            'total_price' => 'required|numeric|min:0', 
            'note' => 'nullable|string',
        ]);

        $affiliate = Auth::user()->affiliate;
        if (!$affiliate) {
            return redirect()->back()->with('error', 'Affiliate account not found.');
        }

        $miceKit = MiceKit::findOrFail($validated['mice_kit_id']);

        // 2. AMBIL HARGA DARI INPUT USER
        $finalPrice = $validated['total_price']; 

        // 3. KONVERSI TANGGAL (Fix Invalid datetime format)
        try {
            // Coba parse format d-m-Y (format datepicker frontend) ke Y-m-d (format database)
            $checkInDate = Carbon::createFromFormat('d-m-Y', $validated['check_in_date'])->format('Y-m-d');
        } catch (\Exception $e) {
            // Jika gagal (misal format sudah Y-m-d), gunakan parse standar
            try {
                $checkInDate = Carbon::parse($validated['check_in_date'])->format('Y-m-d');
            } catch (\Exception $e) {
                return redirect()->back()->withInput()->withErrors(['check_in_date' => 'Format tanggal tidak valid.']);
            }
        }

        // Ambil rate untuk estimasi
        $commissionRate = Setting::where('key', 'mice_commission_rate')->value('value') ?? 2.5;
        $estimatedCommission = $finalPrice * ($commissionRate / 100);

        // 4. Buat Booking
        $booking = Booking::create([
            'booking_code'    => 'AFF-MICE-' . strtoupper(Str::random(6)),
            'user_id'         => Auth::id(),
            'affiliate_id'    => $affiliate->id,
            'mice_kit_id'     => $miceKit->id,
            'event_name'      => $validated['event_name'],
            'guest_name'      => $validated['event_name'], 
            'guest_phone'     => $validated['phone'],
            'guest_email'     => Auth::user()->email ?? '',
            'checkin_date'    => $checkInDate, // Gunakan tanggal yang sudah dikonversi
            'checkout_date'   => $checkInDate, // MICE biasanya 1 hari, atau sesuaikan logika jika perlu range
            'num_rooms'       => 1,
            'total_price'     => $finalPrice, 
            'pax'             => $validated['pax'],
            'note'            => $validated['note'],
            'payment_method'  => 'pay_at_hotel',
            'payment_status'  => 'pending',
            'status'          => 'pending', 
        ]);

        // 5. Kirim Notifikasi
        $this->sendWhatsAppToSupervisor($booking, $miceKit, $affiliate, $estimatedCommission);
        $this->sendWhatsAppToCustomer($booking, $miceKit);

        return redirect()->route('affiliate.dashboard')
            ->with('success', 'Booking MICE berhasil dikirim! Komisi akan tercatat di history setelah booking disetujui Admin.');
    }

    // ... (Method sendWhatsAppToSupervisor dan sendWhatsAppToCustomer biarkan tetap sama) ...
    
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
        $message .= "Total Deal: Rp " . number_format($booking->total_price, 0, ',', '.') . "\n\n";
        $message .= "👤 *Affiliate:*\n";
        $message .= "Nama: {$affiliate->user->name}\n";
        $message .= "Potensi Komisi: Rp " . number_format($estimatedCommission, 0, ',', '.') . "\n";
        $message .= "📞 *Kontak Customer:*\n";
        $message .= "WA: {$booking->guest_phone}\n\n";
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