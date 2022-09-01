<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220830122630 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE treatment ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE treatment ADD CONSTRAINT FK_98013C31A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_98013C31A76ED395 ON treatment (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE treatment DROP FOREIGN KEY FK_98013C31A76ED395');
        $this->addSql('DROP INDEX IDX_98013C31A76ED395 ON treatment');
        $this->addSql('ALTER TABLE treatment DROP user_id');
    }
}
