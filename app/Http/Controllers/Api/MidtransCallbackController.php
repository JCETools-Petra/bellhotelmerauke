<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Booking;
use App\Helpers\FonnteApi;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use App\Models\Commission;
use App\Services\CommissionService; // Tambahkan ini
use Midtrans\Config; // Tambahkan ini agar tidak error di class Config
use Midtrans\Notification; // Tambahkan ini

class MidtransCallbackController extends Controller
{
    protected $commissionService;

    public function __construct(CommissionService $commissionService)
    {
        $this->commissionService = $commissionService;
    }

    public function handle(Request $request)
    {
        // Konfigurasi Midtrans
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        
        try {
            $notification = new Notification();
        } catch (\Exception $e) {
            return response()->json(['error' => 'Invalid notification object.'], 500);
        }
        
        $transactionStatus = $notification->transaction_status;
        $orderId = explode('-', $notification->order_id)[0]; // Ambil ID booking asli
        $fraudStatus = $notification->fraud_status;

        $booking = Booking::find($orderId);

        if (!$booking) {
            return response()->json(['error' => 'Booking not found.'], 404);
        }

        // Jangan proses notifikasi yang sama dua kali
        if ($booking->status === 'paid' || $booking->status === 'success') {
            return response()->json(['status' => 'ok', 'message' => 'Booking already paid.']);
        }

        // Logika pembaruan status booking
        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            if ($fraudStatus == 'accept') {
                $booking->status = 'paid'; // Sesuaikan dengan standar enum Anda ('paid' atau 'success')
                $booking->payment_status = 'paid';
                $booking->save();

                // ======================= PERBAIKAN =======================
                // Panggil CommissionService untuk menghitung dan menyimpan komisi
                try {
                    $this->commissionService->createForBooking($booking);
                    Log::info('Commission created via handle() for Booking ID: ' . $booking->id);
                } catch (\Exception $e) {
                    Log::error('Failed to create commission via handle(): ' . $e->getMessage());
                }
                // =========================================================
            }
        } else if ($transactionStatus == 'cancel' || $transactionStatus == 'deny' || $transactionStatus == 'expire') {
            $booking->status = 'cancelled';
            $booking->payment_status = 'failed';
            $booking->save();
        } else if ($transactionStatus == 'pending') {
            $booking->payment_status = 'pending';
            $booking->save();
        }

        return response()->json(['status' => 'ok']);
    }
    
    public function callback(Request $request)
    {
        // 1. Set Server Key & Log
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = config('midtrans.is_production');
        Log::info('--- Midtrans Callback Received ---', $request->all());

        // 2. Validate Signature Key
        $hashed = hash('sha512', $request->order_id . $request->status_code . $request->gross_amount . config('midtrans.server_key'));
        if ($hashed != $request->signature_key) {
            Log::error('Invalid Signature Key.');
            return response()->json(['message' => 'Invalid signature'], 403);
        }
        Log::info('Signature Key is valid.');

        // 3. Differentiate between Test & Real Transactions
        if (Str::startsWith($request->order_id, 'payment_notif_test_')) {
            Log::info('Test notification received from Midtrans dashboard. Responding with success.');
            return response()->json(['message' => 'Test notification processed successfully']);
        }

        $orderIdParts = explode('-', $request->order_id);
        $bookingId = $orderIdParts[1] ?? null; 
        
        // Fallback jika format order_id berbeda (misal tidak pakai dash)
        if (!$bookingId && is_numeric($orderIdParts[0])) {
             $bookingId = $orderIdParts[0];
        }

        if (!$bookingId || !is_numeric($bookingId)) {
            Log::error('Could not extract a valid numeric ID from order_id: ' . $request->order_id);
            return response()->json(['message' => 'Invalid order_id format'], 400);
        }
        
        Log::info('Extracted numeric booking ID: ' . $bookingId);
        $booking = Booking::find($bookingId);
        
        if (!$booking) {
            Log::error('Booking not found for extracted ID: ' . $bookingId);
            return response()->json(['message' => 'Booking not found'], 404);
        }

        Log::info('Found booking ID: ' . $booking->id . ' with current status: "' . $booking->status . '"');

        // 4. Update booking status on successful payment
        if (($request->transaction_status == 'capture' || $request->transaction_status == 'settlement')) {
            // Cek jika status belum success/paid agar tidak double process
            if ($booking->status != 'success' && $booking->status != 'paid') {
                
                Log::info('Transaction is successful. Updating status and sending notifications.');

                $booking->status = 'success'; // Atau 'paid', sesuaikan dengan konsistensi database Anda
                $booking->payment_status = 'paid';
                
                // Simpan info metode pembayaran
                $paymentType = $request->payment_type;
                $paymentMethod = Str::title(str_replace('_', ' ', $paymentType));

                if ($paymentType == 'bank_transfer' && isset($request->va_numbers[0]['bank'])) {
                    $bank = strtoupper($request->va_numbers[0]['bank']);
                    $paymentMethod = "$bank Virtual Account"; 
                } elseif ($paymentType == 'qris') {
                     $acquirer = isset($request->acquirer) ? Str::title($request->acquirer) : 'QRIS';
                     $paymentMethod = "QRIS ($acquirer)";
                }

                $booking->payment_method = $paymentMethod;
                $booking->save();
                
                // ======================= PERBAIKAN UTAMA =======================
                // Panggil Service Komisi di sini!
                try {
                    $this->commissionService->createForBooking($booking);
                    Log::info('Commission created via callback() for Booking ID: ' . $booking->id);
                } catch (\Exception $e) {
                    Log::error('Failed to create commission via callback(): ' . $e->getMessage());
                }
                // ===============================================================

                $this->sendWhatsAppNotifications($booking);
            } else {
                Log::info('Booking status is already paid/success. Skipping commission generation.');
            }
        } else {
            Log::info('Transaction status is "' . $request->transaction_status . '". No action taken.');
        }

        Log::info('--- Midtrans Callback Processed ---');
        return response()->json(['message' => 'Callback processed successfully']);
    }

    private function sendWhatsAppNotifications($booking)
    {
        Log::info('Preparing to send WhatsApp notifications for booking ID: ' . $booking->id);
        try {
            $customerTemplate = settings('whatsapp_customer_message', 'Terima kasih! Pembayaran untuk booking ID: {booking_id} telah kami terima.');
            $adminTemplate = settings('whatsapp_admin_message', 'Pembayaran baru diterima untuk Booking ID: {booking_id}');

            // Prepare replacement data
            $replacements = [
                '{guest_name}'    => $booking->guest_name,
                '{booking_id}'    => $booking->booking_code ?? $booking->id, // Gunakan booking_code jika ada
                '{guest_phone}'   => $booking->guest_phone,
                '{guest_email}'   => $booking->guest_email,
                '{checkin_date}'  => \Carbon\Carbon::parse($booking->checkin_date)->format('d M Y'),
                '{checkout_date}' => \Carbon\Carbon::parse($booking->checkout_date)->format('d M Y'),
                '{payment_method}' => $booking->payment_method ?: 'N/A',
            ];

            // Create final messages
            $customerMessage = str_replace(array_keys($replacements), array_values($replacements), $customerTemplate);
            $adminMessage = str_replace(array_keys($replacements), array_values($replacements), $adminTemplate);
            
            // Send to customer
            $customerPhone = $booking->guest_phone;
            if ($customerPhone) {
                Log::info('Sending message to customer: ' . $customerPhone);
                FonnteApi::sendMessageWithDelay($customerPhone, $customerMessage);
            }

            // Send to Admin (Mengambil dari setting atau .env)
            $adminPhoneNumber = env('ADMIN_WHATSAPP_NUMBER'); // Pastikan variabel ini ada
            
            if ($adminPhoneNumber) {
                Log::info('Sending message to admin: ' . $adminPhoneNumber);
                FonnteApi::sendMessageWithDelay($adminPhoneNumber, $adminMessage);
            } else {
                Log::warning('ADMIN_WHATSAPP_NUMBER is not configured in .env file.');
            }
            Log::info('WhatsApp notification process finished.');
        } catch (\Exception $e) {
            Log::error('CRITICAL: Failed to send WhatsApp notification: ' . $e->getMessage());
        }
    }
}