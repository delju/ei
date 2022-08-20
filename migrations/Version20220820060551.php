<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220820060551 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animals_get_on DROP FOREIGN KEY FK_E606FCAD132B9E58');
        $this->addSql('ALTER TABLE animals_get_on DROP FOREIGN KEY FK_E606FCADFA646488');
        $this->addSql('ALTER TABLE animals_get_on ADD CONSTRAINT FK_E606FCAD132B9E58 FOREIGN KEY (animals_id) REFERENCES animals (id)');
        $this->addSql('ALTER TABLE animals_get_on ADD CONSTRAINT FK_E606FCADFA646488 FOREIGN KEY (get_on_id) REFERENCES get_on (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

        $this->addSql('ALTER TABLE animals_get_on DROP FOREIGN KEY FK_E606FCAD132B9E58');
        $this->addSql('ALTER TABLE animals_get_on DROP FOREIGN KEY FK_E606FCADFA646488');
        $this->addSql('ALTER TABLE animals_get_on ADD CONSTRAINT FK_E606FCAD132B9E58 FOREIGN KEY (animals_id) REFERENCES animals (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE animals_get_on ADD CONSTRAINT FK_E606FCADFA646488 FOREIGN KEY (get_on_id) REFERENCES get_on (id) ON DELETE CASCADE');
    }
}
