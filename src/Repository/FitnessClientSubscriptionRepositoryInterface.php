<?php

namespace App\Repository;

use App\Entity\FitnessClientSubscription;

interface FitnessClientSubscriptionRepositoryInterface
{
    public function add(FitnessClientSubscription $entity);
}