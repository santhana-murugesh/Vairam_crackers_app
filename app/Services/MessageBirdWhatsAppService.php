<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class MessageBirdWhatsAppService
{
    protected $apiKey;

    public function __construct()
    {
        $this->apiKey = env('MESSAGEBIRD_API_KEY');
    }

    public function sendMessage($to, $message)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'AccessKey ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://conversations.messagebird.com/v1/send', [
                'to' => '+91' . $to,
                'type' => 'text',
                'content' => [
                    'text' => $message
                ],
                'channelId' => env('MESSAGEBIRD_CHANNEL_ID')
            ]);

            if ($response->successful()) {
                Log::info('MessageBird WhatsApp message sent successfully', [
                    'to' => $to,
                    'response' => $response->json()
                ]);
                return true;
            } else {
                Log::error('MessageBird WhatsApp message failed', [
                    'to' => $to,
                    'response' => $response->json()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('MessageBird WhatsApp service error', [
                'error' => $e->getMessage(),
                'to' => $to
            ]);
            return false;
        }
    }
} 