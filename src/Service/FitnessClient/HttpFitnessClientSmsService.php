<?php

namespace App\Service\FitnessClient;

use App\Service\FitnessClientSmsServiceInterface;

class HttpFitnessClientSmsService implements FitnessClientSmsServiceInterface
{
    private $smsUrl;

    public function __construct(string $smsUrl)
    {
        $this->smsUrl = $smsUrl;
    }

    public function sendNotificationSms(string $phone, string $message): bool
    {
        $url = $this->smsUrl.'?phone='.urlencode($phone).'&message='.urlencode($message);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_exec($ch);
        $success = true;
        if (!curl_errno($ch)) {
            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $success = $code === 200;
        }
        curl_close($ch);

        return $success;
    }
}