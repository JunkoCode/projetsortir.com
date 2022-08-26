<?php

namespace App\DataFixtures;

use App\Entity\Sortie;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SortieFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $sortie1 = new Sortie();
        $sortie1->setNom('Théatre');
        $sortie1->setDateLimiteInscription(new \DateTimeImmutable("2022-08-26 15:30:00"));
        $sortie1->setDateHeureDebut(new \DateTimeImmutable("2022-08-26 18:30:00"));
        $sortie1->setDuree(new \DateInterval("02H:30i:00s"));
        $sortie1->setInfoSortie("test1");
        $sortie1->setNombreInscriptionMax(10);
        $sortie1->setOrganisateur($this->getReference());
        $sortie1->setEtat($this->getReference(EtatFixtures::ETAT_SORTIE1));
        $sortie1->setLieu();
        $manager->persist($sortie1);

        $sortie2 = new Sortie();
        $sortie2->setNom('Apéro');
        $sortie2->setDateLimiteInscription();
        $sortie2->setDateHeureDebut();
        $sortie2->setDuree();
        $sortie2->setInfoSortie();
        $sortie2->setNombreInscriptionMax();
        $sortie2->setOrganisateur();
        $sortie2->setEtat();
        $sortie2->setLieu();
        $manager->persist($sortie2);

        $sortie3 = new Sortie();
        $sortie3->setNom('Apéro V&B');
        $sortie3->setDateLimiteInscription();
        $sortie3->setDateHeureDebut();
        $sortie3->setDuree();
        $sortie3->setInfoSortie();
        $sortie3->setNombreInscriptionMax();
        $sortie3->setOrganisateur();
        $sortie3->setEtat();
        $sortie3->setLieu();
        $manager->persist($sortie3);

        $manager->flush();
    }
}
