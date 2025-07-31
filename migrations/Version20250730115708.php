<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250730115708 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE events_event_theme (events_id INT NOT NULL, event_theme_id INT NOT NULL, INDEX IDX_4C041C279D6A1065 (events_id), INDEX IDX_4C041C271FEE9D57 (event_theme_id), PRIMARY KEY(events_id, event_theme_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE events_event_theme ADD CONSTRAINT FK_4C041C279D6A1065 FOREIGN KEY (events_id) REFERENCES events (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE events_event_theme ADD CONSTRAINT FK_4C041C271FEE9D57 FOREIGN KEY (event_theme_id) REFERENCES event_theme (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE events_event_theme DROP FOREIGN KEY FK_4C041C279D6A1065');
        $this->addSql('ALTER TABLE events_event_theme DROP FOREIGN KEY FK_4C041C271FEE9D57');
        $this->addSql('DROP TABLE events_event_theme');
    }
}
