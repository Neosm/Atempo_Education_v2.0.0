<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AbsencesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $student = $options['students'][$builder->getName()];

        $builder
            ->add('student_name', TextType::class, [
                'mapped' => false,
                'data' => $student->getFirstname() . ' ' . $student->getLastname(),
                'attr' => ['readonly' => true],
            ])
            ->add('est_absent', CheckboxType::class, [
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'students' => [],
        ]);
    }
}