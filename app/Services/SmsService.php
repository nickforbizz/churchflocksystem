<?php

namespace App\Services;

use AfricasTalking\SDK\AfricasTalking;
use Illuminate\Support\Facades\Log;

class SmsService
{
    protected $sms;

    public function __construct()
    {
        $username = config('services.at.username');
        $apiKey   = config('services.at.key');

        if (!$username || !$apiKey) {
            throw new \RuntimeException('Africa’s Talking credentials are missing');
        }

        $at = new AfricasTalking($username, $apiKey);
        $this->sms = $at->sms();
    }

    public function sendBulk(array $numbers, string $message)
    {
        Log::info('Sending SMS', [
            'to' => $numbers,
            'env' => app()->environment(),
        ]);

        // IMPORTANT:
        // - Do NOT set `from` in sandbox
        // - Only use `from` in production with an approved sender ID
        $payload = [
            'to'      => $numbers,
            'message' => $message,
        ];

        // Only add sender ID in production
        if (app()->environment('production') && config('services.at.from')) {
            $payload['from'] = config('services.at.from');
        }

        return $this->sms->send($payload);
    }
}
