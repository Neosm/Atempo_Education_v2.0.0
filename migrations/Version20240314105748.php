<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240314105748 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE courses_users (courses_id INT NOT NULL, users_id INT NOT NULL, INDEX IDX_389EDBD0F9295384 (courses_id), INDEX IDX_389EDBD067B3B43D (users_id), PRIMARY KEY(courses_id, users_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE courses_users ADD CONSTRAINT FK_389EDBD0F9295384 FOREIGN KEY (courses_id) REFERENCES courses (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE courses_users ADD CONSTRAINT FK_389EDBD067B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE courses ADD teacher_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE courses ADD CONSTRAINT FK_A9A55A4C41807E1D FOREIGN KEY (teacher_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_A9A55A4C41807E1D ON courses (teacher_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE courses_users DROP FOREIGN KEY FK_389EDBD0F9295384');
        $this->addSql('ALTER TABLE courses_users DROP FOREIGN KEY FK_389EDBD067B3B43D');
        $this->addSql('DROP TABLE courses_users');
        $this->addSql('ALTER TABLE courses DROP FOREIGN KEY FK_A9A55A4C41807E1D');
        $this->addSql('DROP INDEX IDX_A9A55A4C41807E1D ON courses');
        $this->addSql('ALTER TABLE courses DROP teacher_id');
    }
}
