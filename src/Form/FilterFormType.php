<?php

namespace App\Form;

use App\Entity\Etat;
use App\Entity\Lieu;
use App\Entity\Site;
use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class FilterFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('dateHeureDebut', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => false,
                'mapped' => false, // Ne lie pas ce champ à une propriété de l'entité

            ])
            ->add('dateLimiteInscription', DateTimeType::class, [
                'widget' => 'single_text',
                'required' => false,
                'mapped' => false, // Ne lie pas ce champ à une propriété de l'entité

            ])
            ->add('etatPassee', CheckboxType::class, [
                'required' => false, // La case à cocher n'est pas obligatoire
                'mapped' => false,
            ])
            ->add('organisateur', CheckboxType::Class, [
                'required' => false,
                'mapped' => false,
            ])


        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
