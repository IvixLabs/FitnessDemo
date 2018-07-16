<?php

namespace App\Manager;

use App\Manager\Transaction\TransactionInterface;
use App\Manager\Transaction\TransactionNotStartedException;

/**
 * Manager collects transactions for any dbs which implements TransactionInterface
 */
class TransactionManager
{
    private $isStarted = false;

    /**
     * @var TransactionInterface[]
     */
    private $transactions = [];

    public function begin()
    {
        $this->isStarted = true;

        foreach ($this->transactions as $transaction) {
            $transaction->begin();
        }
    }

    public function end()
    {
        if (!$this->isStarted) {
            throw new TransactionNotStartedException();
        }

        try {
            foreach ($this->transactions as $transaction) {
                $transaction->end();
            }
        } finally {
            $this->isStarted = false;
        }
    }

    public function clear()
    {
        foreach ($this->transactions as $transaction) {
            $transaction->clear();
        }
    }

    public function addTransaction(TransactionInterface $transaction)
    {
        $this->transactions[] = $transaction;
    }

    public function getTransactions() {
        return $this->transactions;
    }
}