<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $apiUrl;
    protected $phoneNumberId;
    protected $accessToken;
    protected $version;

    public function __construct()
    {
        $this->apiUrl = config('services.whatsapp.api_url', 'https://graph.facebook.com');
        $this->phoneNumberId = config('services.whatsapp.phone_number_id');
        $this->accessToken = config('services.whatsapp.access_token');
        $this->version = config('services.whatsapp.version', 'v18.0');
    }

    /**
     * Send a WhatsApp message
     *
     * @param string $to Phone number in international format (e.g., 393331234567)
     * @param string $message Message text to send
     * @return bool
     */
    public function sendMessage(string $to, string $message): bool
    {
        if (!$this->phoneNumberId || !$this->accessToken) {
            Log::error('WhatsApp configuration missing');
            return false;
        }

        // Format phone number (remove + and spaces, ensure it starts with country code)
        $phoneNumber = $this->formatPhoneNumber($to);

        if (!$phoneNumber) {
            Log::error('Invalid phone number format', ['phone' => $to]);
            return false;
        }

        $url = "{$this->apiUrl}/{$this->version}/{$this->phoneNumberId}/messages";

        try {
            $response = Http::withToken($this->accessToken)
                ->post($url, [
                    'messaging_product' => 'whatsapp',
                    'to' => $phoneNumber,
                    'type' => 'text',
                    'text' => [
                        'body' => $message,
                    ],
                ]);

            if ($response->successful()) {
                Log::info('WhatsApp message sent successfully', [
                    'to' => $phoneNumber,
                    'response' => $response->json(),
                ]);
                return true;
            } else {
                Log::error('WhatsApp API error', [
                    'status' => $response->status(),
                    'response' => $response->json(),
                    'to' => $phoneNumber,
                ]);
                return false;
            }
        } catch (\Exception $e) {
            Log::error('WhatsApp service exception', [
                'message' => $e->getMessage(),
                'to' => $phoneNumber,
            ]);
            return false;
        }
    }

    /**
     * Format phone number for WhatsApp API
     * Removes +, spaces, and ensures it's in international format
     *
     * @param string $phoneNumber
     * @return string|null
     */
    protected function formatPhoneNumber(string $phoneNumber): ?string
    {
        // Remove all non-numeric characters except leading +
        $cleaned = preg_replace('/[^\d+]/', '', $phoneNumber);

        // Remove leading + if present
        $cleaned = ltrim($cleaned, '+');

        // Ensure it's not empty and has at least 7 digits
        if (empty($cleaned) || strlen($cleaned) < 7) {
            return null;
        }

        return $cleaned;
    }
}
