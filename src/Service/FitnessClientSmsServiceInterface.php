<?php

namespace App\Service;

interface FitnessClientSmsServiceInterface
{

    public function sendNotificationSms(string $phone, string $message) : bool ;
}