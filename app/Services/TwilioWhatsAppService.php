<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TwilioWhatsAppService
{
    protected $accountSid;
    protected $authToken;
    protected $fromNumber;

    public function __construct()
    {
        $this->accountSid = env('TWILIO_ACCOUNT_SID');
        $this->authToken = env('TWILIO_AUTH_TOKEN');
        $this->fromNumber = env('TWILIO_WHATSAPP_FROM');
    }

    public function sendMessage($to, $message)
    {
        try {
            $response = Http::withBasicAuth($this->accountSid, $this->authToken)
                ->post("https://api.twilio.com/2010-04-01/Accounts/{$this->accountSid}/Messages.json", [
                    'From' => "whatsapp:{$this->fromNumber}",
                    'To' => "whatsapp:+91{$to}",
                    'Body' => $message
                ]);

            if ($response->successful()) {
                Log::info('Twilio WhatsApp message sent successfully', [
                    'to' => $to,
                    'response' => $response->json()
                ]);
                return true;
            } else {
                Log::error('Twilio WhatsApp message failed', [
                    'to' => $to,
                    'response' => $response->json()
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('Twilio WhatsApp service error', [
                'error' => $e->getMessage(),
                'to' => $to
            ]);
            return false;
        }
    }
} 