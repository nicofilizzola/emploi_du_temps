<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210312004511 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'create subjects entity';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE subject (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, ppn VARCHAR(255) NOT NULL, ca VARCHAR(255) DEFAULT NULL, semester SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE attribution CHANGE session_id session_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE subject');
        $this->addSql('ALTER TABLE attribution CHANGE session_id session_id INT NOT NULL');
    }
}
