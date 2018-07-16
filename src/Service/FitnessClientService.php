<?php

namespace App\Service;

use App\Dto\FitnessClientFormDto;
use App\Entity\FitnessClient;
use App\Repository\FitnessClientRepositoryInterface;

class FitnessClientService
{
    /**
     * @var FitnessClientRepositoryInterface
     */
    private $repository;

    /**
     * @var FitnessClientPhotoServiceInterface
     */
    private $photoService;

    public function __construct(
        FitnessClientRepositoryInterface $repository,
        FitnessClientPhotoServiceInterface $photoService
    ) {
        $this->repository = $repository;
        $this->photoService = $photoService;
    }

    /**
     * @param FitnessClientFormDto $dto
     *
     * @return FitnessClient
     */
    public function updateFitnessClient(FitnessClientFormDto $dto)
    {
        $entity = $this->repository->getById($dto->getId());
        $entity->setName($dto->getFirstName(), $dto->getMiddleName(), $dto->getLastName());
        $entity->setCellPhone($dto->getCellPhone());
        $entity->setGender($dto->getGender());
        $entity->setBirthDate($dto->getBirthDate());

        return $entity;
    }

    /**
     * @param string $id
     * @param string $srcPath
     *
     * @return FitnessClient
     */
    public function setFitnessClientPhoto(string $id, string $srcPath)
    {
        $entity = $this->repository->getById($id);
        if (!$this->photoService->copyFitnessClientPhoto($id, $srcPath)) {
            throw new \RuntimeException();
        }
        $entity->setPhoto(true);

        return $entity;
    }

    /**
     * @param string $id
     *
     * @return FitnessClient
     */
    public function removeFitnessClientPhoto(string $id)
    {
        $entity = $this->repository->getById($id);
        if (!$this->photoService->removeFitnessClientPhotoFile($id)) {
            throw new \RuntimeException();
        }
        $entity->setPhoto(false);

        return $entity;
    }
}