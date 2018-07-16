<?php

namespace App\Controller;

use App\Manager\Transaction\TransactionInterface;
use App\Repository\UserRepositoryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller confirm email for new clients
 */
class FitnessClientConfirmationController extends Controller
{
    /**
     * @Route("/fitness-client-confirmation", name="fitness_client_confirmation")
     */
    public function index(
        Request $request,
        UserRepositoryInterface $repository,
        TransactionInterface $transaction
    ) {
        $token = $request->get('token');
        if (empty($token)) {
            throw $this->createNotFoundException();
        }

        $user = $repository->getByConfirmationToken($token);

        $error = false;
        if ($request->isMethod(Request::METHOD_POST)) {
            $password = $request->get('clientPassword');
            $repeatPassword = $request->get('clientRepeatPassword');

            if (!empty($password) && $password === $repeatPassword) {
                $transaction->begin();
                $user->setPlainPassword($password);
                $user->setConfirmationToken(null);
                $user->setEnabled(true);
                $transaction->end();
                return $this->redirectToRoute('root');
            } else {
                $error = true;
            }
        }

        return $this->render(
            'fitness-client-confirmation/index.html.twig',
            [
                'error' => $error,

            ]
        );
    }
}
