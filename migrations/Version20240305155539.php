<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240305155539 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE absences (id INT AUTO_INCREMENT NOT NULL, student_id INT DEFAULT NULL, course_id INT DEFAULT NULL, evaluation_id INT DEFAULT NULL, school_id INT DEFAULT NULL, INDEX IDX_F9C0EFFFCB944F1A (student_id), INDEX IDX_F9C0EFFF591CC992 (course_id), INDEX IDX_F9C0EFFF456C5646 (evaluation_id), INDEX IDX_F9C0EFFFC32A47EE (school_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, school_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_3AF34668727ACA70 (parent_id), INDEX IDX_3AF34668C32A47EE (school_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE courses (id INT AUTO_INCREMENT NOT NULL, room_id INT DEFAULT NULL, discipline_id INT DEFAULT NULL, school_id INT DEFAULT NULL, parent_id INT DEFAULT NULL, start DATETIME NOT NULL, end DATETIME NOT NULL, duration VARCHAR(255) NOT NULL COMMENT \'(DC2Type:dateinterval)\', comment LONGTEXT NOT NULL, objectives LONGTEXT NOT NULL, id_unique VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, zoom_link VARCHAR(255) NOT NULL, recurrence TINYINT(1) NOT NULL, recurrence_end DATETIME DEFAULT NULL, recurrence_frequency VARCHAR(255) DEFAULT NULL, background_color VARCHAR(7) NOT NULL, text_color VARCHAR(7) NOT NULL, INDEX IDX_A9A55A4C54177093 (room_id), INDEX IDX_A9A55A4CA5522701 (discipline_id), INDEX IDX_A9A55A4CC32A47EE (school_id), INDEX IDX_A9A55A4C727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE courses_programs (courses_id INT NOT NULL, programs_id INT NOT NULL, INDEX IDX_84983D7F9295384 (courses_id), INDEX IDX_84983D779AEC3C (programs_id), PRIMARY KEY(courses_id, programs_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE courses_lessons (courses_id INT NOT NULL, lessons_id INT NOT NULL, INDEX IDX_867BE545F9295384 (courses_id), INDEX IDX_867BE545FED07355 (lessons_id), PRIMARY KEY(courses_id, lessons_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE delays (id INT AUTO_INCREMENT NOT NULL, school_id INT DEFAULT NULL, student_id INT DEFAULT NULL, course_id INT DEFAULT NULL, evaluation_id INT DEFAULT NULL, delay_time TIME NOT NULL, INDEX IDX_7C611F47C32A47EE (school_id), INDEX IDX_7C611F47CB944F1A (student_id), INDEX IDX_7C611F47591CC992 (course_id), INDEX IDX_7C611F47456C5646 (evaluation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE disciplines (id INT AUTO_INCREMENT NOT NULL, school_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_AD1D5CD8C32A47EE (school_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE disciplines_users (disciplines_id INT NOT NULL, users_id INT NOT NULL, INDEX IDX_EEE6B48C90D3DF94 (disciplines_id), INDEX IDX_EEE6B48C67B3B43D (users_id), PRIMARY KEY(disciplines_id, users_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evaluations (id INT AUTO_INCREMENT NOT NULL, discipline_id INT DEFAULT NULL, school_id INT DEFAULT NULL, teacher_id INT DEFAULT NULL, start DATETIME NOT NULL, duration VARCHAR(255) NOT NULL COMMENT \'(DC2Type:dateinterval)\', end DATETIME NOT NULL, comment LONGTEXT NOT NULL, objective LONGTEXT NOT NULL, id_unique VARCHAR(255) NOT NULL, coefficient DOUBLE PRECISION NOT NULL, max_notation INT NOT NULL, background_color VARCHAR(7) NOT NULL, text_color VARCHAR(7) NOT NULL, INDEX IDX_3B72691DA5522701 (discipline_id), INDEX IDX_3B72691DC32A47EE (school_id), INDEX IDX_3B72691D41807E1D (teacher_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evaluations_lessons (evaluations_id INT NOT NULL, lessons_id INT NOT NULL, INDEX IDX_2281F135788B35D6 (evaluations_id), INDEX IDX_2281F135FED07355 (lessons_id), PRIMARY KEY(evaluations_id, lessons_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evaluations_programs (evaluations_id INT NOT NULL, programs_id INT NOT NULL, INDEX IDX_58E808FF788B35D6 (evaluations_id), INDEX IDX_58E808FF79AEC3C (programs_id), PRIMARY KEY(evaluations_id, programs_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evaluations_users (evaluations_id INT NOT NULL, users_id INT NOT NULL, INDEX IDX_5CD92F6A788B35D6 (evaluations_id), INDEX IDX_5CD92F6A67B3B43D (users_id), PRIMARY KEY(evaluations_id, users_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE events (id INT AUTO_INCREMENT NOT NULL, school_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, start DATETIME NOT NULL, end DATETIME NOT NULL, description LONGTEXT NOT NULL, background_color VARCHAR(7) NOT NULL, text_color VARCHAR(7) NOT NULL, INDEX IDX_5387574AC32A47EE (school_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lessons (id INT AUTO_INCREMENT NOT NULL, program_id INT DEFAULT NULL, school_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, thumnail VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', type VARCHAR(10) NOT NULL, content LONGTEXT NOT NULL, INDEX IDX_3F4218D93EB8070A (program_id), INDEX IDX_3F4218D9C32A47EE (school_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lessons_users (lessons_id INT NOT NULL, users_id INT NOT NULL, INDEX IDX_E530EACAFED07355 (lessons_id), INDEX IDX_E530EACA67B3B43D (users_id), PRIMARY KEY(lessons_id, users_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lessons_audios (id INT AUTO_INCREMENT NOT NULL, lesson_id INT DEFAULT NULL, school_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, link VARCHAR(255) NOT NULL, INDEX IDX_39E517CBCDF80196 (lesson_id), INDEX IDX_39E517CBC32A47EE (school_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lessons_pdf (id INT AUTO_INCREMENT NOT NULL, lesson_id INT DEFAULT NULL, school_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, link VARCHAR(255) NOT NULL, INDEX IDX_7EDBC617CDF80196 (lesson_id), INDEX IDX_7EDBC617C32A47EE (school_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lessons_videos (id INT AUTO_INCREMENT NOT NULL, lesson_id INT DEFAULT NULL, school_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, link VARCHAR(255) NOT NULL, INDEX IDX_8B3CA60FCDF80196 (lesson_id), INDEX IDX_8B3CA60FC32A47EE (school_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE posts (id INT AUTO_INCREMENT NOT NULL, categorie_id INT DEFAULT NULL, author_id INT DEFAULT NULL, school_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, thumbnail VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_885DBAFABCF5E72D (categorie_id), INDEX IDX_885DBAFAF675F31B (author_id), INDEX IDX_885DBAFAC32A47EE (school_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE programs (id INT AUTO_INCREMENT NOT NULL, school_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, thumbnail VARCHAR(255) NOT NULL, type VARCHAR(10) NOT NULL, INDEX IDX_F1496545C32A47EE (school_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE programs_users (programs_id INT NOT NULL, users_id INT NOT NULL, INDEX IDX_C2E624C779AEC3C (programs_id), INDEX IDX_C2E624C767B3B43D (users_id), PRIMARY KEY(programs_id, users_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ratings (id INT AUTO_INCREMENT NOT NULL, student_id INT DEFAULT NULL, evaluation_id INT DEFAULT NULL, school_id INT DEFAULT NULL, comment LONGTEXT NOT NULL, INDEX IDX_CEB607C9CB944F1A (student_id), INDEX IDX_CEB607C9456C5646 (evaluation_id), INDEX IDX_CEB607C9C32A47EE (school_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rooms (id INT AUTO_INCREMENT NOT NULL, school_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, equipments LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', INDEX IDX_7CA11A96C32A47EE (school_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE schools (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, number VARCHAR(50) NOT NULL, city VARCHAR(255) NOT NULL, campus VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE student_classes (id INT AUTO_INCREMENT NOT NULL, school_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, INDEX IDX_EFDBCB3DC32A47EE (school_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE users (id INT AUTO_INCREMENT NOT NULL, school_id INT DEFAULT NULL, student_classes_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, lastname VARCHAR(100) NOT NULL, firstname VARCHAR(100) NOT NULL, address VARCHAR(255) NOT NULL, zipcode VARCHAR(10) NOT NULL, city VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_verified TINYINT(1) NOT NULL, is_online TINYINT(1) NOT NULL, thumbnail VARCHAR(255) NOT NULL, phone_number VARCHAR(10) NOT NULL, date_of_birth DATE NOT NULL, area_code VARCHAR(5) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9E7927C74 (email), INDEX IDX_1483A5E9C32A47EE (school_id), INDEX IDX_1483A5E973FDB6C5 (student_classes_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE absences ADD CONSTRAINT FK_F9C0EFFFCB944F1A FOREIGN KEY (student_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE absences ADD CONSTRAINT FK_F9C0EFFF591CC992 FOREIGN KEY (course_id) REFERENCES courses (id)');
        $this->addSql('ALTER TABLE absences ADD CONSTRAINT FK_F9C0EFFF456C5646 FOREIGN KEY (evaluation_id) REFERENCES evaluations (id)');
        $this->addSql('ALTER TABLE absences ADD CONSTRAINT FK_F9C0EFFFC32A47EE FOREIGN KEY (school_id) REFERENCES schools (id)');
        $this->addSql('ALTER TABLE categories ADD CONSTRAINT FK_3AF34668727ACA70 FOREIGN KEY (parent_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE categories ADD CONSTRAINT FK_3AF34668C32A47EE FOREIGN KEY (school_id) REFERENCES schools (id)');
        $this->addSql('ALTER TABLE courses ADD CONSTRAINT FK_A9A55A4C54177093 FOREIGN KEY (room_id) REFERENCES rooms (id)');
        $this->addSql('ALTER TABLE courses ADD CONSTRAINT FK_A9A55A4CA5522701 FOREIGN KEY (discipline_id) REFERENCES disciplines (id)');
        $this->addSql('ALTER TABLE courses ADD CONSTRAINT FK_A9A55A4CC32A47EE FOREIGN KEY (school_id) REFERENCES schools (id)');
        $this->addSql('ALTER TABLE courses ADD CONSTRAINT FK_A9A55A4C727ACA70 FOREIGN KEY (parent_id) REFERENCES courses (id)');
        $this->addSql('ALTER TABLE courses_programs ADD CONSTRAINT FK_84983D7F9295384 FOREIGN KEY (courses_id) REFERENCES courses (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE courses_programs ADD CONSTRAINT FK_84983D779AEC3C FOREIGN KEY (programs_id) REFERENCES programs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE courses_lessons ADD CONSTRAINT FK_867BE545F9295384 FOREIGN KEY (courses_id) REFERENCES courses (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE courses_lessons ADD CONSTRAINT FK_867BE545FED07355 FOREIGN KEY (lessons_id) REFERENCES lessons (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE delays ADD CONSTRAINT FK_7C611F47C32A47EE FOREIGN KEY (school_id) REFERENCES schools (id)');
        $this->addSql('ALTER TABLE delays ADD CONSTRAINT FK_7C611F47CB944F1A FOREIGN KEY (student_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE delays ADD CONSTRAINT FK_7C611F47591CC992 FOREIGN KEY (course_id) REFERENCES courses (id)');
        $this->addSql('ALTER TABLE delays ADD CONSTRAINT FK_7C611F47456C5646 FOREIGN KEY (evaluation_id) REFERENCES evaluations (id)');
        $this->addSql('ALTER TABLE disciplines ADD CONSTRAINT FK_AD1D5CD8C32A47EE FOREIGN KEY (school_id) REFERENCES schools (id)');
        $this->addSql('ALTER TABLE disciplines_users ADD CONSTRAINT FK_EEE6B48C90D3DF94 FOREIGN KEY (disciplines_id) REFERENCES disciplines (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE disciplines_users ADD CONSTRAINT FK_EEE6B48C67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evaluations ADD CONSTRAINT FK_3B72691DA5522701 FOREIGN KEY (discipline_id) REFERENCES disciplines (id)');
        $this->addSql('ALTER TABLE evaluations ADD CONSTRAINT FK_3B72691DC32A47EE FOREIGN KEY (school_id) REFERENCES schools (id)');
        $this->addSql('ALTER TABLE evaluations ADD CONSTRAINT FK_3B72691D41807E1D FOREIGN KEY (teacher_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE evaluations_lessons ADD CONSTRAINT FK_2281F135788B35D6 FOREIGN KEY (evaluations_id) REFERENCES evaluations (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evaluations_lessons ADD CONSTRAINT FK_2281F135FED07355 FOREIGN KEY (lessons_id) REFERENCES lessons (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evaluations_programs ADD CONSTRAINT FK_58E808FF788B35D6 FOREIGN KEY (evaluations_id) REFERENCES evaluations (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evaluations_programs ADD CONSTRAINT FK_58E808FF79AEC3C FOREIGN KEY (programs_id) REFERENCES programs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evaluations_users ADD CONSTRAINT FK_5CD92F6A788B35D6 FOREIGN KEY (evaluations_id) REFERENCES evaluations (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evaluations_users ADD CONSTRAINT FK_5CD92F6A67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE events ADD CONSTRAINT FK_5387574AC32A47EE FOREIGN KEY (school_id) REFERENCES schools (id)');
        $this->addSql('ALTER TABLE lessons ADD CONSTRAINT FK_3F4218D93EB8070A FOREIGN KEY (program_id) REFERENCES programs (id)');
        $this->addSql('ALTER TABLE lessons ADD CONSTRAINT FK_3F4218D9C32A47EE FOREIGN KEY (school_id) REFERENCES schools (id)');
        $this->addSql('ALTER TABLE lessons_users ADD CONSTRAINT FK_E530EACAFED07355 FOREIGN KEY (lessons_id) REFERENCES lessons (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lessons_users ADD CONSTRAINT FK_E530EACA67B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lessons_audios ADD CONSTRAINT FK_39E517CBCDF80196 FOREIGN KEY (lesson_id) REFERENCES lessons (id)');
        $this->addSql('ALTER TABLE lessons_audios ADD CONSTRAINT FK_39E517CBC32A47EE FOREIGN KEY (school_id) REFERENCES schools (id)');
        $this->addSql('ALTER TABLE lessons_pdf ADD CONSTRAINT FK_7EDBC617CDF80196 FOREIGN KEY (lesson_id) REFERENCES lessons (id)');
        $this->addSql('ALTER TABLE lessons_pdf ADD CONSTRAINT FK_7EDBC617C32A47EE FOREIGN KEY (school_id) REFERENCES schools (id)');
        $this->addSql('ALTER TABLE lessons_videos ADD CONSTRAINT FK_8B3CA60FCDF80196 FOREIGN KEY (lesson_id) REFERENCES lessons (id)');
        $this->addSql('ALTER TABLE lessons_videos ADD CONSTRAINT FK_8B3CA60FC32A47EE FOREIGN KEY (school_id) REFERENCES schools (id)');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFABCF5E72D FOREIGN KEY (categorie_id) REFERENCES categories (id)');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFAF675F31B FOREIGN KEY (author_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE posts ADD CONSTRAINT FK_885DBAFAC32A47EE FOREIGN KEY (school_id) REFERENCES schools (id)');
        $this->addSql('ALTER TABLE programs ADD CONSTRAINT FK_F1496545C32A47EE FOREIGN KEY (school_id) REFERENCES schools (id)');
        $this->addSql('ALTER TABLE programs_users ADD CONSTRAINT FK_C2E624C779AEC3C FOREIGN KEY (programs_id) REFERENCES programs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE programs_users ADD CONSTRAINT FK_C2E624C767B3B43D FOREIGN KEY (users_id) REFERENCES users (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ratings ADD CONSTRAINT FK_CEB607C9CB944F1A FOREIGN KEY (student_id) REFERENCES users (id)');
        $this->addSql('ALTER TABLE ratings ADD CONSTRAINT FK_CEB607C9456C5646 FOREIGN KEY (evaluation_id) REFERENCES evaluations (id)');
        $this->addSql('ALTER TABLE ratings ADD CONSTRAINT FK_CEB607C9C32A47EE FOREIGN KEY (school_id) REFERENCES schools (id)');
        $this->addSql('ALTER TABLE rooms ADD CONSTRAINT FK_7CA11A96C32A47EE FOREIGN KEY (school_id) REFERENCES schools (id)');
        $this->addSql('ALTER TABLE student_classes ADD CONSTRAINT FK_EFDBCB3DC32A47EE FOREIGN KEY (school_id) REFERENCES schools (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E9C32A47EE FOREIGN KEY (school_id) REFERENCES schools (id)');
        $this->addSql('ALTER TABLE users ADD CONSTRAINT FK_1483A5E973FDB6C5 FOREIGN KEY (student_classes_id) REFERENCES student_classes (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE absences DROP FOREIGN KEY FK_F9C0EFFFCB944F1A');
        $this->addSql('ALTER TABLE absences DROP FOREIGN KEY FK_F9C0EFFF591CC992');
        $this->addSql('ALTER TABLE absences DROP FOREIGN KEY FK_F9C0EFFF456C5646');
        $this->addSql('ALTER TABLE absences DROP FOREIGN KEY FK_F9C0EFFFC32A47EE');
        $this->addSql('ALTER TABLE categories DROP FOREIGN KEY FK_3AF34668727ACA70');
        $this->addSql('ALTER TABLE categories DROP FOREIGN KEY FK_3AF34668C32A47EE');
        $this->addSql('ALTER TABLE courses DROP FOREIGN KEY FK_A9A55A4C54177093');
        $this->addSql('ALTER TABLE courses DROP FOREIGN KEY FK_A9A55A4CA5522701');
        $this->addSql('ALTER TABLE courses DROP FOREIGN KEY FK_A9A55A4CC32A47EE');
        $this->addSql('ALTER TABLE courses DROP FOREIGN KEY FK_A9A55A4C727ACA70');
        $this->addSql('ALTER TABLE courses_programs DROP FOREIGN KEY FK_84983D7F9295384');
        $this->addSql('ALTER TABLE courses_programs DROP FOREIGN KEY FK_84983D779AEC3C');
        $this->addSql('ALTER TABLE courses_lessons DROP FOREIGN KEY FK_867BE545F9295384');
        $this->addSql('ALTER TABLE courses_lessons DROP FOREIGN KEY FK_867BE545FED07355');
        $this->addSql('ALTER TABLE delays DROP FOREIGN KEY FK_7C611F47C32A47EE');
        $this->addSql('ALTER TABLE delays DROP FOREIGN KEY FK_7C611F47CB944F1A');
        $this->addSql('ALTER TABLE delays DROP FOREIGN KEY FK_7C611F47591CC992');
        $this->addSql('ALTER TABLE delays DROP FOREIGN KEY FK_7C611F47456C5646');
        $this->addSql('ALTER TABLE disciplines DROP FOREIGN KEY FK_AD1D5CD8C32A47EE');
        $this->addSql('ALTER TABLE disciplines_users DROP FOREIGN KEY FK_EEE6B48C90D3DF94');
        $this->addSql('ALTER TABLE disciplines_users DROP FOREIGN KEY FK_EEE6B48C67B3B43D');
        $this->addSql('ALTER TABLE evaluations DROP FOREIGN KEY FK_3B72691DA5522701');
        $this->addSql('ALTER TABLE evaluations DROP FOREIGN KEY FK_3B72691DC32A47EE');
        $this->addSql('ALTER TABLE evaluations DROP FOREIGN KEY FK_3B72691D41807E1D');
        $this->addSql('ALTER TABLE evaluations_lessons DROP FOREIGN KEY FK_2281F135788B35D6');
        $this->addSql('ALTER TABLE evaluations_lessons DROP FOREIGN KEY FK_2281F135FED07355');
        $this->addSql('ALTER TABLE evaluations_programs DROP FOREIGN KEY FK_58E808FF788B35D6');
        $this->addSql('ALTER TABLE evaluations_programs DROP FOREIGN KEY FK_58E808FF79AEC3C');
        $this->addSql('ALTER TABLE evaluations_users DROP FOREIGN KEY FK_5CD92F6A788B35D6');
        $this->addSql('ALTER TABLE evaluations_users DROP FOREIGN KEY FK_5CD92F6A67B3B43D');
        $this->addSql('ALTER TABLE events DROP FOREIGN KEY FK_5387574AC32A47EE');
        $this->addSql('ALTER TABLE lessons DROP FOREIGN KEY FK_3F4218D93EB8070A');
        $this->addSql('ALTER TABLE lessons DROP FOREIGN KEY FK_3F4218D9C32A47EE');
        $this->addSql('ALTER TABLE lessons_users DROP FOREIGN KEY FK_E530EACAFED07355');
        $this->addSql('ALTER TABLE lessons_users DROP FOREIGN KEY FK_E530EACA67B3B43D');
        $this->addSql('ALTER TABLE lessons_audios DROP FOREIGN KEY FK_39E517CBCDF80196');
        $this->addSql('ALTER TABLE lessons_audios DROP FOREIGN KEY FK_39E517CBC32A47EE');
        $this->addSql('ALTER TABLE lessons_pdf DROP FOREIGN KEY FK_7EDBC617CDF80196');
        $this->addSql('ALTER TABLE lessons_pdf DROP FOREIGN KEY FK_7EDBC617C32A47EE');
        $this->addSql('ALTER TABLE lessons_videos DROP FOREIGN KEY FK_8B3CA60FCDF80196');
        $this->addSql('ALTER TABLE lessons_videos DROP FOREIGN KEY FK_8B3CA60FC32A47EE');
        $this->addSql('ALTER TABLE posts DROP FOREIGN KEY FK_885DBAFABCF5E72D');
        $this->addSql('ALTER TABLE posts DROP FOREIGN KEY FK_885DBAFAF675F31B');
        $this->addSql('ALTER TABLE posts DROP FOREIGN KEY FK_885DBAFAC32A47EE');
        $this->addSql('ALTER TABLE programs DROP FOREIGN KEY FK_F1496545C32A47EE');
        $this->addSql('ALTER TABLE programs_users DROP FOREIGN KEY FK_C2E624C779AEC3C');
        $this->addSql('ALTER TABLE programs_users DROP FOREIGN KEY FK_C2E624C767B3B43D');
        $this->addSql('ALTER TABLE ratings DROP FOREIGN KEY FK_CEB607C9CB944F1A');
        $this->addSql('ALTER TABLE ratings DROP FOREIGN KEY FK_CEB607C9456C5646');
        $this->addSql('ALTER TABLE ratings DROP FOREIGN KEY FK_CEB607C9C32A47EE');
        $this->addSql('ALTER TABLE rooms DROP FOREIGN KEY FK_7CA11A96C32A47EE');
        $this->addSql('ALTER TABLE student_classes DROP FOREIGN KEY FK_EFDBCB3DC32A47EE');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E9C32A47EE');
        $this->addSql('ALTER TABLE users DROP FOREIGN KEY FK_1483A5E973FDB6C5');
        $this->addSql('DROP TABLE absences');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE courses');
        $this->addSql('DROP TABLE courses_programs');
        $this->addSql('DROP TABLE courses_lessons');
        $this->addSql('DROP TABLE delays');
        $this->addSql('DROP TABLE disciplines');
        $this->addSql('DROP TABLE disciplines_users');
        $this->addSql('DROP TABLE evaluations');
        $this->addSql('DROP TABLE evaluations_lessons');
        $this->addSql('DROP TABLE evaluations_programs');
        $this->addSql('DROP TABLE evaluations_users');
        $this->addSql('DROP TABLE events');
        $this->addSql('DROP TABLE lessons');
        $this->addSql('DROP TABLE lessons_users');
        $this->addSql('DROP TABLE lessons_audios');
        $this->addSql('DROP TABLE lessons_pdf');
        $this->addSql('DROP TABLE lessons_videos');
        $this->addSql('DROP TABLE posts');
        $this->addSql('DROP TABLE programs');
        $this->addSql('DROP TABLE programs_users');
        $this->addSql('DROP TABLE ratings');
        $this->addSql('DROP TABLE rooms');
        $this->addSql('DROP TABLE schools');
        $this->addSql('DROP TABLE student_classes');
        $this->addSql('DROP TABLE users');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
