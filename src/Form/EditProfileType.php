<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class EditProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('firstname', TextType::class, [
                'label'=>'Prénom',
                'attr'=>[
                    'placeholder'=>'Votre prénom',
                    'class' => 'form-control form-control-lg'
                ]
            ])
            ->add('lastname', TextType::class, [
                'label'=>'Nom',
                'attr'=>[
                    'placeholder'=>'Votre nom',
                    'class' => 'form-control form-control-lg'
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'attr'=>[
                    'Placeholder'=>'exemple@exemple.com',
                    'class' => 'form-control form-control-lg'
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
                'years' => range(date('Y') - 100, date('Y')),
                'attr' => [
                    'class' => 'form-control form-control-lg'
                ],
            ])
            ->add('address', TextType::class, [
                'attr' => [
                    'class' => 'form-control form-control-lg'
                ],
                'label_attr' => [
                    'class' => 'form-label'
                ]
            ])
            ->add('area_code', TextType::class, [
                'attr' => [
                    'class' => 'form-control form-control-lg'
                ],
                'label_attr' => [
                    'class' => 'form-label'
                ]
            ])
            ->add('zip_code', TextType::class, [
                'attr' => [
                    'class' => 'form-control form-control-lg'
                ],
                'label_attr' => [
                    'class' => 'form-label'
                ]
            ])
            ->add('city', TextType::class, [
                'attr' => [
                    'class' => 'form-control form-control-lg'
                ],
                'label_attr' => [
                    'class' => 'form-label'
                ]
            ])
            ->add('thumbnail', FileType::class, [
                'label' => 'Photo de profil',
                'mapped'=>false,
                'multiple'=>false,
                'required'=>false,
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
                ]
            ])
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}
