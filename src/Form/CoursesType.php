<?php

namespace App\Form;

use App\Entity\Courses;
use App\Entity\Disciplines;
use App\Entity\Lessons;
use App\Entity\Programs;
use App\Entity\Rooms;
use App\Entity\Schools;
use App\Entity\StudentClasses;
use App\Entity\Users;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoursesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $ecole = $options['ecole'];
        $builder
            ->add('discipline', EntityType::class, [
                'class' => Disciplines::class,
                'label' => 'Discipline',
                'query_builder' => function (EntityRepository $er) use ($ecole) {
                    return $er->createQueryBuilder('m')
                        ->join('m.school', 'ec')
                        ->andWhere('ec.id = :ecole')
                        ->setParameter('ecole', $ecole);
                },
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('start', DateTimeType::class, [
                'label' => 'Heure de début',
                'widget' => 'single_text',
                'html5' => false,
                'attr' => [
                    'class' => 'form-control datetimepickr my-flatpickr',
                    'data-route' => '/agenda/cours/api/reserved_rooms', // Route pour récupérer les salles réservées
                ],
            ])
            ->add('duration', IntegerType::class, [
                'label' => 'Durée (en minutes)',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('recurrence', ChoiceType::class, [
                'label' => 'Événement récurrent',
                'choices' => [
                    'Non' => false,
                    'Oui' => true,
                ],
                'multiple' => false,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('recurrence_frequency', ChoiceType::class, [
                'label' => 'Fréquence de récurrence',
                'choices' => [
                    'Tous les jours' => 'daily',
                    'Toutes les semaines' => 'weekly',
                    'Tous les mois' => 'monthly',
                ],
                'expanded' => true, // Pour afficher en tant que radios
                'multiple' => false,
                'required' => false, // Rendre ce champ facultatif
                'attr' => [
                    'class' => 'form-control', // Ajoutez des classes CSS si nécessaire
                ],
            ])
            ->add('recurrence_end', DateTimeType::class, [
                'label' => 'Date de fin de récurrence',
                'widget' => 'single_text',
                'html5' => false,
                'required' => false, // Rendre ce champ facultatif
                'attr' => [
                    'class' => 'form-control datetimepickr', // Ajoutez des classes CSS si nécessaire
                ],
            ])
            ->add('teacher', EntityType::class, [
                'label' => 'Professeur',
                'class' => Users::class,
                'query_builder' => function (EntityRepository $er) use ($ecole) {
                    return $er->createQueryBuilder('u')
                    ->join('u.school', 'e')
                    ->andWhere('u.roles LIKE :val')
                    ->andWhere('e.id = :ecoleId')
                    ->setParameter('val', '%["ROLE_TEACHER"]%')
                    ->setParameter('ecoleId', $ecole->getId());
            },
                'choice_label' => 'UserIdentifier',
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('zoomswitch', CheckboxType::class, [
                'label' => false,
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-check-input',
                    'role' => 'switch',
                ],
                'label_attr' => [
                    'class' => 'form-switch-label',
                ],
            ])
            ->add('zoom_link', UrlType::class, [
                'label' => "Lien pour le cours à distance",
                'required' => false,
                'label_attr' => [
                    'class' => 'label-zoom',
                ],
                'attr' => [
                    'class' => 'form-control',
                ]
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
            ->add('studentswitch', CheckboxType::class, [
                'label' => false,
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'class' => 'form-check-input',
                    'role' => 'switch',
                ],
                'label_attr' => [
                    'class' => 'form-switch-label',
                ],
            ])
            ->add('studentClasses', EntityType::class, [
                'label' => 'Classe',
                'multiple' => true,
                'class' => StudentClasses::class,
                'choice_label' => 'name',
                'required' => false,
                'query_builder' => function (EntityRepository $er) use ($ecole) {
                    return $er->createQueryBuilder('sc')
                        ->join('sc.school', 'ec')
                        ->andWhere('ec.id = :ecole')
                        ->setParameter('ecole', $ecole);
                },
                'placeholder' => "Choisissez la classe à laquelle vous faites cours",
                'attr' => [
                    'class' => 'student-class-field form-control'
                ],
                'label_attr' => [
                    'class' => 'label-studentClass',
                ],
            ])
            ->add('students', EntityType::class, [
                'label' => 'Élèves',
                'class' => Users::class,
                'query_builder' => function (EntityRepository $er) use ($ecole) {
                    return $er->createQueryBuilder('u')
                    ->join('u.school', 'e')
                    ->andWhere('u.roles LIKE :val')
                    ->andWhere('e.id = :ecoleId')
                    ->setParameter('val', '%["ROLE_STUDENT"]%')
                    ->setParameter('ecoleId', $ecole->getId());
            },
                'choice_label' => 'UserIdentifier',
                'multiple' => true,
                'required' => false,
                'attr' => [
                    'class' => 'students-field form-control'
                ],
                'label_attr' => [
                    'class' => 'label-students',
                ],
            ])
            ->add('programs', EntityType::class, [
                'class' => Programs::class,
                'query_builder' => function (EntityRepository $er) use ($ecole) {
                    return $er->createQueryBuilder('p')
                        ->join('p.school', 'ec')
                        ->andWhere('ec.id = :ecole')
                        ->setParameter('ecole', $ecole);
                },
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'programmes-field form-control'
                ],
                'multiple' => true,
                'required' => false,
            ])
            ->add('lessons', EntityType::class, [
                'label' => 'Leçons',
                'placeholder' => "Souhaitez-vous ajouter une/des leçon(s) ?",
                'class' => Lessons::class,
                'query_builder' => function (EntityRepository $er) use ($ecole) {
                    return $er->createQueryBuilder('l')
                        ->join('l.school', 'ec')
                        ->andWhere('ec.id = :ecole')
                        ->setParameter('ecole', $ecole);
                },
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'lecons-field form-control'
                ],
                'multiple' => true,
                'required' => false,
            ])
            ->add('reservedRooms', HiddenType::class, [
                'mapped' => false,
            ]);

            $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                $form = $event->getForm();
                $data = $event->getData();
    
                if ($data instanceof Courses && $data->getId() !== null) {
                    // Pré-remplir les salles réservées si l'événement existe déjà
                    $reservedRooms = $data->getRoom();
                    if ($reservedRooms !== null) {
                        $form->get('reservedRooms')->setData($reservedRooms->getId());
                    }
                }
    
                // Récupérer la date de début de l'événement
                $startDate = $data->getStart();
                $currentDateTime = new \DateTime();
    
                if ($startDate !== null && $startDate < $currentDateTime) {
                        $form->add('comment', TextareaType::class, [
                            'label' => 'Commentaire',
                            'required' => false,
                            'attr' => [
                                'class' => 'form-control',
                            ]
                        ]);
                } else {
                    // Si la date de début est future, ajoutez le champ "Objectif"
                    $form->add('objectives', TextareaType::class, [
                        'label' => 'Objectif de la Séance et Travail à Faire',
                        'required' => false,
                        'attr' => [
                            'class' => 'form-control',
                        ]
                    ]);
                }
    
                // Vérifiez si l'événement est une récurrence ou a un parentEventId
                $isRecurrence = $data->isRecurrence() == true || $data->getParent() !== null;
    
                // Ajoutez le champ modificationScope uniquement si c'est une récurrence
                if ($isRecurrence) {
                    $form->add('modificationScope', ChoiceType::class, [
                        'label' => 'Portée de la modification',
                        'choices' => [
                            'Cet événement' => 'this_event',
                            'Tous les événements de la récurrence' => 'all_events',
                            'Tous les événements futurs de la récurrence' => 'future_events',
                        ],
                        'expanded' => true,
                        'required' => true,
                        'mapped' => false,
                    ]);
                }
            });    
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Courses::class,
            'ecole' => null,
        ]);
    }
}
