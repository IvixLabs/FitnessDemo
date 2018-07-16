<?php

namespace App\Repository\User;

use App\Dto\UserListDto;
use App\Dto\UserSuggestionDto;
use App\Entity\User;
use App\Repository\UserRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class DoctrineUserRepository extends ServiceEntityRepository implements UserRepositoryInterface
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function findList($start, $limit, array $filters, array $sorting)
    {
        $qb = $this->createQueryBuilder('u')
                   ->orderBy('u.id', 'ASC')
                   ->setMaxResults($limit)
                   ->setFirstResult($start);

        if (isset($filters['username'])) {
            $qb->andWhere($qb->expr()->like('u.username', ':username'));
            $qb->setParameter(':username', '%'.$filters['username']['value'].'%');
        }

        if (isset($filters['email'])) {
            $qb->andWhere($qb->expr()->like('u.email', ':email'));
            $qb->setParameter(':email', '%'.$filters['email']['value'].'%');
        }

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
            $dtos[] = new UserListDto($item);
        }

        $count = $this->createQueryBuilder('u')
                      ->select('count(u) as cnt')
                      ->getQuery()
                      ->getSingleScalarResult();

        return ['count' => $count, 'items' => $dtos];
    }

    public function findByUsername($login)
    {
        return $this->findOneBy(['username' => $login]);
    }

    public function findByEmail($login)
    {
        return $this->findOneBy(['email' => $login]);
    }

    public function remove(User $user)
    {
        $this->getEntityManager()->remove($user);
    }

    public function add(User $user)
    {
        $this->getEntityManager()->persist($user);
    }

    /**
     * @inheritdoc
     */
    public function getById($id)
    {
        $entity = $this->find($id);

        if ($entity === null) {
            throw UserNotFoundException::byId($id);
        }

        return $entity;
    }

    /**
     * @inheritdoc
     */
    public function findSuggestions($query)
    {
        $qb = $this->createQueryBuilder('u')
                   ->setMaxResults(5)
                   ->setFirstResult(0);

        $qb->andWhere(
            $qb->expr()->orX(
                $qb->expr()->like('u.username', ':query')
            )
        );
        $qb->setParameter(':query', '%'.$query.'%');

        $qb->orderBy('u.id', 'DESC');

        $items = $qb->getQuery()->getResult();

        $dtos = [];

        foreach ($items as $item) {
            $dtos[] = new UserSuggestionDto($item);
        }

        return $dtos;
    }

    /**
     * @inheritdoc
     */
    public function getByConfirmationToken($token)
    {
        $entity = $this->findOneBy([User::PROPERTY_CONFIRMATION_TOKEN => $token]);

        if ($entity === null) {
            throw UserNotFoundException::byConfirmationToken($token);
        }

        return $entity;
    }
}
