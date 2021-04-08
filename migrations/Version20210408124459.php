<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210408124459 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE preference ADD start_week INT NOT NULL, ADD end_week INT DEFAULT NULL, ADD except_start_week INT DEFAULT NULL, ADD except_end_week INT DEFAULT NULL, ADD weekdays LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', ADD times LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', DROP datetime');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE preference ADD datetime DATETIME NOT NULL, DROP start_week, DROP end_week, DROP except_start_week, DROP except_end_week, DROP weekdays, DROP times');
    }
}
