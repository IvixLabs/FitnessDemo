<?php

namespace App\Event;

use App\Entity\FitnessClient;
use Symfony\Component\EventDispatcher\Event;

/**
 * Event raised when fitness client created
 */
class FitnessClientCreatedEvent extends Event
{
    const NAME = 'fitness-client.created';

    protected $fitnessClient;

    public function __construct(FitnessClient $fitnessClient)
    {
        $this->fitnessClient = $fitnessClient;
    }

    /**
     * @return FitnessClient
     */
    public function getFitnessClient(): FitnessClient
    {
        return $this->fitnessClient;
    }
}