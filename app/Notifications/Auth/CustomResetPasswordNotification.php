<?php

namespace App\Notifications\Auth;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Notifications\Channels\WhatsAppChannel; // Panggil channel baru kita

class CustomResetPasswordNotification extends Notification
{
    use Queueable;

    public $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Tentukan kanal pengiriman notifikasi (email, whatsapp, dll).
     */
    public function via($notifiable)
    {
        // Kirim melalui kanal WhatsApp yang sudah kita buat
        return [WhatsAppChannel::class];
    }

    /**
     * Format pesan untuk dikirim via WhatsApp.
     */
    public function toWhatsApp($notifiable)
    {
        // Buat URL reset password
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        // Format pesan
        return "Halo,\nAnda menerima pesan ini karena kami menerima permintaan reset password untuk akun Anda.\n\nKlik link di bawah ini untuk mereset password Anda:\n" . $url . "\n\nJika Anda tidak merasa melakukan permintaan ini, abaikan saja pesan ini.";
    }
}