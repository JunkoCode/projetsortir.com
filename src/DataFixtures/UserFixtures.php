<?php

namespace App\DataFixtures;

use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class UserFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $user1 = new Utilisateur();
        $user1->setEmail('yo@mail.com');
        $user1->setPseudo('yo30');
        $user1->setNom('DOE');
        $user1->setPrenom('Yoyo');
        $user1->setPassword(123456);
        $user1->setTelephone(0102030405);
        $user1->setCampus($this->getReference(CampusFixtures::CAMPUS_USER1));


        $manager->persist($user1);

        $manager->flush();
    }
}
