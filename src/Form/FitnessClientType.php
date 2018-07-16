<?php

namespace App\Form;

use App\Dto\FitnessClientFormDto;
use App\Entity\FitnessClient;
use App\Entity\GenderEnum;
use App\Repository\FitnessClientRepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Form used for create fitness client entity
 */
class FitnessClientType extends AbstractType
{
    /**
     * @var FitnessClientRepositoryInterface
     */
    private $repository;

    public function __construct(FitnessClientRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(FitnessClientFormDto::PROPERTY_ID, TextType::class, ['mapped' => false]);
        $builder
            ->add(FitnessClientFormDto::PROPERTY_PHOTO, TextType::class, ['mapped' => false]);

        $builder
            ->add(
                FitnessClientFormDto::PROPERTY_FIRST_NAME,
                TextType::class,
                ['constraints' => [new Length(['max' => 255]), new NotBlank()]]
            );

        $builder
            ->add(
                FitnessClientFormDto::PROPERTY_MIDDLE_NAME,
                TextType::class,
                ['constraints' => [new Length(['max' => 255]), new NotBlank()]]
            );

        $builder
            ->add(
                FitnessClientFormDto::PROPERTY_LAST_NAME,
                TextType::class,
                ['constraints' => [new Length(['max' => 255]), new NotBlank()]]
            );

        $builder
            ->add(
                FitnessClientFormDto::PROPERTY_BIRTH_DATE,
                DateType::class,
                [
                    'format'      => 'dd-MM-yyyy',
                    'widget'      => 'single_text',
                    'constraints' => [new NotBlank()],
                ]
            );

        $builder
            ->add(
                FitnessClientFormDto::PROPERTY_GENDER,
                ChoiceType::class,
                [
                    'choices'     => array_keys(GenderEnum::getAll()),
                    'constraints' => [new NotBlank()],
                ]
            );

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                /** @var FitnessClientFormDto $data */
                $data = $event->getData();
                $form = $event->getForm();

                if ($data->getId() === null) {
                    $form->add(
                        FitnessClientFormDto::PROPERTY_EMAIL,
                        EmailType::class,
                        [
                            'constraints' => [
                                new Length(['max' => 50]),
                                new NotBlank(),
                                new Callback(
                                    function ($payload, ExecutionContextInterface $context) {
                                        $user = $this->repository->findByEmail($payload);
                                        if ($user !== null) {
                                            $context->buildViolation('This email already exists')
                                                    ->atPath(FitnessClientFormDto::PROPERTY_EMAIL)
                                                    ->addViolation();
                                        }
                                    }),
                            ],
                        ]
                    );
                }
            });

        $builder
            ->add(
                FitnessClientFormDto::PROPERTY_CELL_PHONE,
                TextType::class,
                [
                    'constraints' => [
                        new Length(['max' => 20]),
                        new NotBlank(),
                    ],
                ]
            );

        $builder->get(FitnessClientFormDto::PROPERTY_CELL_PHONE)
                ->addModelTransformer(
                    new CallbackTransformer(
                        function ($phone) {
                            return $phone;
                        },
                        function ($phone) {
                            return preg_replace('/[^+0123456789]+/', '', $phone);
                        }
                    ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [

                'data_class'  => FitnessClientFormDto::class,
                'constraints' => [
                    new Callback(
                        function ($payload, ExecutionContextInterface $context) {
                            /** @var FitnessClient $payload */
                            $client = $this->repository->findByCellPhone($payload->getCellPhone());
                            if ($client !== null) {
                                if ($payload->getId() !== null && $payload->getId() === $client->getId()) {
                                    return;
                                }
                                $context->buildViolation('This cell phone already exists')
                                        ->atPath(FitnessClientFormDto::PROPERTY_CELL_PHONE)
                                        ->addViolation();
                            }
                        }),
                    new Callback(
                        function ($payload, ExecutionContextInterface $context) {
                            /** @var FitnessClient $payload */
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
                                        ->atPath(FitnessClientFormDto::PROPERTY_FIRST_NAME)
                                        ->addViolation();
                            }
                        }),

                ],
            ]);
    }
}