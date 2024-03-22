<?php

namespace App\Form;

use App\Entity\Events;
use App\Entity\Rooms;
use App\Entity\Schools;
use App\Entity\Users;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $ecole = $options['ecole'];
        $builder
            ->add('name', TextType::class, [
                'label' => "Nom de l'évènement",
                'attr' => [
                    'class' => 'form-control'
                ],
                'label_attr' => [
                    'class' => 'form-label'
                ]
            ])
            ->add('start', DateTimeType::class, [
                'label' => 'Heure de début',
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'class' => 'form-control datetimepickr my-flatpickr',
                ],
            ])
            ->add('end', DateTimeType::class, [
                'label' => 'Heure de fin',
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'class' => 'form-control datetimepickr my-flatpickr',
                ],
            ])
            ->add('users', EntityType::class, [
                'label' => 'Personnes concernées',
                'class' => Users::class,
                'query_builder' => function (EntityRepository $er) use ($ecole) {
                    return $er->createQueryBuilder('u')
                    ->join('u.school', 'e')
                    ->andWhere('e.id = :ecoleId')
                    ->setParameter('ecoleId', $ecole->getId());
            },
                'choice_label' => 'UserIdentifier',
                'multiple' => true,
                'required' => true,
                'attr' => [
                    'class' => 'form-control users-field'
                ],
            ])
            ->add('room', EntityType::class, [
                'class' => Rooms::class,
                'placeholder' => 'Choisir une salle pour le cours',
                'choice_label' => 'name',
                'required' => false,
                'label' => 'Salle',
                'query_builder' => function (EntityRepository $er) use ($ecole) {
                    return $er->createQueryBuilder('r')
                        ->join('r.school', 'ec')
                        ->andWhere('ec.id = :ecole')
                        ->setParameter('ecole', $ecole);
                },
                'attr' => [
                    'class' => 'room-select form-control',
                ],
                'label_attr' => [
                    'class' => 'label-room',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => "Description de l'évènement",
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Events::class,
            'ecole' => null,
        ]);
    }
}
