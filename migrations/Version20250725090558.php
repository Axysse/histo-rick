<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250725090558 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE events (id INT AUTO_INCREMENT NOT NULL, event_type_id INT DEFAULT NULL, event_period_id INT DEFAULT NULL, title VARCHAR(255) NOT NULL, year INT NOT NULL, short_text LONGTEXT DEFAULT NULL, event_text LONGTEXT DEFAULT NULL, event_picture VARCHAR(255) DEFAULT NULL, longitude DOUBLE PRECISION DEFAULT NULL, latitude DOUBLE PRECISION DEFAULT NULL, INDEX IDX_5387574A401B253C (event_type_id), INDEX IDX_5387574A6A1E44E3 (event_period_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE events ADD CONSTRAINT FK_5387574A401B253C FOREIGN KEY (event_type_id) REFERENCES event_type (id)');
        $this->addSql('ALTER TABLE events ADD CONSTRAINT FK_5387574A6A1E44E3 FOREIGN KEY (event_period_id) REFERENCES event_period (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE events DROP FOREIGN KEY FK_5387574A401B253C');
        $this->addSql('ALTER TABLE events DROP FOREIGN KEY FK_5387574A6A1E44E3');
        $this->addSql('DROP TABLE events');
    }
}
