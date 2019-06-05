<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190430131714 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE yt_categories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE yt_channels (id INT AUTO_INCREMENT NOT NULL, category_id INT NOT NULL, chan_id VARCHAR(255) NOT NULL, thumb VARCHAR(255) NOT NULL, chan_name VARCHAR(255) NOT NULL, INDEX IDX_2A974B0012469DE2 (category_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE yt_videos (id INT AUTO_INCREMENT NOT NULL, channel_id_id INT NOT NULL, videoid VARCHAR(255) NOT NULL, thumb VARCHAR(255) NOT NULL, title VARCHAR(255) NOT NULL, date_published DATE NOT NULL, liked TINYINT(1) NOT NULL, active TINYINT(1) NOT NULL, INDEX IDX_DFE6A285C86596CF (channel_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE yt_channels ADD CONSTRAINT FK_2A974B0012469DE2 FOREIGN KEY (category_id) REFERENCES yt_categories (id)');
        $this->addSql('ALTER TABLE yt_videos ADD CONSTRAINT FK_DFE6A285C86596CF FOREIGN KEY (channel_id_id) REFERENCES yt_channels (id)');
        $this->addSql('ALTER TABLE page_post CHANGE post_order post_order INT DEFAULT NULL, CHANGE active active TINYINT(1) DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE yt_channels DROP FOREIGN KEY FK_2A974B0012469DE2');
        $this->addSql('ALTER TABLE yt_videos DROP FOREIGN KEY FK_DFE6A285C86596CF');
        $this->addSql('DROP TABLE yt_categories');
        $this->addSql('DROP TABLE yt_channels');
        $this->addSql('DROP TABLE yt_videos');
        $this->addSql('ALTER TABLE page_post CHANGE post_order post_order INT DEFAULT NULL, CHANGE active active TINYINT(1) DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
    }
}
