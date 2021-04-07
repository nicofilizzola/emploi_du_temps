<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210407092151 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE equipment (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipment_request (id INT AUTO_INCREMENT NOT NULL, session_id INT NOT NULL, user_id INT NOT NULL, equipment_id INT NOT NULL, subject_id INT NOT NULL, tds LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', tps LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', note VARCHAR(255) DEFAULT NULL, INDEX IDX_4DEC4070613FECDF (session_id), INDEX IDX_4DEC4070A76ED395 (user_id), INDEX IDX_4DEC4070517FE9FE (equipment_id), INDEX IDX_4DEC407023EDC87 (subject_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE equipment_request ADD CONSTRAINT FK_4DEC4070613FECDF FOREIGN KEY (session_id) REFERENCES session (id)');
        $this->addSql('ALTER TABLE equipment_request ADD CONSTRAINT FK_4DEC4070A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE equipment_request ADD CONSTRAINT FK_4DEC4070517FE9FE FOREIGN KEY (equipment_id) REFERENCES equipment (id)');
        $this->addSql('ALTER TABLE equipment_request ADD CONSTRAINT FK_4DEC407023EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipment_request DROP FOREIGN KEY FK_4DEC4070517FE9FE');
        $this->addSql('DROP TABLE equipment');
        $this->addSql('DROP TABLE equipment_request');
    }
}
