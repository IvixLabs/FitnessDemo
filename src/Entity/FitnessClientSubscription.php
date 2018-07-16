<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * Fitness client subscription to group fitness class notifications
 *
 * @ORM\Entity(repositoryClass="App\Repository\FitnessClientSubscription\DoctrineFitnessClientSubscriptionRepository")
 */
class FitnessClientSubscription
{
    const PROPERTY_FITNESS_CLIENT = 'fitnessClient';
    const PROPERTY_STATUS = 'status';
    const PROPERTY_ID = 'id';

    /**
     * @var string
     * @ORM\Id()
     * @ORM\Column(type="string", length=36)
     */
    private $id;

    /**
     * @var GroupFitnessClass
     * @ORM\ManyToOne(targetEntity="GroupFitnessClass", inversedBy="subscriptions")
     * @ORM\JoinColumn(name="group_fitness_class_id", referencedColumnName="id")
     */
    private $groupFitnessClass;

    /**
     * @var FitnessClient
     * @ORM\ManyToOne(targetEntity="FitnessClient", inversedBy="subscriptions")
     * @ORM\JoinColumn(name="fitness_client_id", referencedColumnName="id")
     */
    private $fitnessClient;

    /**
     * @var int
     * @ORM\Column(type="smallint")
     */
    private $type = SubscriptionTypeEnum::TYPE_EMAIL;

    /**
     * @var int
     * @ORM\Column(type="smallint")
     */
    private $status = SubscriptionStatusEnum::STATUS_DISABLED;

    public function __construct(GroupFitnessClass $groupFitnessClass, FitnessClient $fitnessClient)
    {
        $this->groupFitnessClass = $groupFitnessClass;
        $this->fitnessClient = $fitnessClient;
        $this->id = (string)Uuid::uuid4();
    }

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getType(): int
    {
        return $this->type;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @return GroupFitnessClass
     */
    public function getGroupFitnessClass(): GroupFitnessClass
    {
        return $this->groupFitnessClass;
    }

    public function enable()
    {
        $this->status = SubscriptionStatusEnum::STATUS_ENABLED;
    }

    public function disable()
    {
        $this->status = SubscriptionStatusEnum::STATUS_DISABLED;
    }

    public function setType(int $type): void
    {
        $this->type = $type;
    }

    public function getFitnessClient(): FitnessClient
    {
        return $this->fitnessClient;
    }
}