<?php

namespace App\Repository\GroupFitnessClass;

class GroupFitnessClassNotFoundException extends \RuntimeException
{
    static function byId($id)
    {
        return new static('GroupFitnessClass not found by id=' . $id);
    }
}