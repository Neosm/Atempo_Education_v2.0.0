<?php

namespace App\Form;

use App\Entity\Courses;
use App\Entity\Evaluations;
use App\Entity\Lessons;
use App\Entity\Programs;
use App\Entity\Schools;
use App\Entity\Users;
use App\Repository\UsersRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;

class LessonsType extends AbstractType
{
    private $usersRepository;

    public function __construct(UsersRepository $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $ecole = $options['ecole'];
        $allusers = $this->usersRepository->findAllStudentByEcole($ecole);
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la leçon',
                'attr' => [
                    'class' => 'form-control'
                ],
                'label_attr' => [
                    'class' => 'form-label'
                ]
            ])
            ->add('users', EntityType::class, [
                'class' => Users::class,
                'choices' => $allusers,
                'multiple' => true,
                'placeholder' => 'Élèves à qui partager',
                'required'=>false,
                'label'=>'Élèves',
                'attr' => [
                    'class' => 'form-control'
                ],
                'label_attr' => [
                    'class' => 'form-label'
                ]
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Leçons privée ou publique',
                'choices'  => [
                    'Publique' => '1',
                    'Privée' => '0',
                ],
                'attr' => [
                    'class' => 'form-control'
                ],
                'label_attr' => [
                    'class' => 'form-label'
                ]
            ])
            ->add('program', EntityType::class, [
                'class' => Programs::class,
                'required' =>false,
                'placeholder' => 'Choisir un programme',
                'label' => 'Programme',
                'choice_label'=>'name',
                'attr' => [
                    'class' => 'form-control'
                ],
                'label_attr' => [
                    'class' => 'form-label'
                ]
            ])
            ->add('thumnail', FileType::class, [
                'label' => 'Illustration',
                'mapped'=>false,
                'multiple'=>false,
                'required' => false,
                'row_attr'=>[
                    'class'=>'mb-3'
                ],
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '1500k',
                        'mimeTypes' => [
                            'image/png',
                            'image/jpg',
                            'image/jpeg',
                        ],
                        'mimeTypesMessage' => 'Choisissez une image valide',
                    ])
                ],
                'attr' => [
                    'accept'=>'.jpg, .jpeg, .png',
                    'class' => 'form-control'
                ]
            ])
            ->add('content', TextareaType::class, [
                'label' => 'Contenu de la leçon',
                'required' => false,
                'attr' => [
                    'class' => 'form-control',
                    'row' => "4",
                ],
                'label_attr' => [
                    'class' => 'form-label',
                ]
            ])
            ->add('pdf', FileType::class, [
                'label' => 'Cours | Fichier PDF',
                'mapped' => false,
                'required' => false,
                'multiple' => true,
                'constraints' => [
                    new All([
                        new File( [
                            'maxSize' => '130M',
                            'maxSizeMessage'=>'Fichier trop lourd, poids maximum : 130 Méga-Octet, veuillez choisir un fichier plus petit',
                            'mimeTypes' => [
                                'application/pdf',
                                'application/x-pdf',
                            ],
                            'mimeTypesMessage' => 'Veuillez téléverser un fichier valide s\'il vous plait',
                        ])
                    ])
                ],
                'attr' => [
                    'accept'=>'.pdf',
                    'class' => 'form-control',
                ],
                'label_attr' => [
                    'class' => 'form-label'
                ]
            ])
            ->add('audio', UrlType::class, [
                'mapped' => false,
                'required'=>false,
                'label' => "Audio | Lien URL du son",
                'attr' => [
                    'accept'=>'.pdf',
                    'class' => 'form-control'
                ],
                'label_attr' => [
                    'class' => 'form-label'
                ]
            ])
            ->add('video', UrlType::class, [
                'mapped' => false,
                'required'=>false,
                'label' => "Vidéo | Lien URL de la vidéo",
                'attr' => [
                    'accept'=>'.pdf',
                    'class' => 'form-control'
                ],
                'label_attr' => [
                    'class' => 'form-label'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lessons::class,
            'ecole' => null,
        ]);
    }
}
