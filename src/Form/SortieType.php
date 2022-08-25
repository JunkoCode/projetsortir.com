<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('dateLimiteInscription')
            ->add('dateHeureDebut')
            ->add('duree')
            ->add('nombreInscriptionMax')
            ->add('infoSortie')
            ->add('etat',EntityType::class, [
            'class' => Etat::class])
            ->add('lieu',EntityType::class, [
                'class' => Lieu::class])
            ->add('organisateur')
            ->add('participants')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
