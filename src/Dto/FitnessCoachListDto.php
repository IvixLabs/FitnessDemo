<?php

namespace App\Dto;

use App\Entity\FitnessCoach;

class FitnessCoachListDto
{
    /**
    * @var FitnessCoach    */
    private $entity;

    public function __construct(FitnessCoach $entity)
    {
        $this->entity = $entity;
    }

    public function getId(): string
    {
        return $this->entity->getId();
    }

    public function getName(): string
    {
        return $this->entity->getName();
    }
}