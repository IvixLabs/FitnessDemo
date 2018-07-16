<?php

namespace App\Manager\Transaction;

/**
 * Interface for transaction managing
 */
interface TransactionInterface
{
    public function begin();

    public function end();

    public function clear();
}