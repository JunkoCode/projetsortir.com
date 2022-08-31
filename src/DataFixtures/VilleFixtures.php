<?php

namespace App\DataFixtures;

use App\Entity\Ville;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class VilleFixtures extends Fixture
{
    public const LIEU_VILLE1 = 'ville1';
    public const LIEU_VILLE2 = 'ville2';
    public const LIEU_VILLE3 = 'ville3';

    public function load(ObjectManager $manager): void
    {
        $ville1 = new Ville();
        $ville1->setNom('Rezé');
        $ville1->setCodePostal(44000);
        $manager->persist($ville1);

        $ville2 = new Ville();
        $ville2->setNom('Rennes');
        $ville2->setCodePostal(35000);
        $manager->persist($ville2);

        $ville3 = new Ville();
        $ville3->setNom('La Roche sur Yon');
        $ville3->setCodePostal(85000);
        $manager->persist($ville3);

        $manager->flush();

        $this->addReference(self::LIEU_VILLE1, $ville1);
        $this->addReference(self::LIEU_VILLE2, $ville2);
        $this->addReference(self::LIEU_VILLE3, $ville3);
    }
}
