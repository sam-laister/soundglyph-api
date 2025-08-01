<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250729073753 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE album ADD artwork_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE album DROP artwork_path');
        $this->addSql('ALTER TABLE album ADD CONSTRAINT FK_39986E43DB8FFA4 FOREIGN KEY (artwork_id) REFERENCES media (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_39986E43DB8FFA4 ON album (artwork_id)');
        $this->addSql('ALTER TABLE artist ADD artwork_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE artist DROP artwork_path');
        $this->addSql('ALTER TABLE artist ADD CONSTRAINT FK_1599687DB8FFA4 FOREIGN KEY (artwork_id) REFERENCES media (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_1599687DB8FFA4 ON artist (artwork_id)');
        $this->addSql('ALTER TABLE track ADD artwork_id UUID DEFAULT NULL');
        $this->addSql('ALTER TABLE track ADD audio_id UUID NOT NULL');
        $this->addSql('ALTER TABLE track DROP audio_path');
        $this->addSql('ALTER TABLE track DROP artwork_path');
        $this->addSql('ALTER TABLE track ADD CONSTRAINT FK_D6E3F8A6DB8FFA4 FOREIGN KEY (artwork_id) REFERENCES media (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE track ADD CONSTRAINT FK_D6E3F8A63A3123C7 FOREIGN KEY (audio_id) REFERENCES media (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_D6E3F8A6DB8FFA4 ON track (artwork_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D6E3F8A63A3123C7 ON track (audio_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE album DROP CONSTRAINT FK_39986E43DB8FFA4');
        $this->addSql('DROP INDEX IDX_39986E43DB8FFA4');
        $this->addSql('ALTER TABLE album ADD artwork_path VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE album DROP artwork_id');
        $this->addSql('ALTER TABLE track DROP CONSTRAINT FK_D6E3F8A6DB8FFA4');
        $this->addSql('ALTER TABLE track DROP CONSTRAINT FK_D6E3F8A63A3123C7');
        $this->addSql('DROP INDEX IDX_D6E3F8A6DB8FFA4');
        $this->addSql('DROP INDEX UNIQ_D6E3F8A63A3123C7');
        $this->addSql('ALTER TABLE track ADD audio_path VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE track ADD artwork_path VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE track DROP artwork_id');
        $this->addSql('ALTER TABLE track DROP audio_id');
        $this->addSql('ALTER TABLE artist DROP CONSTRAINT FK_1599687DB8FFA4');
        $this->addSql('DROP INDEX IDX_1599687DB8FFA4');
        $this->addSql('ALTER TABLE artist ADD artwork_path VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE artist DROP artwork_id');
    }
}
