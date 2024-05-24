<?php

namespace App\Form;

use App\Entity\Disciplines;
use App\Entity\Schools;
use App\Entity\Users;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DisciplinesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $ecole = $options['ecole'];
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la Discipline',
            ])
            ->add('teachers', EntityType::class, [
                'label' => 'Professeurs',
                'class' => Users::class,
                'multiple' => true,
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
                    'class' => '',
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Disciplines::class,
            'ecole' => null,
        ]);
    }
}
