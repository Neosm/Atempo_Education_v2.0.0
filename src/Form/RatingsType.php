<?php

namespace App\Form;

use App\Entity\Evaluations;
use App\Entity\Ratings;
use App\Entity\Schools;
use App\Entity\Users;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RatingsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('comment')
            ->add('student', EntityType::class, [
                'class' => Users::class,
'choice_label' => 'id',
            ])
            ->add('evaluation', EntityType::class, [
                'class' => Evaluations::class,
'choice_label' => 'id',
            ])
            ->add('school', EntityType::class, [
                'class' => Schools::class,
'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ratings::class,
        ]);
    }
}
