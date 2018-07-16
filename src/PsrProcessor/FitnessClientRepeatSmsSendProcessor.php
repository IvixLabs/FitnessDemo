<?php

namespace App\PsrProcessor;

use App\Dto\FitnessClientRepeatSmsDto;
use App\Service\FitnessClientDelayedSmsServiceInterface;
use App\Service\FitnessClientSmsServiceInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Enqueue\Client\TopicSubscriberInterface;
use Interop\Queue\PsrContext;
use Interop\Queue\PsrMessage;
use Interop\Queue\PsrProcessor;
use Symfony\Component\Serializer\SerializerInterface;

/**
 * Ampq handler for retrying send sms if before sending was failure
 */
class FitnessClientRepeatSmsSendProcessor implements PsrProcessor, TopicSubscriberInterface
{
    const TOPIC = 'fitnessClientRepeatSmsSendProcessorTopic';

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var FitnessClientSmsServiceInterface
     */
    private $fitnessClientSmsService;

    /**
     * @var FitnessClientDelayedSmsServiceInterface
     */
    private $fitnessClientDelayedSmsService;

    /**
     * @var ObjectManager
     */
    private $objectManager;

    public function __construct(
        SerializerInterface $serializer,
        FitnessClientSmsServiceInterface $fitnessClientSmsService,
        ObjectManager $objectManager,
        FitnessClientDelayedSmsServiceInterface $fitnessClientDelayedSmsService
    ) {
        $this->serializer = $serializer;
        $this->fitnessClientSmsService = $fitnessClientSmsService;
        $this->objectManager = $objectManager;
        $this->fitnessClientDelayedSmsService = $fitnessClientDelayedSmsService;
    }

    public function process(PsrMessage $message, PsrContext $context)
    {
        /** @var FitnessClientRepeatSmsDto $sms */
        $sms = $this->serializer->deserialize($message->getBody(), FitnessClientRepeatSmsDto::class, 'json');

        if (!$this->fitnessClientSmsService->sendNotificationSms($sms->getPhone(), $sms->getSms())) {
            $this->fitnessClientDelayedSmsService->sendDelayedNotificationSms($sms->getPhone(), $sms->getSms());
        }

        $this->objectManager->clear();
        return self::ACK;
    }

    public static function getSubscribedTopics()
    {
        return [self::TOPIC];
    }
}