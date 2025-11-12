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

        // Clean and validate access token
        if ($this->accessToken) {
            $this->accessToken = trim($this->accessToken);
        }
    }

    /**
     * Send a WhatsApp message
     *
     * @param string $to Phone number in international format (e.g., 393331234567)
     * @param string $message Message text to send
     * @return array Returns ['success' => bool, 'error' => string|null]
     */
    public function sendMessage(string $to, string $message): array
    {
        // Validate configuration
        if (!$this->phoneNumberId || !$this->accessToken) {
            $error = 'WhatsApp configuration missing: phone_number_id or access_token not set';
            Log::error('WhatsApp configuration missing', [
                'phone_number_id_set' => !empty($this->phoneNumberId),
                'access_token_set' => !empty($this->accessToken),
            ]);
            return ['success' => false, 'error' => $error];
        }

        // Validate access token format
        $tokenValidation = $this->validateAccessToken($this->accessToken);
        if (!$tokenValidation['valid']) {
            $error = "Invalid access token format: {$tokenValidation['error']}";
            Log::error('Invalid access token format', [
                'error' => $tokenValidation['error'],
                'token_length' => strlen($this->accessToken),
                'token_preview' => substr($this->accessToken, 0, 10) . '...',
            ]);
            return ['success' => false, 'error' => $error];
        }

        // Format phone number (remove + and spaces, ensure it starts with country code)
        $phoneNumber = $this->formatPhoneNumber($to);

        if (!$phoneNumber) {
            $error = "Invalid phone number format: {$to}";
            Log::error('Invalid phone number format', ['phone' => $to]);
            return ['success' => false, 'error' => $error];
        }

        $url = "{$this->apiUrl}/{$this->version}/{$this->phoneNumberId}/messages";

        try {
            Log::debug('Sending WhatsApp message', [
                'url' => $url,
                'to' => $phoneNumber,
                'message_length' => strlen($message),
                'phone_number_id' => $this->phoneNumberId,
                'token_length' => strlen($this->accessToken),
            ]);

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
                return ['success' => true, 'error' => null];
            } else {
                $errorResponse = $response->json();
                $errorMessage = $errorResponse['error']['message'] ?? 'Unknown API error';
                $errorCode = $errorResponse['error']['code'] ?? $response->status();

                // Special handling for OAuth token errors
                if ($errorCode == 190) {
                    $error = "WhatsApp API error [190]: Invalid OAuth access token. " .
                        "The token may be expired, invalid, or malformed. " .
                        "Please check your WHATSAPP_ACCESS_TOKEN in .env file. " .
                        "Make sure there are no extra spaces or quotes around the token value.";
                } else {
                    $error = "WhatsApp API error [{$errorCode}]: {$errorMessage}";
                }

                Log::error('WhatsApp API error', [
                    'status' => $response->status(),
                    'error_code' => $errorCode,
                    'error_message' => $errorMessage,
                    'error_type' => $errorResponse['error']['type'] ?? null,
                    'error_subcode' => $errorResponse['error']['error_subcode'] ?? null,
                    'response' => $errorResponse,
                    'to' => $phoneNumber,
                    'phone_number_id' => $this->phoneNumberId,
                    'token_length' => strlen($this->accessToken),
                ]);

                return ['success' => false, 'error' => $error];
            }
        } catch (\Exception $e) {
            $error = "WhatsApp service exception: {$e->getMessage()}";
            Log::error('WhatsApp service exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'to' => $phoneNumber ?? $to,
            ]);
            return ['success' => false, 'error' => $error];
        }
    }

    /**
     * Validate access token format
     *
     * @param string $token
     * @return array ['valid' => bool, 'error' => string|null]
     */
    protected function validateAccessToken(string $token): array
    {
        // Remove whitespace
        $token = trim($token);

        // Check if token is empty
        if (empty($token)) {
            return ['valid' => false, 'error' => 'Token is empty'];
        }

        // Check minimum length (Facebook tokens are typically long)
        if (strlen($token) < 50) {
            return ['valid' => false, 'error' => 'Token appears too short (minimum 50 characters expected)'];
        }

        // Check for common issues: quotes, spaces in the middle
        if (preg_match('/^["\']|["\']$/', $token)) {
            return ['valid' => false, 'error' => 'Token appears to be wrapped in quotes. Remove quotes from .env file.'];
        }

        if (preg_match('/\s/', $token)) {
            return ['valid' => false, 'error' => 'Token contains whitespace. Remove any spaces from the token.'];
        }

        // Basic format check: should be alphanumeric with some special characters
        // Facebook tokens typically contain letters, numbers, and various special chars
        // We'll be lenient here and just check it's not obviously wrong
        if (preg_match('/[<>{}]/', $token)) {
            return ['valid' => false, 'error' => 'Token contains invalid characters (likely copy-paste error)'];
        }

        return ['valid' => true, 'error' => null];
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
