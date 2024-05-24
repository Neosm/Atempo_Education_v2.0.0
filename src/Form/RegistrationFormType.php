<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('firstname', TextType::class, [
            'label'=>'Prénom',
            'attr'=>[
                'class' => 'form-control form-control-lg'
            ],
            'label_attr' => [
                'class' => 'form-label'
            ]
        ])
        ->add('lastname', TextType::class, [
            'label'=>'Nom',
            'attr'=>[
                'class' => 'form-control form-control-lg'
            ],
            'label_attr' => [
                'class' => 'form-label'
            ]
        ])
        ->add('email', EmailType::class, [
            'label' => 'Email',
            'attr'=>[
                'Placeholder'=>'exemple@exemp.com',
                'class' => 'form-control form-control-lg'
            ],
            'label_attr' => [
                'class' => 'form-label'
            ]
        ])
        ->add('phone_number', TelType::class, [
            'label' => 'Téléphone',
            'attr'=>[
                'Placeholder'=>'0607080901',
                'class' => 'form-control form-control-lg'
            ],
            'label_attr' => [
                'class' => 'form-label'
            ]
        ])
        ->add('date_of_birth', DateType::class, [
            'label' => "Date de naissance",
            'widget' => 'single_text',
            'html5' => false,
            'attr' => [
                'class' => 'form-control form-control-lg datetimepickr'
            ],
        ])
        ->add('address', TextType::class, [
            'label' => 'Adresse',
            'attr' => [
                'class' => 'form-control form-control-lg'
            ],
            'label_attr' => [
                'class' => 'form-label'
            ]
        ])
        ->add('area_code', TextType::class, [
            'label' => 'Code région',
            'attr' => [
                'class' => 'form-control form-control-lg'
            ],
            'label_attr' => [
                'class' => 'form-label'
            ]
        ])
        ->add('zip_code', TextType::class, [
            'label' => 'Zip Code',
            'attr' => [
                'class' => 'form-control form-control-lg'
            ],
            'label_attr' => [
                'class' => 'form-label'
            ]
        ])
        ->add('city', TextType::class, [
            'label' => 'Ville',
            'attr' => [
                'class' => 'form-control form-control-lg'
            ],
            'label_attr' => [
                'class' => 'form-label'
            ]
        ])
        ->add('roles', ChoiceType::class, [
            'label' => 'Role de l\'utilisateur',
            'placeholder'=> 'Élève, Professeur, Secrétariat',
            'required' => true,
            'multiple' => false,
            'expanded' => false,
            'attr' => [
                'class' => 'form-control form-control-lg'
            ],
            'choices'=> [
                'Élèves'=> "ROLE_STUDENT",
                'Professeur'=> "ROLE_TEACHER",
                'Secrétariat'=> "ROLE_SECRETARIAT",
            ],
        ])
        ->add('thumbnail', FileType::class, [
            'label' => 'Photo de profil',
            'mapped'=>false,
            'multiple'=>false,
            'required'=>false,
            'attr' => [
                'class' => 'form-control form-control-lg'
            ],
            'row_attr'=>[
                'class'=>'mb-3'
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
        ])
        ->add('password',PasswordType::class, [
            'invalid_message'=>"Les deux mots de passe ne sont pas semblables, veuillez les retaper",
            'label'=>'Mot de passe',
            'required'=>true,
            'constraints' => [
                new NotBlank([
                    'message' => 'Entrer le mot de passe',
                ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'Votre mot de passe doit faire minimum {{ limit }} caracters',
                    // max length allowed by Symfony for security reasons
                    'max' => 4096,
                ]),
            ],
            'label'=>'Mot de passe',
            'attr'=>[
                'class' => 'form-control form-control-lg'
            ],
            'label_attr' => [
                'class' => 'form-label'
            ]
        ],
        )
        ;
        // Data transformer
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    // transform the array to a string
                    return count($rolesArray) > 0? $rolesArray[0]: null;
                },
                function ($rolesString) {
                    // transform the string back to an array
                    return [$rolesString];
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
