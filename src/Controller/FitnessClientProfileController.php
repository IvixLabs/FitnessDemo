<?php

namespace App\Controller;

use App\Dto\FitnessClientProfileDto;
use App\Entity\User;
use App\Form\FitnessClientPasswordType;
use App\Manager\Transaction\TransactionInterface;
use App\Repository\FitnessClientRepositoryInterface;
use App\Repository\FitnessClientSubscriptionRepositoryInterface;
use App\Repository\GroupFitnessClassRepositoryInterface;
use App\Service\FitnessClientPhotoServiceInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\UserBundle\Model\UserManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Controller handles operations of fitness client dashboard
 * @Route("/api/fitness-client-profile")
 */
class FitnessClientProfileController extends Controller
{
    /**
     * @Route("/data.json", methods="GET", defaults={"_format"="json"})
     * @Rest\View()
     */
    public function dataAction(
        FitnessClientRepositoryInterface $repository,
        TranslatorInterface $translator,
        UrlGeneratorInterface $urlGenerator,
        Request $request): FitnessClientProfileDto
    {
        /** @var User $user */
        $user = $this->getUser();

        $fitnessClient = $repository->getByUser($user);

        return new FitnessClientProfileDto($fitnessClient, $translator, $urlGenerator);
    }

    /**
     * @Route("/photo.png", methods="GET", name="fitness_client_profile_photo", defaults={"_format"="json"})
     */
    public function photoAction(FitnessClientPhotoServiceInterface $service, FitnessClientRepositoryInterface $repository, Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();

        $fitnessClient = $repository->getByUser($user);

        $path = $service->getFitnessClientPhotoPath($fitnessClient->getId());

        return new BinaryFileResponse($path);
    }

    /**
     * @Route("/change-password.json", methods="POST", defaults={"_format"="json"})
     * @Rest\View()
     */
    public function changePasswordAction(
        TransactionInterface $transaction,
        UserManagerInterface $userManager,
        Request $request)
    {
        $form = $this->createForm(FitnessClientPasswordType::class, null, ['csrf_protection' => false]);
        $json = json_decode($request->getContent(), true);
        $form->submit($json);

        if ($form->isSubmitted() && $form->isValid()) {
            /** @var User $user */
            $user = $this->getUser();

            $data = $form->getData();

            $transaction->begin();
            $user->setPlainPassword($data['password']);
            $userManager->updatePassword($user);
            $transaction->end();

            return null;
        }

        return $form;
    }

    /**
     * @Route("/group-fitness-class-list.json", methods="GET", defaults={"_format"="json"})
     * @Rest\View()
     *
     * @return array
     */
    public function groupFitnessClassListAction(
        GroupFitnessClassRepositoryInterface $repository,
        FitnessClientRepositoryInterface $fitnessClientRepository,
        Request $request): array
    {
        $start = $request->get('start');
        $limit = $request->get('limit');
        $filters = $request->get('filters');
        $sorting = $request->get('sorting');

        /** @var User $user */
        $user = $this->getUser();

        $fitnessClient = $fitnessClientRepository->getByUser($user);

        return $repository->getListForFitnessClient(
            $start,
            $limit,
            json_decode($filters, true),
            json_decode($sorting, true),
            $fitnessClient
        );
    }

    /**
     * @Route("/change-subscription.json", methods="POST", defaults={"_format"="json"})
     * @Rest\View()
     */
    public function changeSubscriptionAction(
        GroupFitnessClassRepositoryInterface $repository,
        FitnessClientRepositoryInterface $fitnessClientRepository,
        TransactionInterface $transaction,
        FitnessClientSubscriptionRepositoryInterface $fitnessClientSubscriptionRepository,
        Request $request)
    {

        $json = json_decode($request->getContent(), true);

        /** @var User $user */
        $user = $this->getUser();

        $fitnessClient = $fitnessClientRepository->getByUser($user);

        $classId = $json['classId'];

        $groupFitnessClass = $repository->getById($classId);

        $type = $json['subscriptionType'];
        $transaction->begin();
        $fitnessClient->subscribeTo($groupFitnessClass, $type);
        $transaction->end();
    }

    /**
     * @Route("/unsubscribe.json", methods="POST", defaults={"_format"="json"})
     * @Rest\View()
     */
    public function unsubscribeAction(
        GroupFitnessClassRepositoryInterface $repository,
        FitnessClientRepositoryInterface $fitnessClientRepository,
        TransactionInterface $transaction,
        Request $request)
    {
        /** @var User $user */
        $user = $this->getUser();

        $fitnessClient = $fitnessClientRepository->getByUser($user);

        $json = json_decode($request->getContent(), true);
        $classId = $json['classId'];

        $groupFitnessClass = $repository->getById($classId);

        $transaction->begin();
        $fitnessClient->unsubscribeFrom($groupFitnessClass);
        $transaction->end();
    }
}
