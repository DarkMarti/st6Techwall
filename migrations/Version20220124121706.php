<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220124121706 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE hobby (id INT AUTO_INCREMENT NOT NULL, designation VARCHAR(70) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job (id INT AUTO_INCREMENT NOT NULL, designation VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE persona_hobby (persona_id INT NOT NULL, hobby_id INT NOT NULL, INDEX IDX_D7A5F112F5F88DB9 (persona_id), INDEX IDX_D7A5F112322B2123 (hobby_id), PRIMARY KEY(persona_id, hobby_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE profile (id INT AUTO_INCREMENT NOT NULL, url VARCHAR(255) NOT NULL, rs VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE persona_hobby ADD CONSTRAINT FK_D7A5F112F5F88DB9 FOREIGN KEY (persona_id) REFERENCES persona (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE persona_hobby ADD CONSTRAINT FK_D7A5F112322B2123 FOREIGN KEY (hobby_id) REFERENCES hobby (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE persona ADD profile_id INT DEFAULT NULL, ADD job_id INT DEFAULT NULL, DROP job');
        $this->addSql('ALTER TABLE persona ADD CONSTRAINT FK_51E5B69BCCFA12B8 FOREIGN KEY (profile_id) REFERENCES profile (id)');
        $this->addSql('ALTER TABLE persona ADD CONSTRAINT FK_51E5B69BBE04EA9 FOREIGN KEY (job_id) REFERENCES job (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_51E5B69BCCFA12B8 ON persona (profile_id)');
        $this->addSql('CREATE INDEX IDX_51E5B69BBE04EA9 ON persona (job_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE persona_hobby DROP FOREIGN KEY FK_D7A5F112322B2123');
        $this->addSql('ALTER TABLE persona DROP FOREIGN KEY FK_51E5B69BBE04EA9');
        $this->addSql('ALTER TABLE persona DROP FOREIGN KEY FK_51E5B69BCCFA12B8');
        $this->addSql('DROP TABLE hobby');
        $this->addSql('DROP TABLE job');
        $this->addSql('DROP TABLE persona_hobby');
        $this->addSql('DROP TABLE profile');
        $this->addSql('DROP INDEX UNIQ_51E5B69BCCFA12B8 ON persona');
        $this->addSql('DROP INDEX IDX_51E5B69BBE04EA9 ON persona');
        $this->addSql('ALTER TABLE persona ADD job VARCHAR(50) CHARACTER SET utf8mb4 DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, DROP profile_id, DROP job_id');
    }
}
