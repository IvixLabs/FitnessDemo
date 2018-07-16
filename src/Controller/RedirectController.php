<?php

namespace App\Controller;

use App\Security\Role;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller redirect auth user to right dashboard version
 */
class RedirectController extends Controller
{
    /**
     * @Route("/", name="root")
     */
    public function index()
    {

        if($this->isGranted(Role::ROLE_FITNESS_CLIENT)) {
            return $this->redirectToRoute('fitness_client_dashboard');
        }

        return $this->redirectToRoute('admin_dashboard');
    }
}