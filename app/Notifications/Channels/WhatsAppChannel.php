<?php

namespace App\Notifications\Channels;

use Illuminate\Notifications\Notification;
use App\Helpers\FonnteApi; // Memanggil helper Fonnte yang sudah ada

class WhatsAppChannel
{
    public function send($notifiable, Notification $notification)
    {
        $message = $notification->toWhatsApp($notifiable);
        if (! $notifiable->routeNotificationFor('whatsapp')) {
            return;
        }
        
        $recipientNumber = $notifiable->routeNotificationFor('whatsapp');
        FonnteApi::sendMessageWithDelay($recipientNumber, $message);
    }
}