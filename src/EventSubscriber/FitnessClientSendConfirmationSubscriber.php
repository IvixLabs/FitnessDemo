<?php

namespace App\EventSubscriber;

use App\Event\FitnessClientCreatedEvent;
use App\Service\FitnessClientEmailServiceInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Subscribes send confirmation email for created clients
 */
class FitnessClientSendConfirmationSubscriber implements EventSubscriberInterface
{
    /**
     * @var FitnessClientEmailServiceInterface
     */
    private $fitnessClientEmailService;

    public function __construct(FitnessClientEmailServiceInterface $fitnessClientEmailService)
    {
        $this->fitnessClientEmailService = $fitnessClientEmailService;
    }

    public static function getSubscribedEvents()
    {
        return [
            FitnessClientCreatedEvent::NAME => 'onFitnessClientCreated',
        ];
    }

    public function onFitnessClientCreated(FitnessClientCreatedEvent $event)
    {
        $this->fitnessClientEmailService->sendConfirmationEmail($event->getFitnessClient());
    }
}