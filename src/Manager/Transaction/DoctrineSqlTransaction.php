<?php

namespace App\Manager\Transaction;

use Doctrine\ORM\EntityManagerInterface;

/**
 * EntityManager transaction managing
 */
class DoctrineSqlTransaction implements TransactionInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function begin()
    {
    }

    public function end()
    {
        $this->entityManager->flush();
    }

    public function clear()
    {
        $this->entityManager->clear();
    }
}