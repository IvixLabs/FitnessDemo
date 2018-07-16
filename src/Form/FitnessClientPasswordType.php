<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Form used inside fitness client dashboard for change password
 */
class FitnessClientPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'password',
            PasswordType::class,
            [
                'constraints' => [new NotBlank()],
            ]);

        $builder->add(
            'repeatPassword',
            PasswordType::class,
            [
                'constraints' => [new NotBlank()],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'constraints' => [
                    new Callback(
                        function ($payload, ExecutionContextInterface $context) {
                            if ($payload['password'] !== $payload['repeatPassword']) {
                                $context->buildViolation('Пароли должны совпадать')
                                        ->atPath('[password]')
                                        ->addViolation();
                            }
                        }),
                ],
            ]);
    }
}