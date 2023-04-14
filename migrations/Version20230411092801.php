<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230411092801 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE gallery (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE hours CHANGE lunch_opening lunch_opening TIME DEFAULT NULL, CHANGE lunch_closing lunch_closing TIME DEFAULT NULL, CHANGE dinner_opening dinner_opening TIME DEFAULT NULL, CHANGE dinner_closing dinner_closing TIME DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE gallery');
        $this->addSql('ALTER TABLE hours CHANGE lunch_opening lunch_opening TIME NOT NULL, CHANGE lunch_closing lunch_closing TIME NOT NULL, CHANGE dinner_opening dinner_opening TIME NOT NULL, CHANGE dinner_closing dinner_closing TIME NOT NULL');
    }
}
