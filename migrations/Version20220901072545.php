<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220901072545 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sortie CHANGE date_limite_inscription date_limite_inscription DATETIME NOT NULL, CHANGE date_heure_debut date_heure_debut DATETIME NOT NULL, CHANGE info_sortie info_sortie LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE utilisateur DROP roles, CHANGE administrateur administrateur TINYINT(1) NOT NULL, CHANGE actif actif TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sortie CHANGE date_limite_inscription date_limite_inscription DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE date_heure_debut date_heure_debut DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', CHANGE info_sortie info_sortie LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE utilisateur ADD roles JSON NOT NULL, CHANGE administrateur administrateur TINYINT(1) DEFAULT NULL, CHANGE actif actif TINYINT(1) DEFAULT NULL');
    }
}
