<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Site;
use App\Entity\Sortie;

use App\Entity\Ville;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[
                'label'=>'Nom de la sortie :',
                'required'=>true,
                'attr' => [
                    'class' => 'input custom-input',
                ],
                'label_attr' => [
                    'class' => 'label has-text-white', // Ajoutez la classe CSS souhaitée pour le libellé ici
                ],
            ])
            ->add('dateHeureDebut', DateTimeType::class, [
                'label'=> 'Date et heure de la sortie :' ,
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'input custom-input',
                ],
                'label_attr' => [
                    'class' => 'label has-text-white', // Ajoutez la classe CSS souhaitée pour le libellé ici
                ],
            ])
            ->add('dateLimiteInscription', DateTimeType::class, [
                'label'=> 'Date limite d\'inscription :' ,
                'widget' => 'single_text',
                'attr' => [
                    'class' => 'input custom-input',
                ],
                'label_attr' => [
                    'class' => 'label has-text-white', // Ajoutez la classe CSS souhaitée pour le libellé ici
                ],
            ])
            ->add('nbInscriptionsMax',IntegerType::class,[
            'label'=> 'Nombre de places :' ,
                'attr' => [
                    'class' => 'input custom-input',
                ],
                'label_attr' => [
                    'class' => 'label has-text-white', // Ajoutez la classe CSS souhaitée pour le libellé ici
                ],

            ])
            ->add('duree',IntegerType::class,[
                'label'=> 'Durée :' ,
                'attr' => [
                    'class' => 'input custom-input',
                ],
                'label_attr' => [
                    'class' => 'label has-text-white', // Ajoutez la classe CSS souhaitée pour le libellé ici
                ],
            ])


            ->add('infosSortie',TextareaType::class,[
                'label'=>'Description et infos :',
                'required'=>false,
                'attr' => [
                    'rows' => 20,
                    'class' => 'input custom-input',

                ],
                'label_attr' => [
                    'class' => 'label has-text-white', // Ajoutez la classe CSS souhaitée pour le libellé ici
                ],
            ])

            ->add('siteOrganisateur', EntityType::class, [
                'class' => Site::class,
                'choice_label' => 'nom',
                'attr' => [
                    'class' => 'input custom-input',
                ],
                'label_attr' => [
                    'class' => 'label has-text-white', // Ajoutez la classe CSS souhaitée pour le libellé ici
                ],
            ])

            ->add('lieu', EntityType::class, [
                'class' => Lieu::class,
                'choice_label' => 'nom',
                'choice_value' => 'id',
                'attr' => [
                    'class' => 'input custom-input',
                ],
                'label_attr' => [
                    'class' => 'label has-text-white', // Ajoutez la classe CSS souhaitée pour le libellé ici
                ],
                // Utilisez la variable $options['rue'] pour pré-remplir le champ de la rue
                'data' => $options['lieu'],

            ])





        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
            'lieu' => null,
            'ville'=>null,


        ]);
    }
}
