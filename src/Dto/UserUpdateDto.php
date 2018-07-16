<?php

namespace App\Dto;

use App\Entity\User;
use FOS\UserBundle\Model\UserInterface;

class UserUpdateDto
{
    /**
     * @var User
     */
    private $user;

    /**
     * UserCreateDto constructor.
     *
     * @param User $user
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function getId(): string
    {
        return $this->user->getId();
    }

    /**
     * @return string
     */
    public function getUsername(): ?string
    {
        return $this->user->getUsername();
    }

    /**
     * @param string $username
     */
    public function setUsername(?string $username): void
    {
        $this->user->setUsername($username);
    }

    /**
     * @return string
     */
    public function getPassword(): ?string
    {
        return null;
    }

    /**
     * @param string $password
     */
    public function setPassword(?string $password): void
    {
        if (!empty($password)) {
            $this->user->setPlainPassword($password);
        }
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->user->getEmail();
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->user->setEmail($email);
    }

    public function getRoles()
    {
        return $this->user->getRoles();
    }

    public function setRoles(array $roles)
    {
        $this->user->setRoles($roles);
    }
}