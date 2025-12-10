<?php

namespace App\Helpers;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log; // <-- Tambahkan ini untuk logging

class FonnteApi
{
    private static function getClient(): Client
    {
        return new Client([
            'base_uri' => 'https://api.fonnte.com',
            'headers' => [
                // PERBAIKAN: Ganti FONNTE_TOKEN menjadi FONNTE_API_TOKEN
                'Authorization' => env('FONNTE_API_TOKEN'),
            ],
        ]);
    }

    public static function sendMessage($target, $message): bool
    {
        // Tambahkan pengecekan token untuk debugging yang lebih baik
        if (!env('FONNTE_API_TOKEN')) {
            Log::error('Fonnte API Token is not set in .env file.');
            return false;
        }

        try {
            $client = self::getClient();
            $response = $client->post('/send', [
                'form_params' => [
                    'target' => $target,
                    'message' => $message,
                ],
            ]);

            $body = json_decode($response->getBody()->getContents(), true);

            // Tambahkan log untuk respons dari Fonnte
            Log::info('Fonnte API Response: ', $body);

            return $body['status'] ?? false;
        } catch (RequestException $e) {
            // Perbaiki logging agar lebih informatif
            Log::error('Fonnte API Error: ' . $e->getMessage());
            if ($e->hasResponse()) {
                Log::error('Fonnte API Response Body: ' . $e->getResponse()->getBody()->getContents());
            }
            return false;
        }
    }
    
    public static function sendMessageWithDelay($recipientNumber, $message, $delaySeconds = 7)
    {
        sleep($delaySeconds); // delay per nomor
        return self::sendMessage($recipientNumber, $message);
    }
}