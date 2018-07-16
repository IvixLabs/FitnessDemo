<?php

namespace App\Dto;

use App\Entity\FitnessClient;
use Symfony\Component\Serializer\Normalizer\NormalizableInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class FitnessClientFormDto implements NormalizableInterface
{
    const PROPERTY_ID = 'id';
    const PROPERTY_FIRST_NAME = 'firstName';
    const PROPERTY_MIDDLE_NAME = 'middleName';
    const PROPERTY_LAST_NAME = 'lastName';
    const PROPERTY_BIRTH_DATE = 'birthDate';
    const PROPERTY_GENDER = 'gender';
    const PROPERTY_EMAIL = 'email';
    const PROPERTY_CELL_PHONE = 'cellPhone';
    const PROPERTY_PHOTO = 'photo';

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

    /**
     * @var \DateTime
     */
    private $birthDate;

    /**
     * @var int
     */
    private $gender;

    /**
     * @var string
     */
    private $email;

    /**
     * @var string
     */
    private $cellPhone;

    /**
     * @var bool
     */
    private $photo;

    public function __construct(FitnessClient $entity = null)
    {
        if ($entity !== null) {
            $this->id = $entity->getId();
            $this->firstName = $entity->getFirstName();
            $this->middleName = $entity->getMiddleName();
            $this->lastName = $entity->getLastName();
            $this->cellPhone = $entity->getCellPhone();
            $this->birthDate = $entity->getBirthDate();
            $this->gender = $entity->getGender();
            $this->photo = $entity->isPhoto();
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
    public function setId(string $id): void
    {
        $this->id = $id;
    }

    /**
     * @return string
     */
    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName = null): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getMiddleName(): ?string
    {
        return $this->middleName;
    }

    /**
     * @param string $middleName
     */
    public function setMiddleName(string $middleName = null): void
    {
        $this->middleName = $middleName;
    }

    /**
     * @return string
     */
    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName = null): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return \DateTime
     */
    public function getBirthDate(): ?\DateTime
    {
        return $this->birthDate;
    }

    /**
     * @param \DateTime $birthDate
     */
    public function setBirthDate(\DateTime $birthDate = null): void
    {
        $this->birthDate = $birthDate;
    }

    /**
     * @return int
     */
    public function getGender(): ?int
    {
        return $this->gender;
    }

    /**
     * @param int $gender
     */
    public function setGender(int $gender = null): void
    {
        $this->gender = $gender;
    }

    /**
     * @return string
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email = null): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getCellPhone(): ?string
    {
        return $this->cellPhone;
    }

    /**
     * @param string $cellPhone
     */
    public function setCellPhone(string $cellPhone = null): void
    {
        $this->cellPhone = $cellPhone;
    }

    public function normalize(NormalizerInterface $normalizer, $format = null, array $context = [])
    {
        $data = [
            self::PROPERTY_CELL_PHONE  => $this->getCellPhone(),
            self::PROPERTY_FIRST_NAME  => $this->getFirstName(),
            self::PROPERTY_MIDDLE_NAME => $this->getMiddleName(),
            self::PROPERTY_LAST_NAME   => $this->getLastName(),
            self::PROPERTY_PHOTO       => $this->photo,
        ];

        if ($this->getId() !== null) {
            $data[self::PROPERTY_ID] = $this->getId();
        }

        if ($this->getEmail() !== null) {
            $data[self::PROPERTY_EMAIL] = $this->getEmail();
        }

        if ($this->getBirthDate() !== null) {
            $data[self::PROPERTY_BIRTH_DATE] = $this->getBirthDate()->format('d-m-Y');
        }

        if ($this->getGender() !== null) {
            $data[self::PROPERTY_GENDER] = ['value' => $this->getGender()];
        }

        return $data;
    }
}