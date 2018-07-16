<?php

namespace App\Service;

use App\Dto\FitnessCoachFormDto;
use App\Entity\FitnessCoach;
use App\Repository\FitnessCoachRepositoryInterface;

class FitnessCoachService
{
    /**
     * @var FitnessCoachRepositoryInterface
     */
    private $repository;

    /**
     * FitnessClientService constructor.
     *
     * @param FitnessCoachRepositoryInterface $repository
     */
    public function __construct(FitnessCoachRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param FitnessCoachFormDto $dto
     *
     * @return FitnessCoach
     */
    public function updateFitnessCoach(FitnessCoachFormDto $dto)
    {
        $entity = $this->repository->getById($dto->getId());
        $entity->setName($dto->getFirstName(), $dto->getMiddleName(), $dto->getLastName());

        return $entity;
    }
}