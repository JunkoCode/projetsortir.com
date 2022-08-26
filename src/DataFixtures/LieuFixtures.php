<?php

namespace App\DataFixtures;

use App\Entity\Lieu;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class LieuFixtures extends Fixture implements DependentFixtureInterface
{
    public const LIEU_SORTIE1='lieu1';
    public const LIEU_SORTIE2='lieu2';
    public const LIEU_SORTIE3='lieu3';

    public function load(ObjectManager $manager): void
    {
        $lieu1 = new Lieu();
        $lieu1->setNom('La Soufflerie');
        $lieu1->setRue('2 Avenue de Bretagne');
        $lieu1->setLatitude(47.185447);
        $lieu1->setLongitude(-1.554795);
        $lieu1->setVille($this->getReference(VilleFixtures::LIEU_VILLE1));
        $manager->persist($lieu1);

        $lieu2 = new Lieu();
        $lieu2->setNom('Le Irish Pub');
        $lieu2->setRue('14 Rue Saint-Hélier');
        $lieu2->setLatitude(48.107514);
        $lieu2->setLongitude(-1.672703);
        $lieu2->setVille($this->getReference(VilleFixtures::LIEU_VILLE2));
        $manager->persist($lieu2);

        $lieu3 = new Lieu();
        $lieu3->setNom('V and B');
        $lieu3->setRue('10 Rue Henri Aucher');
        $lieu3->setLatitude(46.6575603);
        $lieu3->setLongitude(-1.4422246);
        $lieu3->setVille($this->getReference(VilleFixtures::LIEU_VILLE3));
        $manager->persist($lieu3);

        $manager->flush();

        $this->addReference(self::LIEU_SORTIE1, $lieu1);
        $this->addReference(self::LIEU_SORTIE2, $lieu2);
        $this->addReference(self::LIEU_SORTIE3, $lieu3);
    }

    public function getDependencies()
    {
        return [
            VilleFixtures::class,
        ];
    }
}
