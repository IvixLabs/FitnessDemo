<?php

namespace App\Entity;

use App\Utils\AbstractEnum;

/**
 * Subscription type enum
 */
class SubscriptionTypeEnum extends AbstractEnum
{
    const TYPE_UNDEFINED = 0;
    const TYPE_EMAIL = 1;
    const TYPE_SMS = 2;

    protected static $values = [
        self::TYPE_UNDEFINED   => 'undefined',
        self::TYPE_EMAIL   => 'email',
        self::TYPE_SMS => 'sms',
    ];
}