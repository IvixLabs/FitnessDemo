<?php

namespace App\Repository\FitnessClient;

use App\Entity\User;

class FitnessClientNotFoundException extends \RuntimeException
{
    static function byId($id)
    {
        return new static('FitnessClient not found by id=' . $id);
    }

    static function byUser(User $user)
    {
        return new static('FitnessClient not found by user id=' . $user->getId());
    }
}