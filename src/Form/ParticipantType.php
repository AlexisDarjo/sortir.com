<?php

namespace App\Form;

use App\Entity\Participant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType; // Ajout de l'import
use Symfony\Component\Form\Extension\Core\Type\EmailType; // Ajout de l'import
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Validator\Constraints\File;

// Ajout de l'import

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
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'label' => 'Mot de passe',
                    'attr' => ['class' => 'input is-rounded'],
                    'hash_property_path' => 'password',
                ],
                'second_options' => [
                    'label' => 'Confirmez le mot de passse',
                    'attr' => ['class' => 'input is-rounded'],
                ],
                'mapped' => false,
            ])
            ->add('poster_file', FileType::class, [
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'maxSizeMessage' => 'Ton image est trop volumineuse...',
                        'mimeTypes' => [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                            'image/gif',
                        ],
                        'mimeTypesMessage' => 'Ce format est pas ok',
                    ])
                ]
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
