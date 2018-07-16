<?php

namespace App\Repository;

use App\Dto\UserSuggestionDto;
use App\Entity\User;
use App\Repository\User\UserNotFoundException;


interface UserRepositoryInterface
{
    /**
     * @param $start
     * @param $limit
     *
     * @return array
     */
    public function findList($start, $limit, array $filters, array $sorting);

    /**
     * @param $login
     *
     * @return User
     */
    public function findByUsername($login);

    /**
     * @param $login
     *
     * @return mixed User
     */
    public function findByEmail($login);

    /**
     * @param string $query
     *
     * @return UserSuggestionDto[]
     */
    public function findSuggestions($query);

    public function remove(User $user);

    public function add(User $user);

    /**
     * @param $id
     *
     * @return User
     * @throws UserNotFoundException
     */
    public function getById($id);

    /**
     * @param $token
     *
     * @return User
     */
    public function getByConfirmationToken($token);
}