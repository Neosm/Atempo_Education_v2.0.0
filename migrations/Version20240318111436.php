<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240318111436 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE courses CHANGE comment comment LONGTEXT DEFAULT NULL, CHANGE objectives objectives LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE rooms CHANGE equipments equipments LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE courses CHANGE comment comment LONGTEXT NOT NULL, CHANGE objectives objectives LONGTEXT NOT NULL');
        $this->addSql('ALTER TABLE rooms CHANGE equipments equipments LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
    }
}
