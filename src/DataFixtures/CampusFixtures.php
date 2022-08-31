<?php

namespace App\DataFixtures;

use App\Entity\Campus;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CampusFixtures extends Fixture
{
    public const CAMPUS_USER1 = 'campus1';
    public const CAMPUS_USER2 = 'campus2';
    public const CAMPUS_USER3 = 'campus3';

    public function load(ObjectManager $manager): void
    {

        $campus1 = new Campus();
        $campus1->setNom('Nantes');
        $manager->persist($campus1);

        $campus2 = new Campus();
        $campus2->setNom('Rennes');
        $manager->persist($campus2);

        $campus3 = new Campus();
        $campus3->setNom('La Roche sur yon');
        $manager->persist($campus3);

        $manager->flush();

        $this->addReference(self::CAMPUS_USER1, $campus1);
        $this->addReference(self::CAMPUS_USER2, $campus2);
        $this->addReference(self::CAMPUS_USER3, $campus3);
    }
}
