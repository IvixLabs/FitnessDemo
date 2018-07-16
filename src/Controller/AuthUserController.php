<?php

namespace App\Controller;

use App\Dto\AuthUserDto;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use FOS\RestBundle\Controller\Annotations as Rest;

/**
 * Controller helps find data for current auth user
 * @Route("/api/auth-user")
 */
class AuthUserController extends Controller
{

    /**
     * @Route("/get-current-user.json", name="get_current_user", methods="GET", defaults={"_format"="json"})
     * @Rest\View()
     *
     * @return AuthUserDto
     */
    public function getCurrentUser() {
        /** @var User $user */
        $user = $this->getUser();

        return new AuthUserDto($user);
    }
}