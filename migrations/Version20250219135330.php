<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250219135330 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article ADD COLUMN uid VARCHAR(255) NOT NULL DEFAULT ""');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, password, email, roles, uid, username, balance, profile_picture FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , uid VARCHAR(32) NOT NULL, username VARCHAR(255) NOT NULL, balance DOUBLE PRECISION NOT NULL, profile_picture VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO user (id, password, email, roles, uid, username, balance, profile_picture) SELECT id, password, email, roles, uid, username, balance, profile_picture FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649539B0606 ON user (uid)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON user (email)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__article AS SELECT id, creator_id_id, name, description, price, publication_date, image FROM article');
        $this->addSql('DROP TABLE article');
        $this->addSql('CREATE TABLE article (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, creator_id_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, publication_date DATETIME NOT NULL, image VARCHAR(255) NOT NULL, CONSTRAINT FK_23A0E66F05788E9 FOREIGN KEY (creator_id_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO article (id, creator_id_id, name, description, price, publication_date, image) SELECT id, creator_id_id, name, description, price, publication_date, image FROM __temp__article');
        $this->addSql('DROP TABLE __temp__article');
        $this->addSql('CREATE INDEX IDX_23A0E66F05788E9 ON article (creator_id_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, email, roles, password, uid, username, balance, profile_picture FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, uid VARCHAR(32) NOT NULL, username VARCHAR(255) DEFAULT \'""\' NOT NULL, balance DOUBLE PRECISION DEFAULT \'0\' NOT NULL, profile_picture VARCHAR(255) DEFAULT \'""\' NOT NULL)');
        $this->addSql('INSERT INTO user (id, email, roles, password, uid, username, balance, profile_picture) SELECT id, email, roles, password, uid, username, balance, profile_picture FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649539B0606 ON user (uid)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON user (email)');
    }
}
