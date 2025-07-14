<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SimpleWhatsAppService
{
    public function sendMessage($to, $message)
    {
        try {
            // Using a simple WhatsApp API service (you'll need to sign up for one)
            $response = Http::post('https://api.whatsapp.com/send', [
                'phone' => $to,
                'message' => $message,
                'api_key' => env('SIMPLE_WHATSAPP_API_KEY')
            ]);

            if ($response->successful()) {
                Log::info('WhatsApp message sent successfully', [
                    'to' => $to,
                    'response' => $response->json()
                ]);
                return true;
            } else {
                Log::error('WhatsApp message failed', [
                    'to' => $to,
                    'response' => $response->json()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp service error', [
                'error' => $e->getMessage(),
                'to' => $to
            ]);
            return false;
        }
    }
} 