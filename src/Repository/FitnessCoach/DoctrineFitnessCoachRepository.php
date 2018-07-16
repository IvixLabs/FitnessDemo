<?php

namespace App\Repository\FitnessCoach;

use App\Dto\FitnessCoachListDto;
use App\Entity\FitnessCoach;
use App\Dto\FitnessCoachSuggestionDto;
use App\Repository\FitnessCoachRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Fitness coach repository doctrine implementation
 */
class DoctrineFitnessCoachRepository extends ServiceEntityRepository implements FitnessCoachRepositoryInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, FitnessCoach::class);
    }

    public function findList($start, $limit, array $filters, array $sorting)
    {
        $qb = $this->createQueryBuilder('u')
                   ->orderBy('u.id', 'ASC')
                   ->setMaxResults($limit)
                   ->setFirstResult($start);

        if (isset($filters['id'])) {
            $qb->andWhere($qb->expr()->like('u.id', ':id'));
            $qb->setParameter(':id', '%'.$filters['id']['value'].'%');
        }

        if (isset($sorting['field'])) {
            $qb->orderBy('u.'.$sorting['field'], $sorting['order'] == 1 ? 'ASC' : 'DESC');
        }

        $items = $qb->getQuery()->getResult();

        $dtos = [];

        foreach ($items as $item) {
            $dtos[] = new FitnessCoachListDto($item);
        }

        $count = $this->createQueryBuilder('u')
                      ->select('count(u) as cnt')
                      ->getQuery()
                      ->getSingleScalarResult();

        return ['count' => $count, 'items' => $dtos];
    }

    public function remove(FitnessCoach $entity)
    {
        $this->getEntityManager()->remove($entity);
    }

    public function add(FitnessCoach $entity)
    {
        $this->getEntityManager()->persist($entity);
    }

    public function findSuggestions($query)
    {
        $qb = $this->createQueryBuilder('u')
                   ->setMaxResults(5)
                   ->setFirstResult(0);

        $qb->andWhere(
            $qb->expr()->orX(
                $qb->expr()->like('u.'.FitnessCoach::PROPERTY_LAST_NAME, ':query'),
                $qb->expr()->like('u.'.FitnessCoach::PROPERTY_FIRST_NAME, ':query'),
                $qb->expr()->like('u.'.FitnessCoach::PROPERTY_MIDDLE_NAME, ':query')
            )
        );
        $qb->setParameter(':query', $query.'%');

        $qb->orderBy('u.'.FitnessCoach::PROPERTY_ID, 'DESC');

        $items = $qb->getQuery()->getResult();

        $dtos = [];

        foreach ($items as $item) {
            $dtos[] = new FitnessCoachSuggestionDto($item);
        }

        return $dtos;
    }

    /**
     * @inheritdoc
     */
    public function getById($id)
    {
        $entity = $this->find($id);

        if ($entity === null) {
            throw FitnessCoachNotFoundException::byId($id);
        }

        return $entity;
    }

    /**
     * @inheritdoc
     */
    public function findByName($firstName, $middleName, $lastName)
    {
        return $this->findOneBy(
            [
                FitnessCoach::PROPERTY_FIRST_NAME  => $firstName,
                FitnessCoach::PROPERTY_MIDDLE_NAME => $middleName,
                FitnessCoach::PROPERTY_LAST_NAME   => $lastName,
            ]);
    }
}
