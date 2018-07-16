<?php

namespace App\Dto;

use App\Entity\GroupFitnessClass;

class GroupFitnessClassListDto
{
    /**
     * @var GroupFitnessClass
     */
    private $entity;

    /**
     * @var int
     */
    private $subscriptionsCount;

    public function __construct(GroupFitnessClass $entity, int $subscriptionsCount)
    {
        $this->entity = $entity;
        $this->subscriptionsCount = $subscriptionsCount;
    }

    public function getId(): string
    {
        return $this->entity->getId();
    }

    public function getName(): string
    {
        return $this->entity->getName();
    }

    public function getFitnessCoachName(): ?string
    {
        $coach = $this->entity->getFitnessCoach();
        if ($coach !== null) {
            return $coach->getName();
        }

        return null;
    }

    public function getSubscriptionsCount(): int
    {
        return $this->subscriptionsCount;
    }
}