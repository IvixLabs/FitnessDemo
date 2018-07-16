<?php

namespace App\Dto;

use App\Entity\FitnessClient;

class FitnessClientListDto
{
    /**
     * @var FitnessClient
     */
    private $entity;

    public function __construct(FitnessClient $entity)
    {
        $this->entity = $entity;
    }

    public function getName(): string
    {
        return $this->entity->getName();
    }

    public function getId(): string
    {
        return $this->entity->getId();
    }

    public function isEnabled(): bool
    {
        return $this->entity->getUser()->isEnabled();
    }
}