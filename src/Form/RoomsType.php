<?php

namespace App\Form;

use App\Entity\Rooms;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class RoomsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la salle',
                'attr' => [
                    "class" => 'form-control'
                ]
            ])
            ->add('equipments', ChoiceType::class, [
                'label' => 'Équipements',
                'required' => true, 
                'choices' => [
                    'Piano' => 'Piano',
                    'Guitare acoustique' => 'Guitare acoustique',
                    'Batterie' => 'Batterie',
                    'Vidéo Projecteur' => 'Vidéo Projecteur',
                    'Tableau' => 'Tableau',
                    'Orchestre' => 'Orchestre',
                    'Contrebasse' => 'Contrebasse',
                    'Violoncelle' => 'Violoncelle',
                    'Synthétiseur' => 'Synthétiseur',
                    'Percussions ' => 'Percussions ',
                    'Microphones' => 'Microphones',
                    'Amplificateurs' => 'Amplificateurs',
                    'Violon' => 'Violon',
                    'Accessoires (métronomes, accordeurs, pupitres, etc.)' => 'Accessoires (métronomes, accordeurs, pupitres, etc.)',
                    // Ajoutez d'autres options prédéfinies si nécessaire
                ],
                'multiple' => true, // Permet à l'utilisateur de sélectionner plusieurs options
                'attr' => [
                    'class' => ''
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Rooms::class,
        ]);
    }
}
