<?php

namespace App\Controller;

use App\Dto\FitnessCoachFormDto;
use App\Dto\FitnessCoachUpdateDto;
use App\Dto\SuggestionResponseDto;
use App\Form\FitnessCoachType;
use App\Form\FitnessCoachUpdateType;
use App\Repository\FitnessCoachRepositoryInterface;
use App\Factory\FitnessCoachFactory;
use App\Manager\TransactionManager;
use App\Service\FitnessCoachSuggestionServiceInterface;
use App\Service\FitnessCoachService;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Controller handles crud operations of fitness coach for admin
 *
 * @Route("/api/fitness-coach")
 */
class FitnessCoachController extends Controller
{
    /**
     * @Route("/list.json", methods="GET", defaults={"_format"="json"})
     * @Rest\View()
     *
     * @return array
     */
    public function listAction(FitnessCoachRepositoryInterface $repository, Request $request): array
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
     */
    public function suggestionsAction(FitnessCoachSuggestionServiceInterface $service, Request $request): SuggestionResponseDto
    {
        $query = $request->get('query');
        $start = $request->get('start', 0);
        $limit = $request->get('limit', 5);
        return $service($query, $start, $limit);
    }

    /**
     * @Route("/create.json", methods="POST", defaults={"_format"="json"})
     * @Rest\View()
     */
    public function createAction(
        FitnessCoachRepositoryInterface $repository,
        FitnessCoachFactory $factory,
        TransactionManager $transactionManager,
        Request $request)
    {
        $createDto = new FitnessCoachFormDto();
        $form = $this->createForm(FitnessCoachType::class, $createDto, ['csrf_protection' => false]);
        $json = json_decode($request->getContent(), true);
        $form->submit($json);

        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $factory->create($createDto);
            $transactionManager->begin();
            $repository->add($entity);
            $transactionManager->end();

            return new FitnessCoachFormDto($entity);
        }

        return $form;
    }

    /**
     * @Route("/get.json", methods="GET", defaults={"_format"="json"})
     * @Rest\View()
     */
    public function getAction(FitnessCoachRepositoryInterface $repository, Request $request): FitnessCoachFormDto
    {
        $id = $request->get('id');
        $entity = $repository->getById($id);
        return new FitnessCoachFormDto($entity);
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
        FitnessCoachService $service,
        TransactionManager $transactionManager,
        Request $request)
    {
        $id = $request->get('id');
        $entityDto = new FitnessCoachFormDto();
        $entityDto->setId($id);

        $form = $this->createForm(FitnessCoachType::class, $entityDto, ['csrf_protection' => false]);
        $json = json_decode($request->getContent(), true);

        $transactionManager->begin();
        $form->submit($json);

        try {
            if ($form->isSubmitted() && $form->isValid()) {
                $service->updateFitnessCoach($entityDto);
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
        FitnessCoachRepositoryInterface $repository,
        TransactionManager $transactionManager,
        Request $request)
    {
        $id = $request->get('id');
        $entity = $repository->find($id);

        $transactionManager->begin();
        $repository->remove($entity);
        $transactionManager->end();
    }
}
