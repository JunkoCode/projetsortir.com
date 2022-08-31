<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\Utilisateur;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends ApplicationType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, $this->getConfiguration("Email", "Votre adresse email"))
            ->add('password', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                //'mapped' => false,
                'label' => 'Mot de passe',
                'attr' =>
                    ['autocomplete' => 'new-password',
                    'placeholder'=>'Votre mot de passe'
                    ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit être au minimum de {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('passwordConfirm', PasswordType::class, $this->getConfiguration("Confirmation de mot de passe", "Veuillez confirmer votre mot de passe"))
            ->add('pseudo', TextType::class, $this->getConfiguration("Pseudo", "Votre pseudo"))
            ->add('nom', TextType::class, $this->getConfiguration("Nom", "Votre nom"))
            ->add('prenom', TextType::class, $this->getConfiguration("Prénom", "Votre prénom"))
            ->add('telephone', TextType::class, $this->getConfiguration("N° de téléphone", "Votre numéro de téléphone"))
            ->add('campus',EntityType::class,[
                'class'=> Campus::class,
                'choice_label'=>'nom',
                'label'=>'Campus',
                'placeholder'=>'Selectionner votre campus',
                'query_builder' => function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.nom', 'ASC');
                },])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Utilisateur::class,
        ]);
    }
}
