<?php

namespace App\Service\GroupFitnessClass;

use App\PsrProcessor\GroupFitnessClassMessageProcessor;
use App\Service\GroupFitnessClassAsyncMessageServiceInterface;
use Enqueue\Client\ProducerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class AmqpGroupFitnessClassAsyncMessageService implements GroupFitnessClassAsyncMessageServiceInterface
{
    /**
     * @var ProducerInterface
     */
    private $producer;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    public function __construct(
        ProducerInterface $producer,
        SerializerInterface $serializer
    ) {
        $this->producer = $producer;
        $this->serializer = $serializer;
    }

    public function sendAsyncMessage(GroupFitnessClassMessage $message)
    {
        $stringMsg = $this->serializer->serialize($message, 'json');
        $this->producer->sendEvent(GroupFitnessClassMessageProcessor::TOPIC, $stringMsg);
    }
}