<?php

namespace App\Service;

use App\Entity\SubscriptionTypeEnum;
use App\Repository\GroupFitnessClass\GroupFitnessClassNotFoundException;
use App\Repository\GroupFitnessClassRepositoryInterface;
use App\Service\GroupFitnessClass\GroupFitnessClassMessage;
use Symfony\Component\Serializer\SerializerInterface;

class GroupFitnessClassSyncMessageService
{
    /**
     * @var GroupFitnessClassRepositoryInterface
     */
    private $repository;

    /**
     * @var FitnessClientEmailServiceInterface
     */
    private $fitnessClientEmailService;

    /**
     * @var FitnessClientSmsServiceInterface
     */
    private $fitnessClientSmsService;

    /**
     * @var FitnessClientDelayedSmsServiceInterface
     */
    private $fitnessClientDelayedSmsService;

    /**
     * @var MessageRendererServiceInterface
     */
    private $messageRendererService;

    public function __construct(
        GroupFitnessClassRepositoryInterface $repository,
        FitnessClientEmailServiceInterface $fitnessClientEmailService,
        FitnessClientSmsServiceInterface $fitnessClientSmsService,
        SerializerInterface $serializer,
        FitnessClientDelayedSmsServiceInterface $fitnessClientDelayedSmsService,
        MessageRendererServiceInterface $messageRendererService
    ) {
        $this->repository = $repository;
        $this->fitnessClientEmailService = $fitnessClientEmailService;
        $this->fitnessClientSmsService = $fitnessClientSmsService;
        $this->fitnessClientDelayedSmsService = $fitnessClientDelayedSmsService;
        $this->messageRendererService = $messageRendererService;
    }

    public function sendSyncMessage(GroupFitnessClassMessage $groupFitnessClassMessage)
    {
        try {
            $groupFitnessClass = $this->repository->getById($groupFitnessClassMessage->getGroupFitnessClassId());
        } catch (GroupFitnessClassNotFoundException $exception) {
            return;
        }

        $subscriptions = $groupFitnessClass->getSubscriptions();

        foreach ($subscriptions as $subscription) {
            $fitnessClient = $subscription->getFitnessClient();

            if (!$fitnessClient->getUser()->isEnabled()) {
                continue;
            }

            $coach = $groupFitnessClass->getFitnessCoach();
            $templateParams = [
                'name' => $fitnessClient->getName(),
                'birthDate' => $fitnessClient->getBirthDate(),
                'email' => $fitnessClient->getEmail(),
                'phone' => $fitnessClient->getCellPhone(),
                'groupClass' => $groupFitnessClass->getName(),
            ];

            if($coach !== null) {
                $templateParams['coach'] = $coach->getName();
            }

            if($subscription->getType() === SubscriptionTypeEnum::TYPE_EMAIL) {
                $email = $groupFitnessClassMessage->getEmail();
                if (!empty($email)) {
                    $renderedEmail = $this->messageRendererService->renderMessage($email, $templateParams);
                    $this->fitnessClientEmailService->sendNotificationEmail($fitnessClient->getEmail(), $renderedEmail);
                }
            }

            if($subscription->getType() === SubscriptionTypeEnum::TYPE_SMS) {
                $sms = $groupFitnessClassMessage->getSms();
                if (!empty($sms)) {
                    $renderedSms = $this->messageRendererService->renderMessage($sms, $templateParams);
                    $success = $this->fitnessClientSmsService->sendNotificationSms($fitnessClient->getCellPhone(), $renderedSms);
                    if (!$success) {
                        $this->fitnessClientDelayedSmsService->sendDelayedNotificationSms($fitnessClient->getCellPhone(), $renderedSms);
                    }
                }
            }
        }
    }
}