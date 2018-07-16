<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Ramsey\Uuid\Uuid;

/**
 * Fitness client entity
 *
 * @ORM\Entity(repositoryClass="App\Repository\FitnessClient\DoctrineFitnessClientRepository")
 * @ORM\Table(indexes={
 *     @ORM\Index(name="first_name_idx", columns={"first_name"}),
 *     @ORM\Index(name="middle_name_idx", columns={"middle_name"}),
 *     @ORM\Index(name="last_name_idx", columns={"last_name"})
 * })
 */
class FitnessClient
{
    const PROPERTY_EMAIL = 'email';
    const PROPERTY_CELL_PHONE = 'cellPhone';
    const PROPERTY_FIRST_NAME = 'firstName';
    const PROPERTY_MIDDLE_NAME = 'middleName';
    const PROPERTY_LAST_NAME = 'lastName';
    const PROPERTY_USER = 'user';
    const PROPERTY_BIRTH_DATE = 'birthDate';
    const PROPERTY_GENDER = 'gender';
    const PROPERTY_PHOTO = 'photo';

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

    /**
     * @var \DateTime
     * @ORM\Column(type="date")
     */
    private $birthDate;

    /**
     * @var int
     * @ORM\Column(type="smallint")
     */
    private $gender;

    /**
     * @var string
     * @ORM\Column(type="string", length=50, unique=true)
     */
    private $email;

    /**
     * @var string
     * @ORM\Column(type="string", length=20, unique=true)
     */
    private $cellPhone;

    /**
     * @var bool
     * @ORM\Column(type="boolean")
     */
    private $photo = false;

    /**
     * @var User
     * @ORM\OneToOne(targetEntity="User", cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="user_id", referencedColumnName="id")
     */
    private $user;

    /**
     * @var FitnessClientSubscription[]
     * @ORM\OneToMany(targetEntity="FitnessClientSubscription", mappedBy="fitnessClient", cascade={"persist", "remove"})
     */
    private $subscriptions;

    public function __construct(
        string $firstName,
        string $middleName,
        string $lastName,
        \DateTime $birthDate,
        int $gender,
        string $email,
        string $cellPhone)
    {
        $this->id = Uuid::uuid4();
        $this->firstName = $firstName;
        $this->middleName = $middleName;
        $this->lastName = $lastName;
        $this->birthDate = $birthDate;
        $this->gender = $gender;
        $this->email = $email;
        $this->cellPhone = $cellPhone;

        $this->user = new User();
        $this->user->setEmail($this->email);
        $this->user->setUsername($this->email);
        $this->user->setPlainPassword($this->generateRandomString(20));
        $this->user->setEnabled(false);

        $this->subscriptions = new ArrayCollection();
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

    public function getBirthDate(): \DateTime
    {
        return $this->birthDate;
    }

    public function setBirthDate(\DateTime $birthDate): void
    {
        $this->birthDate = $birthDate;
    }

    public function getGender(): int
    {
        return $this->gender;
    }

    public function getGenderString(): string
    {
        return GenderEnum::getStringValue($this->getGender());
    }

    public function setGender(int $gender): void
    {
        $this->gender = $gender;
    }

    public function getCellPhone(): string
    {
        return $this->cellPhone;
    }

    public function setCellPhone(string $cellPhone): void
    {
        $this->cellPhone = $cellPhone;
    }

    public function isPhoto(): bool
    {
        return $this->photo;
    }

    public function setPhoto(bool $photo): void
    {
        $this->photo = $photo;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @return string
     */
    public function getMiddleName(): string
    {
        return $this->middleName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    public function subscribeTo(GroupFitnessClass $groupFitnessClass, int $type)
    {
        foreach ($this->subscriptions as $subscription) {
            if ($subscription->getGroupFitnessClass() === $groupFitnessClass) {
                $subscription->enable();
                $subscription->setType($type);
                return;
            }
        }

        $subscription = new FitnessClientSubscription($groupFitnessClass, $this);
        $subscription->enable();
        $subscription->setType($type);

        $this->subscriptions->add($subscription);
    }

    public function unsubscribeFrom(GroupFitnessClass $groupFitnessClass)
    {
        foreach ($this->subscriptions as $subscription) {
            if ($subscription->getGroupFitnessClass() === $groupFitnessClass) {
                $subscription->disable();
                return;
            }
        }
    }
}