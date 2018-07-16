<?php

namespace App\Form;

use App\Entity\FitnessCoach;
use App\Repository\FitnessCoachRepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Form used for add fitness coach entity to other entities
 */
class FitnessCoachSuggestionType extends AbstractType
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
        $builder->addModelTransformer(
            new CallbackTransformer(
                function ($object) {
                    if ($object instanceof FitnessCoach) {
                        return $object->getId();
                    }

                    return null;
                },
                function ($suggestion) {
                    if (is_array($suggestion) && isset($suggestion['id'])) {
                        return $this->repository->getById($suggestion['id']);
                    }

                    return null;
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault(
            'constraints',
            [
                new Callback(
                    function ($payload, ExecutionContextInterface $context) {
                        if (!empty($payload) && !($payload instanceof FitnessCoach)) {
                            $context->buildViolation('FitnessCoach not found')
                                    ->atPath('')
                                    ->addViolation();
                        }
                    }
                ),
            ]);
    }

    public function getParent()
    {
        return TextType::class;
    }
}