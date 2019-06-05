<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190430132105 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE page_post CHANGE post_order post_order INT DEFAULT NULL, CHANGE active active TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE yt_videos DROP FOREIGN KEY FK_DFE6A285C86596CF');
        $this->addSql('DROP INDEX IDX_DFE6A285C86596CF ON yt_videos');
        $this->addSql('ALTER TABLE yt_videos CHANGE channel_id_id channel_id INT NOT NULL');
        $this->addSql('ALTER TABLE yt_videos ADD CONSTRAINT FK_DFE6A28572F5A1AA FOREIGN KEY (channel_id) REFERENCES yt_channels (id)');
        $this->addSql('CREATE INDEX IDX_DFE6A28572F5A1AA ON yt_videos (channel_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE page_post CHANGE post_order post_order INT DEFAULT NULL, CHANGE active active TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
        $this->addSql('ALTER TABLE yt_videos DROP FOREIGN KEY FK_DFE6A28572F5A1AA');
        $this->addSql('DROP INDEX IDX_DFE6A28572F5A1AA ON yt_videos');
        $this->addSql('ALTER TABLE yt_videos CHANGE channel_id channel_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE yt_videos ADD CONSTRAINT FK_DFE6A285C86596CF FOREIGN KEY (channel_id_id) REFERENCES yt_channels (id)');
        $this->addSql('CREATE INDEX IDX_DFE6A285C86596CF ON yt_videos (channel_id_id)');
    }
}
