<?php

namespace App\Repository\FitnessCoach;

class FitnessCoachNotFoundException extends \RuntimeException
{
    static function byId($id)
    {
        return new static('FitnessCoach not found by id=' . $id);
    }
}