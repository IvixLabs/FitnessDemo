<?php

namespace App\Dto;

use App\Entity\User;

class UserSuggestionDto
{
    /**
     * @var User
     */
    private $entity;

    public function __construct(User $entity)
    {
        $this->entity = $entity;
    }

    /**
     * @return string
     */
    public function getId()
    {
        return $this->entity->getId();
    }

    /**
     * @return string
     */
    public function getName()
    {
        return (string)$this->entity->getId();
    }
}