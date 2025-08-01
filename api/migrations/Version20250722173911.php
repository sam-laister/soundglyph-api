<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250722173911 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE album (id UUID NOT NULL, artwork_path VARCHAR(255) DEFAULT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE album_track (album_id UUID NOT NULL, track_id UUID NOT NULL, PRIMARY KEY(album_id, track_id))');
        $this->addSql('CREATE INDEX IDX_A05BB2801137ABCF ON album_track (album_id)');
        $this->addSql('CREATE INDEX IDX_A05BB2805ED23C43 ON album_track (track_id)');
        $this->addSql('CREATE TABLE album_artist (album_id UUID NOT NULL, artist_id UUID NOT NULL, PRIMARY KEY(album_id, artist_id))');
        $this->addSql('CREATE INDEX IDX_D322AB301137ABCF ON album_artist (album_id)');
        $this->addSql('CREATE INDEX IDX_D322AB30B7970CF8 ON album_artist (artist_id)');
        $this->addSql('CREATE TABLE artist (id UUID NOT NULL, artwork_path VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE media (id UUID NOT NULL, path VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE track (id UUID NOT NULL, title VARCHAR(255) NOT NULL, audio_path VARCHAR(255) NOT NULL, artwork_path VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE "user" (id UUID NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE album_track ADD CONSTRAINT FK_A05BB2801137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE album_track ADD CONSTRAINT FK_A05BB2805ED23C43 FOREIGN KEY (track_id) REFERENCES track (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE album_artist ADD CONSTRAINT FK_D322AB301137ABCF FOREIGN KEY (album_id) REFERENCES album (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE album_artist ADD CONSTRAINT FK_D322AB30B7970CF8 FOREIGN KEY (artist_id) REFERENCES artist (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE album_track DROP CONSTRAINT FK_A05BB2801137ABCF');
        $this->addSql('ALTER TABLE album_track DROP CONSTRAINT FK_A05BB2805ED23C43');
        $this->addSql('ALTER TABLE album_artist DROP CONSTRAINT FK_D322AB301137ABCF');
        $this->addSql('ALTER TABLE album_artist DROP CONSTRAINT FK_D322AB30B7970CF8');
        $this->addSql('DROP TABLE album');
        $this->addSql('DROP TABLE album_track');
        $this->addSql('DROP TABLE album_artist');
        $this->addSql('DROP TABLE artist');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE track');
        $this->addSql('DROP TABLE "user"');
    }
}
