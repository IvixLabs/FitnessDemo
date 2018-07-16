<?php

namespace App\Controller;

use App\Dto\FitnessClientFormDto;
use App\Entity\GenderEnum;
use App\Form\FitnessClientType;
use App\Repository\FitnessClientRepositoryInterface;
use App\Factory\FitnessClientFactory;
use App\Manager\TransactionManager;
use App\Service\FitnessClientPhotoServiceInterface;
use App\Service\FitnessClientService;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Controller handles crud operations of fitness client for admin
 * @Route("/api/fitness-client")
 */
class FitnessClientController extends Controller
{
    /**
     * @Route("/list.json", methods="GET", defaults={"_format"="json"})
     * @Rest\View()
     *
     * @return array
     */
    public function listAction(FitnessClientRepositoryInterface $repository, Request $request): array
    {
        $start = $request->get('start');
        $limit = $request->get('limit');
        $filters = $request->get('filters');
        $sorting = $request->get('sorting');
        return $repository->findList($start, $limit, json_decode($filters, true), json_decode($sorting, true));
    }

    /**
     * @Route("/photo-upload.json", methods="POST", defaults={"_format"="json"})
     * @Rest\View()
     */
    public function uploadPhotoAction(
        FitnessClientService $service,
        TransactionManager $transactionManager,
        Request $request)
    {
        $id = $request->get('id');

        $photo = $request->files->get('photo');
        if ($photo instanceof UploadedFile) {
            if ($photo->getError() > 0) {
                throw new \RuntimeException($photo->getErrorMessage());
            }

            $transactionManager->begin();
            try {
                $entity = $service->setFitnessClientPhoto($id, $photo->getRealPath());
                return new FitnessClientFormDto($entity);
            } finally {
                $transactionManager->end();
            }
        }

        throw new \RuntimeException();
    }

    /**
     * @Route("/remove-photo.json", methods="POST", defaults={"_format"="json"})
     * @Rest\View()
     */
    public function removePhotoAction(
        FitnessClientService $service,
        TransactionManager $transactionManager,
        Request $request)
    {
        $id = $request->get('id');

        $transactionManager->begin();
        try {
            $entity = $service->removeFitnessClientPhoto($id);
            return new FitnessClientFormDto($entity);
        } finally {
            $transactionManager->end();
        }
    }

    /**
     * @Route("/photo.png", methods="GET", defaults={"_format"="json"})
     */
    public function photoAction(FitnessClientPhotoServiceInterface $service, Request $request)
    {
        $id = $request->get('id');

        $path = $service->getFitnessClientPhotoPath($id);

        return new BinaryFileResponse($path);
    }

    /**
     * @Route("/suggestions.json", methods="GET", defaults={"_format"="json"})
     * @Rest\View()
     *
     * @return array
     */
    public function suggestionsAction(FitnessClientRepositoryInterface $repository, Request $request): array
    {
        $query = $request->get('query');
        return $repository->findSuggestions($query);
    }

    /**
     * @Route("/create.json", methods="POST", defaults={"_format"="json"})
     * @Rest\View()
     */
    public function createAction(
        FitnessClientRepositoryInterface $repository,
        FitnessClientFactory $factory,
        TransactionManager $transactionManager,
        Request $request)
    {
        $createDto = new FitnessClientFormDto();
        $form = $this->createForm(FitnessClientType::class, $createDto, ['csrf_protection' => false]);
        $json = json_decode($request->getContent(), true);
        $form->submit($json);

        if ($form->isSubmitted() && $form->isValid()) {
            $entity = $factory->create($createDto);
            $transactionManager->begin();
            $repository->add($entity);
            $transactionManager->end();

            return new FitnessClientFormDto($entity);
        }

        return $form;
    }

    /**
     * @Route("/get.json", methods="GET", defaults={"_format"="json"})
     * @Rest\View()
     */
    public function getAction(
        FitnessClientRepositoryInterface $repository,
        Request $request): FitnessClientFormDto
    {
        $id = $request->get('id');
        $entity = $repository->getById($id);
        return new FitnessClientFormDto($entity);
    }

    /**
     * @Route("/form.json", methods="GET", defaults={"_format"="json"})
     * @Rest\View()
     */
    public function formAction(TranslatorInterface $trans)
    {
        $genders = [];
        foreach (GenderEnum::getAll() as $value => $name) {
            $genders[] = ['name' => $trans->trans($name, [], 'gender', 'ru'), 'value' => $value];
        }

        return [
            'genders' => $genders,
        ];
    }

    /**
     * @Route("/update.json", methods="POST", defaults={"_format"="json"})
     * @Rest\View()
     */
    public function updateAction(
        FitnessClientService $service,
        TransactionManager $transactionManager,
        Request $request)
    {
        $id = $request->get('id');
        $entityDto = new FitnessClientFormDto();
        $entityDto->setId($id);

        $form = $this->createForm(FitnessClientType::class, $entityDto, ['csrf_protection' => false]);
        $json = json_decode($request->getContent(), true);

        $transactionManager->begin();
        $form->submit($json);

        try {
            if ($form->isSubmitted() && $form->isValid()) {
                $entity = $service->updateFitnessClient($entityDto);
                return new FitnessClientFormDto($entity);
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
        FitnessClientRepositoryInterface $repository,
        TransactionManager $transactionManager,
        Request $request)
    {
        $id = $request->get('id');
        $entity = $repository->getById($id);

        $transactionManager->begin();
        $repository->remove($entity);
        $transactionManager->end();
    }

    /**
     * @Route("/change-status.json", methods="POST", defaults={"_format"="json"})
     * @Rest\View()
     */
    public function changeStatusAction(
        FitnessClientRepositoryInterface $repository,
        TransactionManager $transactionManager,
        Request $request)
    {
        $id = $request->get('id');

        $transactionManager->begin();
        $entity = $repository->getById($id);
        $json = json_decode($request->getContent(), true);
        $status = (bool)$json['status'];
        $entity->getUser()->setEnabled($status);
        $transactionManager->end();
    }
}
