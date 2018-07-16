<?php

namespace App\Repository\FitnessClientSubscription;

use App\Entity\FitnessClientSubscription;
use App\Repository\FitnessClientSubscriptionRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Fitness client subscription repository doctrine implementation
 */
class DoctrineFitnessClientSubscriptionRepository extends ServiceEntityRepository implements FitnessClientSubscriptionRepositoryInterface
{

    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FitnessClientSubscription::class);
    }

    public function add(FitnessClientSubscription $entity)
    {
        $this->getEntityManager()->persist($entity);
    }
}
