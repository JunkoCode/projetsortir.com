<?php

namespace App\Form;

use App\Entity\Sortie;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom')
            ->add('dateLimiteInscription',DateType::class,['widget'=>'single_text','data'=>new \DateTimeImmutable(),
            'attr' => ['class' => 'js-datepicker']])
            ->add('dateHeureDebut',DateType::class,['widget'=>'single_text','data'=>new \DateTimeImmutable(),
                'attr' => ['class' => 'js-datepicker']])
            ->add('duree',DateType::class,['widget'=>'single_text',
                'attr' => ['class' => 'js-datepicker']])
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
