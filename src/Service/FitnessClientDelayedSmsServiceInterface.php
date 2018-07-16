<?php

namespace App\Service;

interface FitnessClientDelayedSmsServiceInterface
{

    public function sendDelayedNotificationSms(string $phone, string $message);
}