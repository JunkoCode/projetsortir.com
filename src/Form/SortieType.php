<?php

namespace App\Form;

use App\Entity\Lieu;
use App\Entity\Sortie;
use App\Entity\Utilisateur;
use DateTimeImmutable;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', null, ['label' => 'Nom de la sortie', 'attr' => ['placeholder' => 'Indiquez un titre pour la sortie']])
            ->add('dateHeureDebut', DateTimeType::class, ['label' => 'Date et heure de la sortie', 'widget' => 'single_text', 'data' => new DateTimeImmutable("+2 day")])
            ->add('dateLimiteInscription', DateTimeType::class, ['label' => 'Date limite d\'inscription', 'widget' => 'single_text', 'data' => new DateTimeImmutable("+1 day")])
            ->add('nombreInscriptionMax', null, ['label' => 'Nombre de place', 'attr' => ['placeholder' => 'Nombre de participants maximum ', 'value' => 1]])
            ->add('duree', NumberType::class, ['mapped' => false, 'label' => 'Durée (en heure)', 'html5' => true, 'attr' => ['min' => '0.5', 'max' => '24', 'step' => '0.5', 'value' => 1]])
            ->add('infoSortie', null, ['label' => 'Description et infos', 'attr' => ['placeholder' => 'Renseigner les détails de la sortie', 'rows' => 5]])
            ->add('organisateur', EntityType::class, ['class' => Utilisateur::class, 'choice_label' => 'pseudo'])
            ->add('lieu', EntityType::class, ['class' => Lieu::class, 'choice_label' => 'nom'])
            ->add('latitude', TextType::class, ['required' => false, 'attr' => ['placeholder' => 'Préciser si besoin la latitude du lieu'], 'mapped' => false])
            ->add('longitude', TextType::class, ['required' => false, 'attr' => ['placeholder' => 'Préciser si besoin la longitude du lieu'], 'mapped' => false])
            ->add('btnEnregistrer', SubmitType::class, ['label' => 'Enregistrer', 'attr'=>['class' => 'btn-outline-success']])
            ->add('btnPublier', SubmitType::class, ['label' => 'Publier la sortie', 'attr'=>['class' => 'btn-outline-success']]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
