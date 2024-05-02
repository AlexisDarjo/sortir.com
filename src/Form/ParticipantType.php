<?php

namespace App\Form;

use App\Entity\Participant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType; // Ajout de l'import
use Symfony\Component\Form\Extension\Core\Type\EmailType; // Ajout de l'import
use Symfony\Component\Form\Extension\Core\Type\CheckboxType; // Ajout de l'import

class ParticipantType extends AbstractType
{


    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('pseudo', TextType::class, [
                'attr' => ['class' => 'input is-rounded']
            ])
            ->add('nom', TextType::class, [
                'attr' => ['class' => 'input is-rounded']
            ])
            ->add('prenom', TextType::class, [
                'attr' => ['class' => 'input is-rounded']
            ])
            ->add('telephone', TextType::class, [
                'attr' => ['class' => 'input is-rounded']
            ])
            ->add('mail', EmailType::class, [
                'attr' => ['class' => 'input is-rounded']
            ])
            ->add('actif', CheckboxType::class, [
                'attr' => ['class' => 'checkbox']
            ]);
    }


    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
