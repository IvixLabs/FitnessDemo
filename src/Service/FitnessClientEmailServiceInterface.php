<?php

namespace App\Service;

use App\Entity\FitnessClient;

interface FitnessClientEmailServiceInterface
{

    public function sendConfirmationEmail(FitnessClient $fitnessClient);

    public function sendNotificationEmail(string $email, string $message);
}