<?php

namespace App\Form;

use App\Entity\Courses;
use App\Entity\Evaluations;
use App\Entity\Schools;
use App\Entity\StudentClasses;
use App\Entity\Users;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StudentClassesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $ecole = $options['ecole'];
        $builder
            ->add('name', TextType::class, [
                "label" => 'Nom de la classe',
                'attr' => [
                    'class' => 'form-control'
                ]
            ])
            ->add('students', EntityType::class, [
                'label' => 'Élèves',
                'class' => Users::class,
                'query_builder' => function (EntityRepository $er)  use ($ecole, $options) {
                    $qb = $er->createQueryBuilder('u')
                    ->join('u.school', 'e')
                    ->andWhere('u.roles LIKE :val')
                    ->andWhere('e.id = :ecoleId')
                    ->setParameter('val', '%["ROLE_STUDENT"]%')
                    ->setParameter('ecoleId', $ecole->getId());

                    // Si vous êtes en mode d'édition et que des élèves sont déjà associés à la classe,
                    // excluez-les de la requête pour éviter de les réafficher
                    if ($options['studentsInClass']) {
                        $qb->orWhere('u IN (:studentsInClass)')
                            ->setParameter('studentsInClass', $options['studentsInClass']);
                    }

                    return $qb;
                },
                'choice_label' => 'UserIdentifier',
                'multiple' => true,
                'required' =>false,
                'attr' => [
                    'class' => 'students-field'
                ],
                'label_attr' => [
                    'class' => 'label-students',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => StudentClasses::class,
            'studentsInClass' => [], // Ajoutez cette option pour recevoir les étudiants pré-remplis
            'ecole' => null,
        ]);
    }
}
