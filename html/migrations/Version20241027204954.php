<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241027204954 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE job (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(1000) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE volunteer (id INT AUTO_INCREMENT NOT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE volunteers_jobs (volunteer_id INT NOT NULL, job_id INT NOT NULL, INDEX IDX_B7B605618EFAB6B1 (volunteer_id), INDEX IDX_B7B60561BE04EA9 (job_id), PRIMARY KEY(volunteer_id, job_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE volunteers_jobs ADD CONSTRAINT FK_B7B605618EFAB6B1 FOREIGN KEY (volunteer_id) REFERENCES volunteer (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE volunteers_jobs ADD CONSTRAINT FK_B7B60561BE04EA9 FOREIGN KEY (job_id) REFERENCES job (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE volunteers_jobs DROP FOREIGN KEY FK_B7B605618EFAB6B1');
        $this->addSql('ALTER TABLE volunteers_jobs DROP FOREIGN KEY FK_B7B60561BE04EA9');
        $this->addSql('DROP TABLE job');
        $this->addSql('DROP TABLE volunteer');
        $this->addSql('DROP TABLE volunteers_jobs');
    }
}
