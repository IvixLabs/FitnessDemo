<?php

namespace App\Factory;

use App\Dto\FitnessCoachFormDto;
use App\Entity\FitnessCoach;

/**
 * Factory used for create fitness coach entity
 */
class FitnessCoachFactory
{
    public function create(FitnessCoachFormDto $createDto): FitnessCoach
    {
        return new FitnessCoach(
            $createDto->getFirstName(),
            $createDto->getMiddleName(),
            $createDto->getLastName()
        );
    }
}