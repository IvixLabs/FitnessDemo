<?php

namespace App\Service;

use App\Dto\GroupFitnessClassFormDto;
use App\Entity\GroupFitnessClass;
use App\Repository\GroupFitnessClassRepositoryInterface;

class GroupFitnessClassService
{
    /**
     * @var GroupFitnessClassRepositoryInterface
     */
    private $repository;

    public function __construct(GroupFitnessClassRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param GroupFitnessClassFormDto $dto
     *
     * @return GroupFitnessClass
     */
    public function updateGroupFitnessClass(GroupFitnessClassFormDto $dto)
    {
        $entity = $this->repository->getById($dto->getId());
        $entity->setName($dto->getName());
        $entity->setDescription($dto->getDescription());
        $entity->setFitnessCoach($dto->getFitnessCoach());

        return $entity;
    }
}