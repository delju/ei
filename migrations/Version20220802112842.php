<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220802112842 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE animals_get_on (animals_id INT NOT NULL, get_on_id INT NOT NULL, INDEX IDX_E606FCAD132B9E58 (animals_id), INDEX IDX_E606FCADFA646488 (get_on_id), PRIMARY KEY(animals_id, get_on_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE animals_get_on ADD CONSTRAINT FK_E606FCAD132B9E58 FOREIGN KEY (animals_id) REFERENCES animals (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE animals_get_on ADD CONSTRAINT FK_E606FCADFA646488 FOREIGN KEY (get_on_id) REFERENCES get_on (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE animals ADD arrival_reason_id INT NOT NULL, ADD death_id INT DEFAULT NULL, ADD adoption_id INT DEFAULT NULL, ADD come_back_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE animals ADD CONSTRAINT FK_966C69DDEDBEBBE6 FOREIGN KEY (arrival_reason_id) REFERENCES arrival_reason (id)');
        $this->addSql('ALTER TABLE animals ADD CONSTRAINT FK_966C69DD40654B54 FOREIGN KEY (death_id) REFERENCES death (id)');
        $this->addSql('ALTER TABLE animals ADD CONSTRAINT FK_966C69DD631C55DF FOREIGN KEY (adoption_id) REFERENCES adoption (id)');
        $this->addSql('ALTER TABLE animals ADD CONSTRAINT FK_966C69DD2DEA50A6 FOREIGN KEY (come_back_id) REFERENCES come_back (id)');
        $this->addSql('CREATE INDEX IDX_966C69DDEDBEBBE6 ON animals (arrival_reason_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_966C69DD40654B54 ON animals (death_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_966C69DD631C55DF ON animals (adoption_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_966C69DD2DEA50A6 ON animals (come_back_id)');
        $this->addSql('ALTER TABLE treatment ADD animals_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE treatment ADD CONSTRAINT FK_98013C31132B9E58 FOREIGN KEY (animals_id) REFERENCES animals (id)');
        $this->addSql('CREATE INDEX IDX_98013C31132B9E58 ON treatment (animals_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE animals_get_on');
        $this->addSql('ALTER TABLE animals DROP FOREIGN KEY FK_966C69DDEDBEBBE6');
        $this->addSql('ALTER TABLE animals DROP FOREIGN KEY FK_966C69DD40654B54');
        $this->addSql('ALTER TABLE animals DROP FOREIGN KEY FK_966C69DD631C55DF');
        $this->addSql('ALTER TABLE animals DROP FOREIGN KEY FK_966C69DD2DEA50A6');
        $this->addSql('DROP INDEX IDX_966C69DDEDBEBBE6 ON animals');
        $this->addSql('DROP INDEX UNIQ_966C69DD40654B54 ON animals');
        $this->addSql('DROP INDEX UNIQ_966C69DD631C55DF ON animals');
        $this->addSql('DROP INDEX UNIQ_966C69DD2DEA50A6 ON animals');
        $this->addSql('ALTER TABLE animals DROP arrival_reason_id, DROP death_id, DROP adoption_id, DROP come_back_id');
        $this->addSql('ALTER TABLE treatment DROP FOREIGN KEY FK_98013C31132B9E58');
        $this->addSql('DROP INDEX IDX_98013C31132B9E58 ON treatment');
        $this->addSql('ALTER TABLE treatment DROP animals_id');
    }
}
