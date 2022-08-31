<?php

namespace App\DataFixtures;

use App\Entity\Etat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EtatFixtures extends Fixture
{
    public const ETAT_CREEE = 'etatCree';
    public const ETAT_OUVERTE = 'etatOuvert';
    public const ETAT_CLOTUREE = 'etatCloture';
    public const ETAT_EN_COURS = 'etatEnCours';
    public const ETAT_PASSEE = 'etatPasse';
    public const ETAT_ANNULEE = 'etatAnnule';

    public function load(ObjectManager $manager): void
    {
        $etatCree = new Etat();
        $etatCree->setLibelle(Etat::ETAT_CREEE);
        $manager->persist($etatCree);

        $etatOuvert = new Etat();
        $etatOuvert->setLibelle(Etat::ETAT_OUVERTE);
        $manager->persist($etatOuvert);

        $etatCloture = new Etat();
        $etatCloture->setLibelle(Etat::ETAT_CLOTUREE);
        $manager->persist($etatCloture);

        $etatEnCours = new Etat();
        $etatEnCours->setLibelle(Etat::ETAT_EN_COURS);
        $manager->persist($etatEnCours);

        $etatPasse = new Etat();
        $etatPasse->setLibelle(Etat::ETAT_PASSEE);
        $manager->persist($etatPasse);

        $etatAnnule = new Etat();
        $etatAnnule->setLibelle(Etat::ETAT_ANNULEE);
        $manager->persist($etatAnnule);

        $manager->flush();

        $this->addReference(self::ETAT_CREEE, $etatCree);
        $this->addReference(self::ETAT_OUVERTE, $etatOuvert);
        $this->addReference(self::ETAT_CLOTUREE, $etatCloture);
        $this->addReference(self::ETAT_EN_COURS, $etatEnCours);
        $this->addReference(self::ETAT_PASSEE, $etatPasse);
        $this->addReference(self::ETAT_ANNULEE, $etatAnnule);
    }
}
