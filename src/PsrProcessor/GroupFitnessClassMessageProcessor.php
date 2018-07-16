<?php

namespace App\PsrProcessor;

use App\Service\GroupFitnessClass\GroupFitnessClassMessage;
use App\Service\GroupFitnessClassSyncMessageService;
use Doctrine\Common\Persistence\ObjectManager;
use Enqueue\Client\TopicSubscriberInterface;
use Interop\Queue\PsrContext;
use Interop\Queue\PsrMessage;
use Interop\Queue\PsrProcessor;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Ampq handler for sending messages to subscribed fitness clients
 */
class GroupFitnessClassMessageProcessor implements PsrProcessor, TopicSubscriberInterface
{
    const TOPIC = 'groupFitnessClassMessageTopic';

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var GroupFitnessClassSyncMessageService
     */
    private $syncMessageService;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    public function __construct(
        SerializerInterface $serializer,
        GroupFitnessClassSyncMessageService $syncMessageService,
        ObjectManager $objectManager
    ) {
        $this->serializer = $serializer;
        $this->syncMessageService = $syncMessageService;
        $this->objectManager = $objectManager;
    }

    public function process(PsrMessage $message, PsrContext $context)
    {
        /** @var GroupFitnessClassMessage $message */
        $message = $this->serializer->deserialize($message->getBody(), GroupFitnessClassMessage::class, 'json');

        $this->syncMessageService->sendSyncMessage($message);

        $this->objectManager->clear();
        return self::ACK;
    }

    public static function getSubscribedTopics()
    {
        return [self::TOPIC];
    }
}