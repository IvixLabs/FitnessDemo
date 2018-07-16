<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller renders admin template
 * @Route("/admin")
 */
class AdminDashboardController extends Controller
{
    /**
     * @Route("/", name="admin_dashboard")
     */
    public function index()
    {
        return $this->render('admin-dashboard/index.html.twig');
    }
}
