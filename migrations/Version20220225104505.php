<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220225104505 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE seance DROP FOREIGN KEY FK_DF7DFD0EDC304035');
        $this->addSql('CREATE TABLE salles (id INT AUTO_INCREMENT NOT NULL, num_salles INT NOT NULL, etat TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE salle');
        $this->addSql('DROP INDEX IDX_DF7DFD0EDC304035 ON seance');
        $this->addSql('ALTER TABLE seance CHANGE salle_id salles_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE seance ADD CONSTRAINT FK_DF7DFD0EB11E4946 FOREIGN KEY (salles_id) REFERENCES salles (id)');
        $this->addSql('CREATE INDEX IDX_DF7DFD0EB11E4946 ON seance (salles_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE seance DROP FOREIGN KEY FK_DF7DFD0EB11E4946');
        $this->addSql('CREATE TABLE salle (id INT AUTO_INCREMENT NOT NULL, disponible TINYINT(1) NOT NULL, num_salle INT DEFAULT 0 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE salles');
        $this->addSql('ALTER TABLE film CHANGE title title VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE genre genre VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE realisateur realisateur VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE image image VARCHAR(255) DEFAULT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE synopsis synopsis LONGTEXT NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE status status VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('DROP INDEX IDX_DF7DFD0EB11E4946 ON seance');
        $this->addSql('ALTER TABLE seance CHANGE lang lang VARCHAR(255) NOT NULL COLLATE `utf8mb4_unicode_ci`, CHANGE salles_id salle_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE seance ADD CONSTRAINT FK_DF7DFD0EDC304035 FOREIGN KEY (salle_id) REFERENCES salle (id)');
        $this->addSql('CREATE INDEX IDX_DF7DFD0EDC304035 ON seance (salle_id)');
    }
}
