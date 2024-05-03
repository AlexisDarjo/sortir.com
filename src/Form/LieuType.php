<?php

namespace App\Form;

use App\Entity\Lieu;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use function PHPUnit\Framework\isEmpty;

class LieuType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[
                'label'=>'Lieu :',
                'required'=>true,
                'attr' => [
                    'class' => 'input custom-input',
                ],
                'label_attr' => [
                    'class' => 'label has-text-white', // Ajoutez la classe CSS souhaitée pour le libellé ici
                ],
            ])
            ->add('rue', TextType::class,[
                'label'=>'Rue :',
                'required'=>false,
                'attr' => [
                    'class' => 'input custom-input',
                ],
                'label_attr' => [
                    'class' => 'label has-text-white', // Ajoutez la classe CSS souhaitée pour le libellé ici
                ],
            ])
            ->add('lieu', VilleType::class, [
                'label'=>false,
                'required' => false,

            ])
            ->add('latitude', NumberType::class,[
                'label'=>'Latitude :',
                'required'=>false,
                'attr' => [
                    'class' => 'input custom-input',
                ],
                'label_attr' => [
                    'class' => 'label has-text-white', // Ajoutez la classe CSS souhaitée pour le libellé ici
                ],
            ])
            ->add('longitude', NumberType::class,[
                'label'=>'Longitude :',
                'required'=>false,
                'attr' => [
                    'class' => 'input custom-input',
                ],
                'label_attr' => [
                    'class' => 'label has-text-white', // Ajoutez la classe CSS souhaitée pour le libellé ici
                ],
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lieu::class,
        ]);
    }
}
