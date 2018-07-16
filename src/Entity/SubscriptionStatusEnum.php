<?php

namespace App\Entity;

use App\Utils\AbstractEnum;

/**
 * Subscription status enum
 */
class SubscriptionStatusEnum extends AbstractEnum
{
    const STATUS_DISABLED = 1;
    const STATUS_ENABLED = 2;

    protected static $values = [
        self::STATUS_DISABLED   => 'disabled',
        self::STATUS_ENABLED => 'enabled',
    ];
}