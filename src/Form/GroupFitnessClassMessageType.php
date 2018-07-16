<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Callback;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Form used for create group fitness class message
 */
class GroupFitnessClassMessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add(
            'email',
            TextType::class,
            [
                'constraints' => [new Length(['max'=>255])],
            ]);

        $builder->add(
            'sms',
            TextType::class,
            [
                'constraints' => [new Length(['max'=>150])],
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'constraints' => [
                    new Callback(
                        function ($payload, ExecutionContextInterface $context) {
                            if (empty($payload['email']) && empty($payload['sms'])) {
                                $context->buildViolation('Одно из полей должно быть заполнено')
                                        ->atPath('[email]')
                                        ->addViolation();
                            }
                        }),
                ],
            ]);
    }
}