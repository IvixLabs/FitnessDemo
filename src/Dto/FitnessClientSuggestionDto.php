<?php

namespace App\Dto;

use App\Entity\FitnessClient;

class FitnessClientSuggestionDto
{
    /**
     * @var FitnessClient     */
    private $entity;

    public function __construct(FitnessClient $entity)
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
        return $this->entity->getName();
    }
}