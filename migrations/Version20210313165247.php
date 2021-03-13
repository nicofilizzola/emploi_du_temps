<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210313165247 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'include preferences entity';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE preference (id INT AUTO_INCREMENT NOT NULL, session_id INT NOT NULL, start DATETIME NOT NULL, until DATETIME NOT NULL, state TINYINT(1) NOT NULL, INDEX IDX_5D69B053613FECDF (session_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE preference_subject (preference_id INT NOT NULL, subject_id INT NOT NULL, INDEX IDX_9B22316AD81022C0 (preference_id), INDEX IDX_9B22316A23EDC87 (subject_id), PRIMARY KEY(preference_id, subject_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE preference ADD CONSTRAINT FK_5D69B053613FECDF FOREIGN KEY (session_id) REFERENCES session (id)');
        $this->addSql('ALTER TABLE preference_subject ADD CONSTRAINT FK_9B22316AD81022C0 FOREIGN KEY (preference_id) REFERENCES preference (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE preference_subject ADD CONSTRAINT FK_9B22316A23EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE preference_subject DROP FOREIGN KEY FK_9B22316AD81022C0');
        $this->addSql('DROP TABLE preference');
        $this->addSql('DROP TABLE preference_subject');
    }
}
