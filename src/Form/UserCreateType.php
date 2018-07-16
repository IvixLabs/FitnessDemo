<?php

namespace App\Form;

use App\Dto\UserCreateDto;
use App\Repository\UserRepositoryInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Form used for create user entity
 */
class UserCreateType extends AbstractType
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
            ->add('id', null, ['mapped' => false])
            ->add('roles', CollectionType::class,
                  [
                      'allow_add' => true,
                      'allow_delete' => true,
                      'delete_empty' => true
                  ])
            ->add(
                'username',
                TextType::class,
                [
                    'constraints' => [
                        new NotBlank(),
                        new Length(['max' => 180]),
                        new Callback(
                            function ($payload, ExecutionContextInterface $context) {
                                $user = $this->userRepository->findByUsername($payload);
                                if ($user !== null) {
                                    $context->buildViolation('This username already exists')
                                            ->atPath('username')
                                            ->addViolation();
                                }
                            }),

                    ],
                ])
            ->add(
                'email',
                EmailType::class,
                [
                    'constraints' => [
                        new NotBlank(),
                        new Length(['max' => 180]),
                        new Callback(
                            function ($payload, ExecutionContextInterface $context) {
                                $user = $this->userRepository->findByEmail($payload);
                                if ($user !== null) {
                                    $context->buildViolation('This email already exists')
                                            ->atPath('username')
                                            ->addViolation();
                                }
                            }),

                    ],
                ])
            ->add('password', PasswordType::class, ['constraints' => [new NotBlank()]]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [

                'data_class' => UserCreateDto::class,
            ]);
    }
}
