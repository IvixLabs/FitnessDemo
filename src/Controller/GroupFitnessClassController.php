<?php

namespace App\Controller;

use App\Dto\GroupFitnessClassFormDto;
use App\Form\GroupFitnessClassMessageType;
use App\Form\GroupFitnessClassType;
use App\Repository\GroupFitnessClassRepositoryInterface;
use App\Factory\GroupFitnessClassFactory;
use App\Manager\TransactionManager;
use App\Service\GroupFitnessClass\GroupFitnessClassMessage;
use App\Service\GroupFitnessClassAsyncMessageServiceInterface;
use App\Service\GroupFitnessClassService;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller handles crud operations of group fitness class for admin
 *
 * @Route("/api/group-fitness-class")
 */
class GroupFitnessClassController extends Controller
{
    /**
     * @Route("/list.json", methods="GET", defaults={"_format"="json"})
     * @Rest\View()
     *
     * @return array
     */
    public function listAction(GroupFitnessClassRepositoryInterface $repository, Request $request): array
    {
        $start = $request->get('start');
        $limit = $request->get('limit');
        $filters = $request->get('filters');
        $sorting = $request->get('sorting');
        return $repository->findList($start, $limit, json_decode($filters, true), json_decode($sorting, true));
    }

    /**
     * @Route("/suggestions.json", methods="GET", defaults={"_format"="json"})
     * @Rest\View()
     *
     * @return array
     */
    public function suggestionsAction(GroupFitnessClassRepositoryInterface $repository, Request $request): array
    {
        $query = $request->get('query');
        return $repository->findSuggestions($query);
    }

    /**
     * @Route("/create.json", methods="POST", defaults={"_format"="json"})
     * @Rest\View()
     */
    public function createAction(
        GroupFitnessClassRepositoryInterface $repository,
        GroupFitnessClassFactory $factory,
        TransactionManager $transactionManager,
        Request $request)
    {
        $createDto = new GroupFitnessClassFormDto();
        $form = $this->createForm(GroupFitnessClassType::class, $createDto, ['csrf_protection' => false]);
        $json = json_decode($request->getContent(), true);
        $form->submit($json);

        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $factory->create($createDto);
            $transactionManager->begin();
            $repository->add($entity);
            $transactionManager->end();

            return new GroupFitnessClassFormDto($entity);
        }

        return $form;
    }

    /**
     * @Route("/get.json", methods="GET", defaults={"_format"="json"})
     * @Rest\View()
     */
    public function getAction(
        GroupFitnessClassRepositoryInterface $repository,
        Request $request): GroupFitnessClassFormDto
    {
        $id = $request->get('id');
        $entity = $repository->getById($id);
        return new GroupFitnessClassFormDto($entity);
    }

    /**
     * @Route("/form.json", methods="GET", defaults={"_format"="json"})
     * @Rest\View()
     */
    public function formAction(Request $request)
    {
        return [];
    }

    /**
     * @Route("/update.json", methods="POST", defaults={"_format"="json"})
     * @Rest\View()
     */
    public function updateAction(
        GroupFitnessClassService $service,
        TransactionManager $transactionManager,
        Request $request)
    {
        $id = $request->get('id');
        $entityDto = new GroupFitnessClassFormDto();
        $entityDto->setId($id);

        $form = $this->createForm(GroupFitnessClassType::class, $entityDto, ['csrf_protection' => false]);
        $json = json_decode($request->getContent(), true);

        $transactionManager->begin();
        $form->submit($json);

        try {
            if ($form->isSubmitted() && $form->isValid()) {
                $service->updateGroupFitnessClass($entityDto);
                return $entityDto;
            }
        } finally {
            $transactionManager->end();
        }

        return $form;
    }

    /**
     * @Route("/delete.json", methods="DELETE", defaults={"_format"="json"})
     * @Rest\View()
     */
    public function deleteAction(
        GroupFitnessClassRepositoryInterface $repository,
        TransactionManager $transactionManager,
        Request $request)
    {
        $id = $request->get('id');
        $entity = $repository->find($id);

        $transactionManager->begin();
        $repository->remove($entity);
        $transactionManager->end();
    }

    /**
     * @Route("/send-message.json", methods="POST", defaults={"_format"="json"})
     * @Rest\View()
     */
    public function sendMessageAction(
        GroupFitnessClassRepositoryInterface $repository,
        GroupFitnessClassAsyncMessageServiceInterface $messageService,
        Request $request)
    {
        $id = $request->get('id');
        $repository->getById($id);
        $json = json_decode($request->getContent(), true);

        $form = $this->createForm(GroupFitnessClassMessageType::class, null, ['csrf_protection' => false]);

        $form->submit($json);
        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $message = new GroupFitnessClassMessage($id, $data['email'], $data['sms']);
            $messageService->sendAsyncMessage($message);
            return $data;
        }

        return $form;
    }
}
