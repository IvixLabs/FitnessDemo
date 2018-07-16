<?php

namespace App\Service\FitnessClient;

use App\Entity\FitnessClient;
use App\Service\FitnessClientEmailServiceInterface;
use Symfony\Component\Templating\EngineInterface;

class SwiftFitnessClientEmailService implements FitnessClientEmailServiceInterface
{
    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var EngineInterface
     */
    private $renderer;

    public function __construct(\Swift_Mailer $mailer, EngineInterface $engine)
    {
        $this->mailer = $mailer;
        $this->renderer = $engine;
    }

    public function sendConfirmationEmail(FitnessClient $fitnessClient)
    {
        $message = (new \Swift_Message('Подтверждение для email'))
            ->setFrom('noreply@paradise.tld')
            ->setTo($fitnessClient->getEmail())
            ->setBody(
                $this->renderer->render(
                    'emails/fitness-client-confirmation-email.html.twig',
                    ['fitnessClient' => $fitnessClient]
                ),
                'text/html'
            );

        $this->mailer->send($message);
    }

    public function sendNotificationEmail(string $email, string $message)
    {
        $message = (new \Swift_Message('Уведомление о групповом занятии'))
            ->setFrom('noreply@paradise.tld')
            ->setTo($email)
            ->setBody($message,'text/html');

        $this->mailer->send($message);
    }
}