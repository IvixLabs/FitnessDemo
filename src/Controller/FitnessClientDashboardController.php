<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller renders fitness client dashboard template
 * @Route("/fitness-client")
 */
class FitnessClientDashboardController extends Controller
{
    /**
     * @Route("/", name="fitness_client_dashboard")
     */
    public function index()
    {
        return $this->render('fitness-client-dashboard/index.html.twig');
    }
}
