<?php

namespace App\Service\FitnessCoach;

use App\Dto\FitnessCoachSuggestionDto;
use App\Dto\SuggestionResponseDto;
use App\Entity\FitnessCoach;
use App\Service\FitnessCoachSuggestionServiceInterface;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineFitnessCoachSuggestionService implements FitnessCoachSuggestionServiceInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @inheritdoc
     */
    public function __invoke($query = null, $start, $limit)
    {
        $qb = $this->em->createQueryBuilder()
                       ->select('u')
                       ->from(FitnessCoach::class, 'u')
                       ->setMaxResults($limit)
                       ->setFirstResult($start);

        if (!empty($query)) {
            $qb->andWhere(
                $qb->expr()->orX(
                    $qb->expr()->like('u.'.FitnessCoach::PROPERTY_LAST_NAME, ':name'),
                    $qb->expr()->like('u.'.FitnessCoach::PROPERTY_FIRST_NAME, ':name'),
                    $qb->expr()->like('u.'.FitnessCoach::PROPERTY_MIDDLE_NAME, ':name')
                )
            );
            $qb->setParameter(':name', '%'.$query.'%');
        }

        $qb->orderBy('u.'.FitnessCoach::PROPERTY_FIRST_NAME, 'DESC');

        $items = $qb->getQuery()->getResult();

        $dtos = [];

        foreach ($items as $item) {
            $dtos[] = new FitnessCoachSuggestionDto($item);
        }

        $count = $qb->select('count(u) as cnt')
                    ->getQuery()
                    ->getSingleScalarResult();

        return new SuggestionResponseDto($dtos, $count);
    }
}