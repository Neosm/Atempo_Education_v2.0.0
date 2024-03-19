<?php

namespace App\Form;

use App\Entity\Programs;
use App\Entity\Users;
use App\Repository\UsersRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class ProgramsType extends AbstractType
{
    private $usersRepository;

    public function __construct(UsersRepository $usersRepository)
    {
        $this->usersRepository = $usersRepository;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {$ecole = $options['ecole'];
        $allusers = $this->usersRepository->findAllStudentByEcole($ecole);
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du programme',
                'attr' => [
                    'class' => 'form-control'
                ],
                'label_attr' => [
                    'class' => 'form-label'
                ]
            ])
            ->add('thumbnail', FileType::class, [
                'label' => 'Illustration',
                'mapped'=>false,
                'multiple'=>false,
                'required'=>false,
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '600k',
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
            ->add('type', ChoiceType::class, [
                'label' => 'Programme privé ou public',
                'choices'  => [
                    'Public' => true,
                    'Privé' => false,
                ],
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Programs::class,
            'ecole' => null,
        ]);
    }
}
