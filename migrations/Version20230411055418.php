<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230411055418 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hours DROP FOREIGN KEY FK_8A1ABD8D9C24126');
        $this->addSql('DROP INDEX IDX_8A1ABD8D9C24126 ON hours');
        $this->addSql('ALTER TABLE hours ADD name VARCHAR(255) NOT NULL, DROP day_id, CHANGE lunch_opening lunch_opening TIME NOT NULL, CHANGE lunch_closing lunch_closing TIME NOT NULL, CHANGE dinner_opening dinner_opening TIME NOT NULL, CHANGE dinner_closing dinner_closing TIME NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE hours ADD day_id INT NOT NULL, DROP name, CHANGE lunch_opening lunch_opening TIME DEFAULT NULL, CHANGE lunch_closing lunch_closing TIME DEFAULT NULL, CHANGE dinner_opening dinner_opening TIME DEFAULT NULL, CHANGE dinner_closing dinner_closing TIME DEFAULT NULL');
        $this->addSql('ALTER TABLE hours ADD CONSTRAINT FK_8A1ABD8D9C24126 FOREIGN KEY (day_id) REFERENCES day (id)');
        $this->addSql('CREATE INDEX IDX_8A1ABD8D9C24126 ON hours (day_id)');
    }
}
