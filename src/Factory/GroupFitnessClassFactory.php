<?php

namespace App\Factory;

use App\Dto\GroupFitnessClassFormDto;
use App\Entity\GroupFitnessClass;

/**
 * Factory used for create group fitness class entity
 */
class GroupFitnessClassFactory
{
    public function create(GroupFitnessClassFormDto $createDto): GroupFitnessClass    {
        return new GroupFitnessClass($createDto->getName(), $createDto->getFitnessCoach());
    }
}