<?php

namespace App\Factory;

use App\Dto\FitnessClientFormDto;
use App\Entity\FitnessClient;
use App\Event\FitnessClientCreatedEvent;
use App\Security\Role;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Factory used for create fitness client entity
 */
class FitnessClientFactory
{
    /**
     * @var EventDispatcherInterface
     */
    private $eventDispatcher;

    /**
     * @var TokenGeneratorInterface
     */
    private $tokenGenerator;

    public function __construct(
        EventDispatcherInterface $eventDispatcher,
        TokenGeneratorInterface $tokenGenerator
    ) {
        $this->eventDispatcher = $eventDispatcher;
        $this->tokenGenerator = $tokenGenerator;
    }

    public function create(FitnessClientFormDto $createDto): FitnessClient
    {
        $entity = new FitnessClient(
            $createDto->getFirstName(),
            $createDto->getMiddleName(),
            $createDto->getLastName(),
            $createDto->getBirthDate(),
            $createDto->getGender(),
            $createDto->getEmail(),
            $createDto->getCellPhone()
        );
        $entity->getUser()->setConfirmationToken($this->tokenGenerator->generateToken());
        $entity->getUser()->addRole(Role::ROLE_FITNESS_CLIENT);

        $this->eventDispatcher->dispatch(FitnessClientCreatedEvent::NAME, new FitnessClientCreatedEvent($entity));

        return $entity;
    }
}