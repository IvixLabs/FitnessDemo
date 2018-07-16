<?php

namespace App\Repository\GroupFitnessClass;

use App\Dto\GroupFitnessClassFitnessClientListDto;
use App\Dto\GroupFitnessClassListDto;
use App\Entity\FitnessClient;
use App\Entity\FitnessClientSubscription;
use App\Entity\GroupFitnessClass;
use App\Dto\GroupFitnessClassSuggestionDto;
use App\Entity\SubscriptionStatusEnum;
use App\Repository\GroupFitnessClassRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Group fitness class repository doctrine implementation
 */
class DoctrineGroupFitnessClassRepository extends ServiceEntityRepository implements GroupFitnessClassRepositoryInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, GroupFitnessClass::class);
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

        $qb->leftJoin(
            'u.'.GroupFitnessClass::PROPERTY_SUBSCRIPTIONS,
            'ss',
            Join::WITH,
            $qb->expr()->orX(
                $qb->expr()->eq('ss.'.FitnessClientSubscription::PROPERTY_STATUS, ':status')
            )
        );
        $qb->setParameter(':status', SubscriptionStatusEnum::STATUS_ENABLED);
        $qb->groupBy('u.'.GroupFitnessClass::PROPERTY_ID);

        $qb->select(['u as groupFitnessClass', 'count(ss) as subscriptionsCount']);

        $items = $qb->getQuery()->getResult();

        $dtos = [];

        foreach ($items as $item) {
            $dtos[] = new GroupFitnessClassListDto($item['groupFitnessClass'], $item['subscriptionsCount']);
        }

        $qb->resetDQLPart('join');
        $qb->resetDQLPart('groupBy');
        $qb->setParameters([]);
        $count = $qb->select('count(u) as cnt')
                    ->getQuery()
                    ->getSingleScalarResult();

        return ['count' => $count, 'items' => $dtos];
    }

    public function remove(GroupFitnessClass $entity)
    {
        $this->getEntityManager()->remove($entity);
    }

    public function add(GroupFitnessClass $entity)
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
                $qb->expr()->like('u.id', ':query')
            )
        );
        $qb->setParameter(':query', '%'.$query.'%');

        $qb->orderBy('u.id', 'DESC');

        $items = $qb->getQuery()->getResult();

        $dtos = [];

        foreach ($items as $item) {
            $dtos[] = new GroupFitnessClassSuggestionDto($item);
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
            throw GroupFitnessClassNotFoundException::byId($id);
        }

        return $entity;
    }

    /**
     * @inheritdoc
     */
    public function findByName($name)
    {
        return $this->findOneBy(
            [
                GroupFitnessClass::PROPERTY_NAME => $name,
            ]);
    }

    public function getListForFitnessClient($start, $limit, array $filters, array $sorting, FitnessClient $fitnessClient)
    {
        $qb = $this->createQueryBuilder('u')
                   ->orderBy('u.id', 'ASC')
                   ->setMaxResults($limit)
                   ->setFirstResult($start);

        $qb->leftJoin(
            'u.'.GroupFitnessClass::PROPERTY_SUBSCRIPTIONS,
            'ss',
            Join::WITH,
            $qb->expr()->orX(
                $qb->expr()->eq('ss.'.FitnessClientSubscription::PROPERTY_FITNESS_CLIENT, ':fitnessClient'),
                $qb->expr()->isNull('ss.'.FitnessClientSubscription::PROPERTY_FITNESS_CLIENT)
            ));
        $qb->setParameter(':fitnessClient', $fitnessClient);

        if (isset($filters['id'])) {
            $qb->andWhere($qb->expr()->like('u.id', ':id'));
            $qb->setParameter(':id', '%'.$filters['id']['value'].'%');
        }

        if (isset($sorting['field'])) {
            $qb->orderBy('u.'.$sorting['field'], $sorting['order'] == 1 ? 'ASC' : 'DESC');
        }

        $qb->select(['u', 'ss']);

        $items = $qb->getQuery()->getResult();

        $dtos = [];

        /** @var GroupFitnessClass $item */
        foreach ($items as $item) {
            $subscription = null;
            foreach ($item->getSubscriptions() as $subscription) {
                break;
            }

            $dtos[] = new GroupFitnessClassFitnessClientListDto($item, $subscription);
        }

        $count = $qb->select('count(u) as cnt')
                    ->getQuery()
                    ->getSingleScalarResult();

        return ['count' => $count, 'items' => $dtos];
    }
}
