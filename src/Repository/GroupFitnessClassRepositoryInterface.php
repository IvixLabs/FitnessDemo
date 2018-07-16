<?php

namespace App\Repository;

use App\Dto\GroupFitnessClassSuggestionDto;
use App\Entity\FitnessClient;
use App\Entity\GroupFitnessClass;
use App\Repository\GroupFitnessClass\GroupFitnessClassNotFoundException;

interface GroupFitnessClassRepositoryInterface
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
     * @return GroupFitnessClassSuggestionDto[]
     */
    public function findSuggestions($query);

    public function remove(GroupFitnessClass $entity);

    public function add(GroupFitnessClass $entity);

    /**
     * @param $id
     *
     * @return GroupFitnessClass
     * @throws GroupFitnessClassNotFoundException
     */
    public function getById($id);

    /**
     * @param $name
     *
     * @return GroupFitnessClass|null
     */
    public function findByName($name);

    public function getListForFitnessClient($start, $limit, array $filters, array $sorting, FitnessClient $fitnessClient);
}