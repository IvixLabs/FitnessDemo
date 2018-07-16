<?php

namespace App\Dto;

use App\Entity\FitnessCoach;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class FitnessCoachFormDto implements NormalizableInterface
{
    const PROPERTY_ID = 'id';
    const PROPERTY_FIRST_NAME = 'firstName';
    const PROPERTY_MIDDLE_NAME = 'middleName';
    const PROPERTY_LAST_NAME = 'lastName';

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $firstName;

    /**
     * @var string
     */
    private $middleName;

    /**
     * @var string
     */
    private $lastName;

    public function __construct(FitnessCoach $entity = null)
    {
        if ($entity !== null) {
            $this->id = $entity->getId();
            $this->firstName = $entity->getFirstName();
            $this->middleName = $entity->getMiddleName();
            $this->lastName = $entity->getLastName();
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

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName = null): void
    {
        $this->firstName = $firstName;
    }

    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    public function setMiddleName(string $middleName = null): void
    {
        $this->middleName = $middleName;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName = null): void
    {
        $this->lastName = $lastName;
    }

    public function normalize(NormalizerInterface $normalizer, $format = null, array $context = [])
    {
        $data = [
            self::PROPERTY_FIRST_NAME  => $this->getFirstName(),
            self::PROPERTY_MIDDLE_NAME => $this->getMiddleName(),
            self::PROPERTY_LAST_NAME   => $this->getLastName(),
        ];

        if ($this->getId() !== null) {
            $data[self::PROPERTY_ID] = $this->getId();
        }

        return $data;
    }
}