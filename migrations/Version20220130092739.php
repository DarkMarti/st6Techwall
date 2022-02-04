<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220130092739 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE persona ADD created_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE persona ADD CONSTRAINT FK_51E5B69BB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_51E5B69BB03A8386 ON persona (created_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE persona DROP FOREIGN KEY FK_51E5B69BB03A8386');
        $this->addSql('DROP INDEX IDX_51E5B69BB03A8386 ON persona');
        $this->addSql('ALTER TABLE persona DROP created_by_id');
    }
}
