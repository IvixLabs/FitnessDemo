<?php

namespace App\Factory;

use App\Dto\UserCreateDto;
use App\Entity\User;

/**
 * Factory used for create user entity
 */
class UserFactory
{
    public function create(UserCreateDto $createDto): User
    {
        //Special exception for user. All required fields must be inside constructor
        $user =  new User();
        $user->setUsername($createDto->getUsername());
        $user->setEmail($createDto->getEmail());
        $user->setRoles($createDto->getRoles());
        $user->setPlainPassword($createDto->getPassword());

        return $user;
    }
}