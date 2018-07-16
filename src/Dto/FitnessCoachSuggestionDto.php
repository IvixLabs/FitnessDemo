<?php

namespace App\Dto;

use App\Entity\FitnessCoach;

class FitnessCoachSuggestionDto implements SuggestionDtoInterface
{
    /**
     * @var FitnessCoach     */
    private $entity;

    public function __construct(FitnessCoach $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->entity->getId();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return (string)$this->entity->getName();
    }
}