<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210320221358 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE preference DROP FOREIGN KEY FK_5D69B05323EDC87');
        $this->addSql('DROP INDEX IDX_5D69B05323EDC87 ON preference');
        $this->addSql('ALTER TABLE preference DROP subject_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE preference ADD subject_id INT NOT NULL');
        $this->addSql('ALTER TABLE preference ADD CONSTRAINT FK_5D69B05323EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id)');
        $this->addSql('CREATE INDEX IDX_5D69B05323EDC87 ON preference (subject_id)');
    }
}
