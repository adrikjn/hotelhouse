<?php

namespace App\Form;

use App\Entity\Commande;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;

class CommandeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_arrive', DateType::class, [
                'widget' => 'single_text',
                'label' => "Date et heure de réservation"
            ])
            ->add('date_depart', DateType::class, [
                'widget' => 'single_text',
                'label' => "Date et heure de rendue",
                'constraints' => [
                    new GreaterThanOrEqual([
                        'propertyPath' => 'parent.all[date_arrive].data',
                        'message' => 'La date de fin doit être supérieure ou égale à la date de réservation.'
                    ]),
                ],
            ])
            // ->add('prix_total')
            ->add('prenom', TextType::class, [
                'attr' => [
                    'placeholder' => "Entrez le prénom"
                ]
            ])
            ->add('nom', TextType::class, [
                'attr' => [
                    'placeholder' => "Entrez le nom"
                ]
            ])
            ->add('telephone', TextType::class, [
                'attr' => [
                    'placeholder' => "Entrez le numéro de téléphone",
                    'pattern' => "[0-9]{10}" // Exemple de format pour un numéro de téléphone à 10 chiffres
                ]
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => "Entrez l'email"
                ]
            ]);
        // ->add('date_enregistrement')
        // ->add('chambre');
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commande::class,
        ]);
    }
}
