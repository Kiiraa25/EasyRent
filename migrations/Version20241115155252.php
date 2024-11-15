<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241115155252 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registration_certificate CHANGE back_image_path back_image_path VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE vehicle CHANGE price_per_day price_per_day DOUBLE PRECISION NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE registration_certificate CHANGE back_image_path back_image_path VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE vehicle CHANGE price_per_day price_per_day NUMERIC(10, 2) NOT NULL');
    }
}
