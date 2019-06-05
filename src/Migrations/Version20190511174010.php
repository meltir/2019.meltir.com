<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190511174010 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE imdb_movies CHANGE my_rating my_rating INT DEFAULT NULL, CHANGE trailer trailer VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE page_post ADD parent_post_id INT DEFAULT NULL, CHANGE post_order post_order INT DEFAULT NULL, CHANGE active active TINYINT(1) DEFAULT NULL, CHANGE link_image link_image VARCHAR(255) DEFAULT NULL, CHANGE link_url link_url VARCHAR(255) DEFAULT NULL, CHANGE link_title link_title VARCHAR(255) DEFAULT NULL, CHANGE title title VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE page_post ADD CONSTRAINT FK_DD4E705739C1776A FOREIGN KEY (parent_post_id) REFERENCES page_post (id)');
        $this->addSql('CREATE INDEX IDX_DD4E705739C1776A ON page_post (parent_post_id)');
        $this->addSql('ALTER TABLE user CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE imdb_movies CHANGE my_rating my_rating INT DEFAULT NULL, CHANGE trailer trailer VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE page_post DROP FOREIGN KEY FK_DD4E705739C1776A');
        $this->addSql('DROP INDEX IDX_DD4E705739C1776A ON page_post');
        $this->addSql('ALTER TABLE page_post DROP parent_post_id, CHANGE post_order post_order INT DEFAULT NULL, CHANGE active active TINYINT(1) DEFAULT \'NULL\', CHANGE link_image link_image VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE link_url link_url VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE link_title link_title VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci, CHANGE title title VARCHAR(255) DEFAULT \'NULL\' COLLATE utf8mb4_unicode_ci');
        $this->addSql('ALTER TABLE user CHANGE roles roles LONGTEXT NOT NULL COLLATE utf8mb4_bin');
    }
}