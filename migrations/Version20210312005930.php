<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210312005930 extends AbstractMigration
{
    public function getDescription() : string
    {
        return 'recreate db';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attribution (id INT AUTO_INCREMENT NOT NULL, session_id INT NOT NULL, subject_id INT NOT NULL, cm_amount SMALLINT DEFAULT NULL, td_amount SMALLINT DEFAULT NULL, tp_amount SMALLINT DEFAULT NULL, INDEX IDX_C751ED49613FECDF (session_id), INDEX IDX_C751ED4923EDC87 (subject_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE day (id INT AUTO_INCREMENT NOT NULL, session_id INT NOT NULL, date DATE NOT NULL, INDEX IDX_E5A02990613FECDF (session_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, abbreviation VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE event_day (event_id INT NOT NULL, day_id INT NOT NULL, INDEX IDX_F46FEC4371F7E88B (event_id), INDEX IDX_F46FEC439C24126 (day_id), PRIMARY KEY(event_id, day_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE session (id INT AUTO_INCREMENT NOT NULL, start DATE NOT NULL, until DATE NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subject (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, ppn VARCHAR(255) NOT NULL, ca VARCHAR(255) DEFAULT NULL, semester SMALLINT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE attribution ADD CONSTRAINT FK_C751ED49613FECDF FOREIGN KEY (session_id) REFERENCES session (id)');
        $this->addSql('ALTER TABLE attribution ADD CONSTRAINT FK_C751ED4923EDC87 FOREIGN KEY (subject_id) REFERENCES subject (id)');
        $this->addSql('ALTER TABLE day ADD CONSTRAINT FK_E5A02990613FECDF FOREIGN KEY (session_id) REFERENCES session (id)');
        $this->addSql('ALTER TABLE event_day ADD CONSTRAINT FK_F46FEC4371F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE event_day ADD CONSTRAINT FK_F46FEC439C24126 FOREIGN KEY (day_id) REFERENCES day (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event_day DROP FOREIGN KEY FK_F46FEC439C24126');
        $this->addSql('ALTER TABLE event_day DROP FOREIGN KEY FK_F46FEC4371F7E88B');
        $this->addSql('ALTER TABLE attribution DROP FOREIGN KEY FK_C751ED49613FECDF');
        $this->addSql('ALTER TABLE day DROP FOREIGN KEY FK_E5A02990613FECDF');
        $this->addSql('ALTER TABLE attribution DROP FOREIGN KEY FK_C751ED4923EDC87');
        $this->addSql('DROP TABLE attribution');
        $this->addSql('DROP TABLE day');
        $this->addSql('DROP TABLE event');
        $this->addSql('DROP TABLE event_day');
        $this->addSql('DROP TABLE session');
        $this->addSql('DROP TABLE subject');
    }
}
