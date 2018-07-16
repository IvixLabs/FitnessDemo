<?php

namespace App\Service\FitnessClient;

use App\Dto\FitnessClientRepeatSmsDto;
use App\PsrProcessor\FitnessClientRepeatSmsSendProcessor;
use App\Service\FitnessClientDelayedSmsServiceInterface;
use Enqueue\Client\Message;
use Enqueue\Client\ProducerInterface;
use Symfony\Component\Serializer\SerializerInterface;

class AmpqFitnessClientDelayedSmsService implements FitnessClientDelayedSmsServiceInterface
{
    /**
     * @var ProducerInterface
     */
    private $producer;

    /**
     * @var SerializerInterface
     */
    private $serializer;

    /**
     * @var int
     */
    private $smsDelay;

    public function __construct(
        ProducerInterface $producer,
        SerializerInterface $serializer,
        int $smsDelay
    ) {
        $this->producer = $producer;
        $this->serializer = $serializer;
        $this->smsDelay = $smsDelay;
    }

    public function sendDelayedNotificationSms(string $phone, string $message)
    {
        $sms = new FitnessClientRepeatSmsDto($phone, $message);
        $serializedSms = $this->serializer->serialize($sms, 'json');

        $msg = new Message();
        $msg->setBody($serializedSms);
        $msg->setDelay($this->smsDelay);
        $this->producer->sendEvent(FitnessClientRepeatSmsSendProcessor::TOPIC, $msg);
    }
}