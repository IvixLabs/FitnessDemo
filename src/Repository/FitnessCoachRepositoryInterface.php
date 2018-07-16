<?php

namespace App\Repository;

use App\Dto\FitnessCoachSuggestionDto;
use App\Entity\FitnessCoach;
use App\Repository\FitnessCoach\FitnessCoachNotFoundException;

interface FitnessCoachRepositoryInterface
{
    /**
     * @param $start
     * @param $limit
     *
     * @return array
     */
    public function findList($start, $limit, array $filters, array $sorting);

    /**
     * @param string $query
     *
     * @return FitnessCoachSuggestionDto[]
     */
    public function findSuggestions($query);

    public function remove(FitnessCoach $entity);

    public function add(FitnessCoach $entity);

    /**
     * @param $id
     *
     * @return FitnessCoach
     * @throws FitnessCoachNotFoundException
     */
    public function getById($id);

    /**
     * @param $firstName
     * @param $middleName
     * @param $lastName
     *
     * @return FitnessCoach|null
     */
    public function findByName($firstName, $middleName, $lastName);
}