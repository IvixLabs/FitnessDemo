<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * Fitness coach entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\FitnessCoach\DoctrineFitnessCoachRepository")
 * @ORM\Table(indexes={
 *     @ORM\Index(name="first_name_idx", columns={"first_name"}),
 *     @ORM\Index(name="middle_name_idx", columns={"middle_name"}),
 *     @ORM\Index(name="last_name_idx", columns={"last_name"})
 * })
 */
class FitnessCoach
{
    const PROPERTY_ID = 'id';
    const PROPERTY_FIRST_NAME = 'firstName';
    const PROPERTY_MIDDLE_NAME = 'middleName';
    const PROPERTY_LAST_NAME = 'lastName';

    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=36)
     */
    private $id;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $firstName;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $middleName;

    /**
     * @var string
     * @ORM\Column(type="string", length=255)
     */
    private $lastName;

    public function __construct(
        string $firstName,
        string $middleName,
        string $lastName
    ) {
        $this->id = Uuid::uuid4();
        $this->firstName = $firstName;
        $this->middleName = $middleName;
        $this->lastName = $lastName;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function setName($firstName, $middleName, $lastName)
    {
        $this->firstName = $firstName;
        $this->middleName = $middleName;
        $this->lastName = $lastName;
    }

    public function getName(): string
    {
        return $this->lastName.' '.$this->firstName.' '.$this->middleName;
    }

    public function getFirstName(): string
    {
        return $this->firstName;
    }

    public function getMiddleName(): string
    {
        return $this->middleName;
    }

    public function getLastName(): string
    {
        return $this->lastName;
    }
}