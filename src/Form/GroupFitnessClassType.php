<?php

namespace App\Form;

use App\Dto\GroupFitnessClassFormDto;
use App\Entity\GroupFitnessClass;
use App\Repository\GroupFitnessClassRepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Form used for create group fitness class entity
 */
class GroupFitnessClassType extends AbstractType
{
    /**
     * @var GroupFitnessClassRepositoryInterface
     */
    private $repository;

    public function __construct(GroupFitnessClassRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(GroupFitnessClassFormDto::PROPERTY_ID, null, ['mapped' => false]);

        $builder
            ->add(
                GroupFitnessClassFormDto::PROPERTY_NAME,
                TextType::class,
                ['constraints' => [new Length(['max' => 255]), new NotBlank()]]
            );

        $builder
            ->add(
                GroupFitnessClassFormDto::PROPERTY_DESCRIPTION,
                TextType::class,
                ['constraints' => [new Length(['max' => 1000])]]
            );

        $builder->add(GroupFitnessClassFormDto::PROPERTY_FITNESS_COACH, FitnessCoachSuggestionType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [

                'data_class'  => GroupFitnessClassFormDto::class,
                'constraints' => [
                    new Callback(
                        function ($payload, ExecutionContextInterface $context) {
                            /** @var GroupFitnessClass $payload */
                            $entity = $this->repository->findByName($payload->getName());
                            if ($entity !== null) {
                                if ($payload->getId() !== null && $payload->getId() === $entity->getId()) {
                                    return;
                                }
                                $context->buildViolation('This class already exists')
                                        ->atPath(GroupFitnessClassFormDto::PROPERTY_NAME)
                                        ->addViolation();
                            }
                        }),
                ],
            ]);
    }
}
