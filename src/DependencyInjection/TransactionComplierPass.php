<?php

namespace App\DependencyInjection;

use App\Manager\TransactionManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class TransactionComplierPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $transactionManager = $container->findDefinition(TransactionManager::class);

        $transactions = $container->findTaggedServiceIds('app.transaction');

        foreach ($transactions as $transactionId => $transaction) {
            $transactionManager->addMethodCall('addTransaction', [new Reference($transactionId)]);
        }
    }
}