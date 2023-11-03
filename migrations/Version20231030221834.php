<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231030221834 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE user_boutique (user_id INT NOT NULL, boutique_id INT NOT NULL, INDEX IDX_41AFD854A76ED395 (user_id), INDEX IDX_41AFD854AB677BE6 (boutique_id), PRIMARY KEY(user_id, boutique_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user_boutique ADD CONSTRAINT FK_41AFD854A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_boutique ADD CONSTRAINT FK_41AFD854AB677BE6 FOREIGN KEY (boutique_id) REFERENCES boutique (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_boutique DROP FOREIGN KEY FK_41AFD854A76ED395');
        $this->addSql('ALTER TABLE user_boutique DROP FOREIGN KEY FK_41AFD854AB677BE6');
        $this->addSql('DROP TABLE user_boutique');
    }
}
