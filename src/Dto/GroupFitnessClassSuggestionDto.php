<?php

namespace App\Dto;

use App\Entity\GroupFitnessClass;

class GroupFitnessClassSuggestionDto
{
    /**
     * @var GroupFitnessClass     */
    private $entity;

    public function __construct(GroupFitnessClass $entity)
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
        return (string)$this->entity->getId();
    }
}