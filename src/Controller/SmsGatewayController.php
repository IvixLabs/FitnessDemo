<?php

namespace App\Controller;

use App\Security\Role;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Fake sms gateway for testing sms sending
 */
class SmsGatewayController extends Controller
{
    /**
     * @Route("/sms")
     */
    public function smsAction(Request $request, LoggerInterface $smsLogger)
    {
        $phone = $request->get('phone');
        $message = $request->get('message');

        $smsLogger->info($phone.' : '.$message);

        $rnd = rand(0, 1);
        if (!$rnd) {
            $smsLogger->info(503);
            return new Response('', 503);
        }

        $smsLogger->info(200);
        return new Response();
    }
}