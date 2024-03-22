<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240319160645 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE evaluations_student_classes (evaluations_id INT NOT NULL, student_classes_id INT NOT NULL, INDEX IDX_A29027FE788B35D6 (evaluations_id), INDEX IDX_A29027FE73FDB6C5 (student_classes_id), PRIMARY KEY(evaluations_id, student_classes_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE evaluations_student_classes ADD CONSTRAINT FK_A29027FE788B35D6 FOREIGN KEY (evaluations_id) REFERENCES evaluations (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evaluations_student_classes ADD CONSTRAINT FK_A29027FE73FDB6C5 FOREIGN KEY (student_classes_id) REFERENCES student_classes (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE evaluations_student_classes DROP FOREIGN KEY FK_A29027FE788B35D6');
        $this->addSql('ALTER TABLE evaluations_student_classes DROP FOREIGN KEY FK_A29027FE73FDB6C5');
        $this->addSql('DROP TABLE evaluations_student_classes');
    }
}
