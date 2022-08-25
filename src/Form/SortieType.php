<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('dateLimiteInscription',DateTimeType::class,['widget'=>'single_text','data'=>new \DateTimeImmutable()])
            ->add('dateHeureDebut',DateTimeType::class,['widget'=>'single_text','data'=>new \DateTimeImmutable()])
            ->add('duree',DateIntervalType::class,['widget'      => 'integer',
                'with_years'  => false,
                'with_months' => false,
                'with_days'   => false,
                'with_hours'  => true,
                'with_minutes'  => true,
                'labels' => [
        'hours' => 'Heure',
    ]])
            ->add('nombreInscriptionMax')
            ->add('infoSortie')
            //->add('etat',EntityType::class, [
            //'class' => Etat::class, 'choice_label'=>'nom'])
            //->add('lieu',EntityType::class, [
            //    'class' => Lieu::class])
            //->add('organisateur')
            //->add('participants')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Sortie::class,
        ]);
    }
}
