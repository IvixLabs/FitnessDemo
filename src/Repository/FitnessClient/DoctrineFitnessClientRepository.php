<?php

namespace App\Repository\FitnessClient;

use App\Dto\FitnessClientListDto;
use App\Entity\FitnessClient;
use App\Dto\FitnessClientSuggestionDto;
use App\Entity\User;
use App\Repository\FitnessClientRepositoryInterface;
use App\Service\FitnessClientPhotoServiceInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Fitness client repository doctrine implementation
 */
class DoctrineFitnessClientRepository extends ServiceEntityRepository implements FitnessClientRepositoryInterface
{
    /**
     * @var FitnessClientPhotoServiceInterface
     */
    private $photoService;

    public function __construct(RegistryInterface $registry, FitnessClientPhotoServiceInterface $photoService)
    {
        parent::__construct($registry, FitnessClient::class);
        $this->photoService = $photoService;
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
            $dtos[] = new FitnessClientListDto($item);
        }

        $count = $this->createQueryBuilder('u')
                      ->select('count(u) as cnt')
                      ->getQuery()
                      ->getSingleScalarResult();

        return ['count' => $count, 'items' => $dtos];
    }

    public function remove(FitnessClient $entity)
    {
        $this->photoService->removeFitnessClientPhotoFile($entity->getId());
        $this->getEntityManager()->remove($entity);
    }

    public function add(FitnessClient $entity)
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
            $dtos[] = new FitnessClientSuggestionDto($item);
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
            throw FitnessClientNotFoundException::byId($id);
        }

        return $entity;
    }

    /**
     * @inheritdoc
     */
    public function findByEmail($email)
    {
        return $this->findOneBy([FitnessClient::PROPERTY_EMAIL => $email]);
    }

    /**
     * @inheritdoc
     */
    public function findByCellPhone($cellPhone)
    {
        return $this->findOneBy([FitnessClient::PROPERTY_CELL_PHONE => $cellPhone]);
    }

    /**
     * @inheritdoc
     */
    public function findByName($firstName, $middleName, $lastName)
    {
        return $this->findOneBy(
            [
                FitnessClient::PROPERTY_FIRST_NAME  => $firstName,
                FitnessClient::PROPERTY_MIDDLE_NAME => $middleName,
                FitnessClient::PROPERTY_LAST_NAME   => $lastName,
            ]);
    }

    /**
     * @inheritdoc
     */
    public function getByUser(User $user)
    {
        $entity = $this->findOneBy([FitnessClient::PROPERTY_USER => $user]);

        if ($entity === null) {
            throw FitnessClientNotFoundException::byUser($user);
        }

        return $entity;
    }
}
