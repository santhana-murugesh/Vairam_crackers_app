<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SMSService
{
    public function sendSMS($to, $message)
    {
        try {
            // Using a simple SMS API (like MSG91, TextLocal, etc.)
            $response = Http::post('https://api.textlocal.in/send/', [
                'apikey' => env('SMS_API_KEY'),
                'numbers' => $to,
                'message' => $message,
                'sender' => env('SMS_SENDER_ID')
            ]);

            if ($response->successful()) {
                Log::info('SMS sent successfully', [
                    'to' => $to,
                    'response' => $response->json()
                ]);
                return true;
            } else {
                Log::error('SMS failed', [
                    'to' => $to,
                    'response' => $response->json()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('SMS service error', [
                'error' => $e->getMessage(),
                'to' => $to
            ]);
            return false;
        }
    }
} 