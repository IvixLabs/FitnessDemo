<?php

namespace App\Dto;

use App\Entity\FitnessClientSubscription;
use App\Entity\GroupFitnessClass;
use App\Entity\SubscriptionStatusEnum;
use App\Entity\SubscriptionTypeEnum;

class GroupFitnessClassFitnessClientListDto
{
    /**
     * @var GroupFitnessClass
     */
    private $groupFitnessClass;

    /**
     * @var FitnessClientSubscription
     */
    private $fitnessClientSubscription;

    public function __construct(GroupFitnessClass $groupFitnessClass, FitnessClientSubscription $fitnessClientSubscription = null)
    {
        $this->groupFitnessClass = $groupFitnessClass;
        $this->fitnessClientSubscription = $fitnessClientSubscription;
    }

    public function getId(): string
    {
        return $this->groupFitnessClass->getId();
    }

    public function getName(): string
    {
        return $this->groupFitnessClass->getName();
    }

    public function isSubscribed(): bool
    {
        if ($this->fitnessClientSubscription === null) {
            return false;
        }

        return $this->fitnessClientSubscription->getStatus() === SubscriptionStatusEnum::STATUS_ENABLED;
    }

    public function getFitnessCoachName(): ?string
    {
        $coach = $this->groupFitnessClass->getFitnessCoach();

        if ($coach === null) {
            return null;
        }

        return $this->groupFitnessClass->getFitnessCoach()->getName();
    }

    public function getSubscriptionType()
    {
        if ($this->fitnessClientSubscription === null) {
            return SubscriptionTypeEnum::TYPE_EMAIL;
        }

        return $this->fitnessClientSubscription->getType();
    }
}