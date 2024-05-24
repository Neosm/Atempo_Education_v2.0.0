<?php

namespace App\Form;

use App\Entity\Schools;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class SchoolType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',
                ],
            ])
            ->add('number', IntegerType::class, [
                'required' => true,
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[0-9]*$/',
                        'message' => 'Le numéro doit contenir uniquement des chiffres.',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control mb-3',
                ],
            ])
            ->add('city', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',
                ],
            ])
            ->add('campus', TextType::class, [
                'attr' => [
                    'class' => 'form-control mb-3',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Schools::class,
        ]);
    }
}
