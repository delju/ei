<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220823100526 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adoption ADD gallery_id INT NOT NULL');
        $this->addSql('ALTER TABLE adoption ADD CONSTRAINT FK_EDDEB6A94E7AF8F FOREIGN KEY (gallery_id) REFERENCES gallery (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_EDDEB6A94E7AF8F ON adoption (gallery_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE adoption DROP FOREIGN KEY FK_EDDEB6A94E7AF8F');
        $this->addSql('DROP INDEX UNIQ_EDDEB6A94E7AF8F ON adoption');
        $this->addSql('ALTER TABLE adoption DROP gallery_id');
    }
}
