<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231101220638 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE boutique ADD horaire_debut TIME NOT NULL, ADD horaire_fin TIME NOT NULL, DROP horaire_ouverture, DROP horaire_fermeture');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE boutique ADD horaire_ouverture VARCHAR(255) NOT NULL, ADD horaire_fermeture VARCHAR(255) NOT NULL, DROP horaire_debut, DROP horaire_fin');
    }
}
