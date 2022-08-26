<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220826124320 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE lieu CHANGE latitude latitude DOUBLE PRECISION DEFAULT NULL, CHANGE longitude longitude DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE sortie CHANGE date_limite_inscription date_limite_inscription DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE date_heure_debut date_heure_debut DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE duree duree VARCHAR(255) NOT NULL COMMENT \'(DC2Type:dateinterval)\'');
        $this->addSql('ALTER TABLE utilisateur CHANGE campus_id campus_id INT DEFAULT NULL, CHANGE actif actif TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('ALTER TABLE lieu CHANGE latitude latitude DOUBLE PRECISION NOT NULL, CHANGE longitude longitude DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE sortie CHANGE date_limite_inscription date_limite_inscription DATETIME NOT NULL, CHANGE date_heure_debut date_heure_debut DATETIME NOT NULL, CHANGE duree duree DATETIME NOT NULL');
        $this->addSql('ALTER TABLE utilisateur CHANGE campus_id campus_id INT NOT NULL, CHANGE actif actif TINYINT(1) NOT NULL');
    }
}
