<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as FOSUser;
use Ramsey\Uuid\Uuid;

/**
 * User entity used for authentication and authorization in app
 *
 * @ORM\Entity(repositoryClass="App\Repository\User\DoctrineUserRepository")
 */
class User extends FOSUser
{

    const PROPERTY_CONFIRMATION_TOKEN = 'confirmationToken';

    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length=36)
     */
    protected $id;


    public function __construct()
    {
        parent::__construct();
        $this->id = Uuid::uuid4();
    }
}
