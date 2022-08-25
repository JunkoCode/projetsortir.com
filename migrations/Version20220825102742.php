<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220825102742 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lieu CHANGE latitude latitude DOUBLE PRECISION DEFAULT NULL, CHANGE longitude longitude DOUBLE PRECISION DEFAULT NULL');
        $this->addSql('ALTER TABLE sortie CHANGE duree duree VARCHAR(255) DEFAULT NULL COMMENT \'(DC2Type:dateinterval)\'');
        $this->addSql('ALTER TABLE utilisateur CHANGE campus_id campus_id INT DEFAULT NULL, CHANGE actif actif TINYINT(1) DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lieu CHANGE latitude latitude DOUBLE PRECISION NOT NULL, CHANGE longitude longitude DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE sortie CHANGE duree duree DATETIME NOT NULL');
        $this->addSql('ALTER TABLE utilisateur CHANGE campus_id campus_id INT NOT NULL, CHANGE actif actif TINYINT(1) NOT NULL');
    }
}
