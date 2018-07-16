<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * Group fitness class entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\GroupFitnessClass\DoctrineGroupFitnessClassRepository")
 */
class GroupFitnessClass
{

    const PROPERTY_ID = 'id';
    const PROPERTY_NAME = 'name';
    const PROPERTY_SUBSCRIPTIONS = 'subscriptions';

    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=36)
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=100, unique=true)
     */
    private $name;

    /**
     * @var string
     * @ORM\Column(type="string", length=1000, nullable=true)
     */
    private $description;

    /**
     * @var FitnessCoach
     * @ORM\ManyToOne(targetEntity="FitnessCoach")
     * @ORM\JoinColumn(name="fitness_coach_id", referencedColumnName="id", nullable=true)
     */
    private $fitnessCoach;

    /**
     * @var FitnessClientSubscription[]
     * @ORM\OneToMany(targetEntity="FitnessClientSubscription", mappedBy="groupFitnessClass", cascade={"remove"})
     */
    private $subscriptions;

    public function __construct(
        string $name,
        FitnessCoach $fitnessCoach = null
    ) {
        $this->id = Uuid::uuid4();
        $this->name = $name;
        $this->fitnessCoach = $fitnessCoach;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description = null): void
    {
        $this->description = $description;
    }

    public function getFitnessCoach(): ?FitnessCoach
    {
        return $this->fitnessCoach;
    }

    public function setFitnessCoach(FitnessCoach $fitnessCoach = null): void
    {
        $this->fitnessCoach = $fitnessCoach;
    }

    /**
     * @return FitnessClientSubscription[]
     */
    public function getSubscriptions()
    {
        return $this->subscriptions;
    }
}