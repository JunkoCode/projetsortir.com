<?php

namespace App\DataFixtures;

use App\Entity\Sortie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

class SortieFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager): void
    {
        $sortie1 = new Sortie();
        $sortie1->setNom('Théatre');
        $sortie1->setDateLimiteInscription(new \DateTimeImmutable("2022-08-26 15:30:00"));
        $sortie1->setDateHeureDebut(new \DateTimeImmutable("2022-08-26 18:30:00"));
        $sortie1->setDuree(new \DateInterval("PT2H30M"));
        $sortie1->setInfoSortie("test1");
        $sortie1->setNombreInscriptionMax(10);
        $sortie1->setOrganisateur($this->getReference(UserFixtures::ORGANISATEUR1));
        $sortie1->setEtat($this->getReference(EtatFixtures::ETAT_SORTIE1));
        $sortie1->setLieu($this->getReference(LieuFixtures::LIEU_SORTIE1));
        $manager->persist($sortie1);

        $sortie2 = new Sortie();
        $sortie2->setNom('Apéro');
        $sortie2->setDateLimiteInscription(new \DateTimeImmutable("2022-08-25 14:00:00"));
        $sortie2->setDateHeureDebut(new \DateTimeImmutable("2022-09-01 18:30:00"));
        $sortie2->setDuree(new \DateInterval("PT4H"));
        $sortie2->setInfoSortie("test2");
        $sortie2->setNombreInscriptionMax(20);
        $sortie2->setOrganisateur($this->getReference(UserFixtures::ORGANISATEUR2));
        $sortie2->setEtat($this->getReference(EtatFixtures::ETAT_SORTIE2));
        $sortie2->setLieu($this->getReference(LieuFixtures::LIEU_SORTIE2));
        $manager->persist($sortie2);

        $sortie3 = new Sortie();
        $sortie3->setNom('Apéro V&B');
        $sortie3->setDateLimiteInscription(new \DateTimeImmutable("2022-08-24 10:30:00"));
        $sortie3->setDateHeureDebut(new \DateTimeImmutable("2022-08-26 19:00:00"));
        $sortie3->setDuree(new \DateInterval("PT3H30M"));
        $sortie3->setInfoSortie("test3");
        $sortie3->setNombreInscriptionMax(15);
        $sortie3->setOrganisateur($this->getReference(UserFixtures::ORGANISATEUR1));
        $sortie3->setEtat($this->getReference(EtatFixtures::ETAT_SORTIE3));
        $sortie3->setLieu($this->getReference(LieuFixtures::LIEU_SORTIE3));
        $manager->persist($sortie3);

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            LieuFixtures::class,
            EtatFixtures::class,
            UserFixtures::class
        ];
    }
}