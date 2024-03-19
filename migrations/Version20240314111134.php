<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240314111134 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE courses_student_classes (courses_id INT NOT NULL, student_classes_id INT NOT NULL, INDEX IDX_ACFF0D9AF9295384 (courses_id), INDEX IDX_ACFF0D9A73FDB6C5 (student_classes_id), PRIMARY KEY(courses_id, student_classes_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE courses_student_classes ADD CONSTRAINT FK_ACFF0D9AF9295384 FOREIGN KEY (courses_id) REFERENCES courses (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE courses_student_classes ADD CONSTRAINT FK_ACFF0D9A73FDB6C5 FOREIGN KEY (student_classes_id) REFERENCES student_classes (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE courses_student_classes DROP FOREIGN KEY FK_ACFF0D9AF9295384');
        $this->addSql('ALTER TABLE courses_student_classes DROP FOREIGN KEY FK_ACFF0D9A73FDB6C5');
        $this->addSql('DROP TABLE courses_student_classes');
    }
}
