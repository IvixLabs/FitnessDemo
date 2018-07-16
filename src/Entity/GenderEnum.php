<?php

namespace App\Entity;

use App\Utils\AbstractEnum;

/**
 * Gender enum
 */
class GenderEnum extends AbstractEnum
{
    const GENDER_MAN = 1;
    const GENDER_WOMAN = 2;

    protected static $values = [
        self::GENDER_MAN   => 'man',
        self::GENDER_WOMAN => 'woman',
    ];
}