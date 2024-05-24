<?php

namespace App\Form;

use App\Entity\Disciplines;
use App\Entity\Evaluations;
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
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EvaluationsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $ecole = $options['ecole'];
        $isAdminRoute = $options['isAdminRoute'];
        $user = $options['user'];
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
            ->add('coefficient', IntegerType::class, [
                'label' => 'Coefficient',
                'required' => true,
                'attr' => [
                    'class' => ' form-control',
                ]
            ])
            ->add('max_notation', IntegerType::class, [
                'label' => 'Note maximal',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ]
            ])
            ->add('room', EntityType::class, [
                'class' => Rooms::class,
                'placeholder' => 'Choisir une salle pour le cours',
                'choice_label' => 'name',
                'required' => true,
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
                    'class' => 'student-class-field '
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
                    'class' => 'students-field '
                ],
                'label_attr' => [
                    'class' => 'label-students',
                ],
            ])
            ->add('teacher', EntityType::class, [
                'label' => 'Professeur',
                'class' => Users::class,
                'query_builder' => function (EntityRepository $er) use ($ecole, $isAdminRoute, $user) {
                    $qb = $er->createQueryBuilder('u')
                        ->join('u.school', 'e')
                        ->andWhere('e.id = :ecoleId')
                        ->setParameter('ecoleId', $ecole->getId());

                    if ($isAdminRoute) {
                        // Si vous êtes sur la route administrateur, permettre de choisir parmi tous les professeurs
                        $qb->andWhere('u.roles LIKE :val')
                            ->setParameter('val', '%["ROLE_TEACHER"]%');
                    } else {
                        // Si vous êtes sur la route Professeur, désactiver le champ et limiter le choix à votre utilisateur
                        $qb->andWhere('u.id = :userId')
                            ->setParameter('userId', $user->getId());
                    }

                    return $qb;
                },
                'choice_label' => 'UserIdentifier',
                'attr' => [
                    'class' => 'form-control',
                    'disabled' => $isAdminRoute ? false : 'disabled',
                ]
            ])
            ->add('program', EntityType::class, [
                'label' => 'Programmes',
                'class' => Programs::class,
                'query_builder' => function (EntityRepository $er) use ($ecole) {
                    return $er->createQueryBuilder('p')
                        ->join('p.school', 'ec')
                        ->andWhere('ec.id = :ecole')
                        ->setParameter('ecole', $ecole);
                },
                'choice_label' => 'name',
                'attr' => [
                    'class' => 'programmes-field'
                ],
                'multiple' => true,
                'required' => false,
            ])
            ->add('lesson', EntityType::class, [
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
                    'class' => 'lecons-field'
                ],
                'multiple' => true,
                'required' => false,
            ])
            ->add('reservedRooms', HiddenType::class, [
                'mapped' => false,
            ]);
        ;
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
            $form = $event->getForm();
            $data = $event->getData();

            if ($data instanceof Evaluations && $data->getId() !== null) {
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
                $form->add('objective', TextareaType::class, [
                    'label' => 'Objectif de l\'examen',
                    'required' => false,
                    'attr' => [
                        'class' => 'form-control',
                    ]
                ]);
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evaluations::class,
            'ecole' => null,
            'isAdminRoute' => false, // Par défaut, vous n'êtes pas sur la route administrateur
            'user' => null, // Utilisateur pour la route Professeur
        ]);
    }
}
