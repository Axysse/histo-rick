<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250805120357 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE temporal_boundary (id INT AUTO_INCREMENT NOT NULL, political_entity_id INT NOT NULL, start_date INT NOT NULL, end_date INT NOT NULL, geometry LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', INDEX IDX_84D022A06A2D51FF (political_entity_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE temporal_boundary ADD CONSTRAINT FK_84D022A06A2D51FF FOREIGN KEY (political_entity_id) REFERENCES political_entity (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE temporal_boundary DROP FOREIGN KEY FK_84D022A06A2D51FF');
        $this->addSql('DROP TABLE temporal_boundary');
    }
}
