<?php

namespace App\DataFixtures;

use App\Entity\Etat;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class EtatFixtures extends Fixture
{
    public const ETAT_SORTIE1='etat1';
    public const ETAT_SORTIE2='etat2';
    public const ETAT_SORTIE3='etat3';

    public function load(ObjectManager $manager): void
    {
        $etat1 = new Etat();
        $etat1->setLibelle('En création');
        $manager->persist($etat1);

        $etat2 = new Etat();
        $etat2->setLibelle('Ouvert');
        $manager->persist($etat2);

        $etat3 = new Etat();
        $etat3->setLibelle('En cours');
        $manager->persist($etat3);

        $manager->flush();

        $this->addReference(self::ETAT_SORTIE1, $etat1);
        $this->addReference(self::ETAT_SORTIE2, $etat2);
        $this->addReference(self::ETAT_SORTIE3, $etat3);
    }
}
