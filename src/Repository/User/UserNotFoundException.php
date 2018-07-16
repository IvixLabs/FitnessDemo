<?php

namespace App\Repository\User;

class UserNotFoundException extends \RuntimeException
{
    static function byId($id)
    {
        return new static('User not found by id='.$id);
    }

    static function byConfirmationToken($token)
    {
        return new static('User not found by confirmation token='.$token);
    }
}