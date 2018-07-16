<?php

namespace App\Repository;

use App\Entity\FitnessClient;
use App\Entity\User;
use App\Repository\FitnessClient\FitnessClientNotFoundException;

interface FitnessClientRepositoryInterface
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
     * @return FitnessClientSuggestionDto[]
     */
    public function findSuggestions($query);

    public function remove(FitnessClient $entity);

    public function add(FitnessClient $entity);

    /**
     * @param $id
     *
     * @return FitnessClient
     * @throws FitnessClientNotFoundException
     */
    public function getById($id);

    /**
     * @param $email
     *
     * @return FitnessClient|null
     */
    public function findByEmail($email);

    /**
     * @param $cellPhone
     *
     * @return FitnessClient|null
     */
    public function findByCellPhone($cellPhone);

    /**
     * @param $firstName
     * @param $middleName
     * @param $lastName
     *
     * @return FitnessClient|null
     */
    public function findByName($firstName, $middleName, $lastName);

    /**
     * @param User $user
     *
     * @return FitnessClient
     */
    public function getByUser(User $user);
}