<?php

namespace App\DataFixtures;

use App\Entity\Utilisateur;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture implements DependentFixtureInterface
{
    public const ORGANISATEUR1 = 'organisateur1';
    public const ORGANISATEUR2 = 'organisateur2';
    public const UTILISATEUR1 = 'utilisateur1';

    private UserPasswordHasherInterface $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user1 = new Utilisateur();
        $user1->setEmail('yo@mail.com');
        $user1->setPseudo('yo30');
        $user1->setNom('DOE');
        $user1->setPrenom('Yoyo');
        $user1->setPassword($this->userPasswordHasher->hashPassword(
            $user1,
            '123456'));
        $user1->setTelephone('0102030405');
        $user1->setCampus($this->getReference(CampusFixtures::CAMPUS_USER1));
        $user1->setActif(1);
        $user1->addRole(Utilisateur::ROLE_ACTIF);
        $user1->addRole(Utilisateur::ROLE_USER);
        $user1->setPhoto('imgProfilUser1.jpg');
        $manager->persist($user1);

        $user2 = new Utilisateur();
        $user2->setEmail('jane@mail.com');
        $user2->setPseudo('janie22');
        $user2->setNom('DODO');
        $user2->setPrenom('Jane');
        $user2->setPassword($this->userPasswordHasher->hashPassword(
            $user2,
            '123456'));
        $user2->setTelephone('0602030406');
        $user2->setCampus($this->getReference(CampusFixtures::CAMPUS_USER2));
        $user2->setActif(0);
        $manager->persist($user2);

        $user3 = new Utilisateur();
        $user3->setEmail('summer@mail.com');
        $user3->setPseudo('summer');
        $user3->setNom('DOE');
        $user3->setPrenom('Summer');
        $user3->setPassword($this->userPasswordHasher->hashPassword(
            $user3,
            '123456'));
        $user3->setTelephone('0104050405');
        $user3->setCampus($this->getReference(CampusFixtures::CAMPUS_USER3));
        $user3->setRoles(["ROLE_ADMIN", "ROLE_ACTIF"]);
        $user3->addRole(Utilisateur::ROLE_ACTIF);
        $user3->addRole(Utilisateur::ROLE_USER);
        $user3->addRole(Utilisateur::ROLE_ADMIN);
        $user3->setActif(1);
        $user3->setAdministrateur(1);
        $user3->setPhoto('imgProfilUser1.jpg');
        $manager->persist($user3);

        $manager->flush();

        $this->addReference(self::ORGANISATEUR1, $user1);
        $this->addReference(self::ORGANISATEUR2, $user2);
        $this->addReference(self::UTILISATEUR1, $user3);
    }

    public function getDependencies()
    {
        return [
            CampusFixtures::class
        ];
    }
}
