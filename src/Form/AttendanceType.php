<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttendanceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $students = $options['students'];

        $builder
            ->add('retards', CollectionType::class, [
                'entry_type' => DelaysType::class,
                'entry_options' => [
                    'students' => $students,
                ],
                'allow_add' => false,
                'allow_delete' => false,
                'by_reference' => false,
            ])
            ->add('absences', CollectionType::class, [
                'entry_type' => AbsencesType::class,
                'entry_options' => [
                    'students' => $students,
                ],
                'allow_add' => false,
                'allow_delete' => false,
                'by_reference' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
            'students' => [],
            'course' => null,
            'evaluation' => null,
        ]);
    }
}