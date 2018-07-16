<?php

namespace App\Dto;

use App\Entity\FitnessCoach;
use App\Entity\GroupFitnessClass;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class GroupFitnessClassFormDto implements NormalizableInterface
{
    const PROPERTY_ID = 'id';
    const PROPERTY_NAME = 'name';
    const PROPERTY_DESCRIPTION = 'description';
    const PROPERTY_FITNESS_COACH = 'fitnessCoach';

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var FitnessCoach
     */
    private $fitnessCoach;

    public function __construct(GroupFitnessClass $entity = null)
    {
        if ($entity !== null) {
            $this->id = $entity->getId();
            $this->name = $entity->getName();
            $this->description = $entity->getDescription();
            $this->fitnessCoach = $entity->getFitnessCoach();
        }
    }

    /**
     * @return string
     */
    public function getId(): ?string
    {
        return $this->id;
    }

    /**
     * @param string $id
     */
    public function setId(string $id = null): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name = null): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description = null): void
    {
        $this->description = $description;
    }

    /**
     * @return FitnessCoach
     */
    public function getFitnessCoach(): ?FitnessCoach
    {
        return $this->fitnessCoach;
    }

    /**
     * @param FitnessCoach $fitnessCoach
     */
    public function setFitnessCoach(FitnessCoach $fitnessCoach = null): void
    {
        $this->fitnessCoach = $fitnessCoach;
    }

    public function normalize(NormalizerInterface $normalizer, $format = null, array $context = [])
    {
        $data = [
            self::PROPERTY_NAME          => $this->getName(),
            self::PROPERTY_DESCRIPTION   => $this->getDescription(),
        ];

        if ($this->getFitnessCoach() !== null) {
            $data[self::PROPERTY_FITNESS_COACH] = [
                'id' => $this->getFitnessCoach()->getId(),
                'name'  => $this->getFitnessCoach()->getName(),
            ];
        }

        if ($this->getId() !== null) {
            $data[self::PROPERTY_ID] = $this->getId();
        }

        return $data;
    }
}