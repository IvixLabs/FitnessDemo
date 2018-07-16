<?php

namespace App\Dto;

use App\Entity\User;

class AuthUserDto
{
    /**
     * @var User
     */
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getId(): string
    {
        return $this->user->getId();
    }

    public function getUsername(): string
    {
        return $this->user->getUsername();
    }

    public function getEmail(): string
    {
        return $this->user->getEmail();
    }
}