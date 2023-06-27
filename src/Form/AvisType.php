<?php

namespace App\Form;

use App\Entity\Avis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class AvisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => "Entrez l'email"
                ]
            ])
            ->add('nom', TextType::class, [
                'attr' => [
                    'placeholder' => "Entrez le nom"
                ]
            ])
            ->add('prenom', TextType::class, [
                'attr' => [
                    'placeholder' => "Entrez le prénom"
                ]
            ])
            ->add('commentaire', TextareaType::class, [
                'attr' => [
                    'placeholder' => "Entrez votre commentaire",
                    'class' => 'form-control',
                    'style' => 'resize: none; height: 150px; font-size: 14px; padding: 10px;',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer un commentaire.',
                    ]),
                    new Length([
                        'min' => 10,
                        'minMessage' => 'Le commentaire doit contenir au moins {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('notation', ChoiceType::class, [
                'choices' => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                    '5' => 5,
                ],
                'placeholder' => 'Choisissez une note / 5',
            ])
            // ->add('date_enregistrement')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avis::class,
        ]);
    }
}
