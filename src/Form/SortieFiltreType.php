<?php

namespace App\Form;

use App\data\FiltreData;
use App\Entity\Campus;
use DateTimeImmutable;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SortieFiltreType extends AbstractType
{

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => FiltreData::class,
            'methode' => 'GET',
            'csrf_protection' => false
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('filtreSortieMotCle', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'rechercher par mot clé'
                ]
            ])
            ->add('filtreSortieCampus', EntityType::class, [
                'label' => 'Campus : ',
                'class' => Campus::class,
                'expanded' => true,
                'multiple' => true,
            ])
            ->add('filtreSortieDateMin', DateType::class, [
                'label' => 'Entre : ',
                'required' => false,
                'widget' => 'single_text',
                'data' => new DateTimeImmutable("-1 day"),
                'attr'=>['min'=>new DateTimeImmutable("-30 day")]
            ])
            ->add('filtreSortieDateMax', DateType::class, [
                'label' => 'et : ',
                'required' => false,
                'widget' => 'single_text',
                'data' => new DateTimeImmutable("+30 day")
            ])
            ->add('filtreSortieOrganisateur', CheckboxType::class, [
                'label' => 'Sorties dont je suis l\'oganisteur/trice',
                'required' => false,
            ])
            ->add('filtreSortieInscrit', CheckboxType::class, [
                'label' => 'Sorties auxquelles je suis inscrit/e',
                'required' => false,
            ])
            ->add('filtreSortiePasInscrit', CheckboxType::class, [
                'label' => 'Sorties auxquelles je ne suis pas inscrit/e',
                'required' => false,
            ])
            ->add('filtreSortiePassees', CheckboxType::class, [
                'label' => 'Sorties passées',
                'required' => false,
            ]);
    }

}