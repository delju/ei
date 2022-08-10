<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220810140745 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animals ADD races_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE animals ADD CONSTRAINT FK_966C69DD99AE984C FOREIGN KEY (races_id) REFERENCES races (id)');
        $this->addSql('CREATE INDEX IDX_966C69DD99AE984C ON animals (races_id)');
        $this->addSql('ALTER TABLE races ADD species_id INT NOT NULL');
        $this->addSql('ALTER TABLE races ADD CONSTRAINT FK_5DBD1EC9B2A1D860 FOREIGN KEY (species_id) REFERENCES species (id)');
        $this->addSql('CREATE INDEX IDX_5DBD1EC9B2A1D860 ON races (species_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animals DROP FOREIGN KEY FK_966C69DD99AE984C');
        $this->addSql('DROP INDEX IDX_966C69DD99AE984C ON animals');
        $this->addSql('ALTER TABLE animals DROP races_id');
        $this->addSql('ALTER TABLE races DROP FOREIGN KEY FK_5DBD1EC9B2A1D860');
        $this->addSql('DROP INDEX IDX_5DBD1EC9B2A1D860 ON races');
        $this->addSql('ALTER TABLE races DROP species_id');
    }
}
