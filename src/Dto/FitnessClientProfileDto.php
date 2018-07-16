<?php

namespace App\Dto;

use App\Entity\FitnessClient;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\TranslatorInterface;

class FitnessClientProfileDto
{
    /**
     * @var FitnessClient
     */
    private $entity;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * FitnessClientProfileDto constructor.
     *
     * @param FitnessClient $entity
     */
    public function __construct(
        FitnessClient $entity,
        TranslatorInterface $translator,
        UrlGeneratorInterface $urlGenerator
    ) {
        $this->entity = $entity;
        $this->translator = $translator;
        $this->urlGenerator = $urlGenerator;
    }

    public function getEmail(): string
    {
        return $this->entity->getEmail();
    }

    public function getName(): string
    {
        return $this->entity->getName();
    }

    public function getCellPhone(): string
    {
        return $this->entity->getCellPhone();
    }

    public function getBirthDate(): string
    {
        return $this->entity->getBirthDate()->format('d-m-Y');
    }

    public function getGender(): string
    {
        return $this->translator->trans($this->entity->getGenderString(), [], 'gender', 'ru');
    }

    public function getPhotoUrl(): ?string
    {
        if ($this->entity->isPhoto()) {
            return $this->urlGenerator->generate('fitness_client_profile_photo');
        }

        return null;
    }
}