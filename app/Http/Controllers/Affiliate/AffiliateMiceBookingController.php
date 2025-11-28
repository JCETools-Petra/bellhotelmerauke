<?php

namespace App\Http\Controllers\Affiliate;

use App\Http\Controllers\Controller;
use App\Models\MiceKit;
use App\Models\MiceInquiry;
use App\Models\Setting;
use App\Services\CommissionService;
use App\Helpers\FonnteApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AffiliateMiceBookingController extends Controller
{
    // Rate Komisi Khusus MICE
    protected $miceCommissionRate = 2.5;

    /**
     * Menampilkan Halaman List Paket
     */
    public function index()
    {
        $miceKits = MiceKit::all();
        return view('frontend.affiliate.special_mice.index', compact('miceKits'));
    }

    /**
     * Menampilkan Halaman Detail & Form Booking
     */
    public function show($id)
    {
        $miceKit = MiceKit::findOrFail($id);
        $commissionRate = $this->miceCommissionRate;
        
        // Ambil data affiliate user yang sedang login
        $user = Auth::user();
        $affiliate = $user->affiliate; 

        return view('frontend.affiliate.special_mice.show', compact('miceKit', 'affiliate', 'commissionRate'));
    }

    /**
     * Memproses Penyimpanan Booking
     */
    public function store(Request $request, CommissionService $commissionService)
    {
        $request->validate([
            'mice_kit_id'   => 'required|exists:mice_kits,id',
            'event_name'    => 'required|string|max:255',
            'phone'         => 'required|numeric|digits_between:10,15',
            'check_in_date' => 'required|date|after:today',
            'pax'           => 'required|integer|min:10',
            'total_price'   => 'required|numeric|min:0',
            'note'          => 'nullable|string',
        ]);

        $user = Auth::user();
        $affiliate = $user->affiliate;

        // Pastikan user punya profil affiliate
        if (!$affiliate) {
            return redirect()->back()->with('error', 'Akun Anda belum terdaftar sebagai affiliate aktif.');
        }

        // 1. Simpan Data Inquiry (Status: New/Pending)
        $inquiry = MiceInquiry::create([
            'affiliate_id'   => $affiliate->id,
            'mice_kit_id'    => $request->mice_kit_id,
            'mice_room_id'   => null,
            'customer_name'  => $user->name, 
            'customer_phone' => $request->phone,
            'event_type'     => $request->event_name,
            'event_date'     => $request->check_in_date,
            'pax'            => $request->pax,
            'total_price'    => $request->total_price,
            'event_other_description' => $request->note,
            'status'         => 'new', 
        ]);

        // 2. Kirim Notifikasi WhatsApp
        $this->sendWhatsAppNotifications($inquiry, $request->phone);

        return redirect()->route('affiliate.dashboard')
            ->with('success', 'Penawaran berhasil dikirim! Menunggu konfirmasi admin untuk pencatatan komisi.');
    }

    /**
     * Kirim Pesan WA
     */
    private function sendWhatsAppNotifications($inquiry, $customerPhone)
    {
        // Ambil nomor dari database settings
        $adminPhone = Setting::where('key', 'whatsapp_admin_receiver')->value('value');
        $supervisorPhone = Setting::where('key', 'whatsapp_supervisor_receivers')->value('value');

        $affiliateName = Auth::user()->name;
        $miceKitName = $inquiry->miceKit->title ?? 'Paket Custom';
        $formattedPrice = number_format($inquiry->total_price, 0, ',', '.');
        $formattedDate = date('d M Y', strtotime($inquiry->event_date));
        $note = $inquiry->event_other_description ?: '-';

        // Pesan Customer (Affiliate)
        $customerMessage = "*KONFIRMASI PENAWARAN MICE*\n" .
                           "Halo, Kak *{$affiliateName}* 👋\n\n" .
                           "Terima kasih! Penawaran MICE Anda telah masuk ke sistem kami dan sedang dalam tahap *Verifikasi Admin*.\n\n" .
                           "📋 *Detail Event:*\n" .
                           "• No. Inquiry: #{$inquiry->id}\n" .
                           "• Event: {$inquiry->event_type}\n" .
                           "• Tanggal: {$formattedDate}\n" .
                           "• Pax: {$inquiry->pax}\n" .
                           "• Paket: {$miceKitName}\n" .
                           "• Total Deal: Rp {$formattedPrice}\n\n" .
                           "⏳ *Status Komisi:* Menunggu Approval Admin\n\n" .
                           "Admin kami akan segera memverifikasi data ini. Mohon ditunggu.\n\n" .
                           "Salam,\n*Bell Hotel Merauke*";

        // Pesan Admin
        $adminMessage = "*🔔 APPROVAL NEEDED: INQUIRY MICE BARU*\n\n" .
                        "🆔 ID: #{$inquiry->id}\n" .
                        "👤 Affiliate: {$affiliateName}\n" .
                        "📞 Kontak: {$customerPhone}\n" .
                        "🎉 Event: {$inquiry->event_type}\n" .
                        "💰 Deal: Rp {$formattedPrice}\n\n" .
                        "👉 Silakan login ke Admin Panel -> Menu MICE Inquiries untuk *ACC* atau *TOLAK* komisi ini.";

        // Kirim
        if ($customerPhone) FonnteApi::sendMessage($customerPhone, $customerMessage);
        if ($adminPhone) FonnteApi::sendMessage($adminPhone, $adminMessage);
        
        if ($supervisorPhone) {
            foreach (explode(',', $supervisorPhone) as $spv) {
                FonnteApi::sendMessage(trim($spv), $adminMessage);
            }
        }
    }
}