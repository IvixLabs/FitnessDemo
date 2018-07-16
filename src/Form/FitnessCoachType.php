<?php

namespace App\Form;

use App\Dto\FitnessCoachFormDto;
use App\Entity\FitnessCoach;
use App\Repository\FitnessCoachRepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Form used for create fitness coach entity
 */
class FitnessCoachType extends AbstractType
{
    /**
     * @var FitnessCoachRepositoryInterface
     */
    private $repository;

    public function __construct(FitnessCoachRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(FitnessCoachFormDto::PROPERTY_ID, TextType::class, ['mapped' => false]);

        $builder
            ->add(
                FitnessCoachFormDto::PROPERTY_FIRST_NAME,
                TextType::class,
                ['constraints' => [new Length(['max' => 255]), new NotBlank()]]
            );

        $builder
            ->add(
                FitnessCoachFormDto::PROPERTY_MIDDLE_NAME,
                TextType::class,
                ['constraints' => [new Length(['max' => 255]), new NotBlank()]]
            );

        $builder
            ->add(
                FitnessCoachFormDto::PROPERTY_LAST_NAME,
                TextType::class,
                ['constraints' => [new Length(['max' => 255]), new NotBlank()]]
            );
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'data_class'  => FitnessCoachFormDto::class,
                'constraints' => [
                    new Callback(
                        function ($payload, ExecutionContextInterface $context) {
                            /** @var FitnessCoach $payload */
                            $client = $this->repository->findByName(
                                $payload->getFirstName(),
                                $payload->getMiddleName(),
                                $payload->getLastName()
                            );
                            if ($client !== null) {
                                if ($payload->getId() !== null && $payload->getId() === $client->getId()) {
                                    return;
                                }
                                $context->buildViolation('This client already exists')
                                        ->atPath(FitnessCoachFormDto::PROPERTY_FIRST_NAME)
                                        ->addViolation();
                            }
                        }),
                ],
            ]);
    }
}
