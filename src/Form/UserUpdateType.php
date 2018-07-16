<?php

namespace App\Form;

use App\Dto\UserUpdateDto;
use App\Repository\UserRepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Form used for update user entity
 */
class UserUpdateType extends AbstractType
{
    /**
     * @var UserRepositoryInterface
     */
    private $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', TextType::class)
            ->add(
                'roles',
                CollectionType::class,
                [
                    'allow_add'    => true,
                    'allow_delete' => true,
                    'delete_empty' => true,
                ])
            ->add(
                'username',
                TextType::class,
                [
                    'constraints' => [
                        new NotBlank(),
                        new Length(['max' => 180]),
                    ],
                ])
            ->add(
                'email',
                EmailType::class,
                [
                    'constraints' => [
                        new NotBlank(),
                        new Length(['max' => 180]),
                    ],
                ])
            ->add('password', TextType::class);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [

                'data_class'  => UserUpdateDto::class,
                'constraints' => [
                    new Callback(
                        function ($payload, ExecutionContextInterface $context) {
                            /** @var UserUpdateDto $payload */
                            $user = $this->userRepository->findByUsername($payload->getUsername());
                            if ($user !== null) {
                                if ($user->getId() !== $payload->getId()) {
                                    $context->buildViolation('This username already exists')
                                            ->atPath('username')
                                            ->addViolation();
                                }
                            }
                        }),
                    new Callback(
                        function ($payload, ExecutionContextInterface $context) {
                            /** @var UserUpdateDto $payload */
                            $user = $this->userRepository->findByEmail($payload->getEmail());
                            if ($user !== null) {
                                if ($user->getId() !== $payload->getId()) {
                                    $context->buildViolation('This email already exists')
                                            ->atPath('email')
                                            ->addViolation();
                                }
                            }
                        }),

                ],
            ]);
    }
}
