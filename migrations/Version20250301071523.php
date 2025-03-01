<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250301071523 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, email, roles, password, uid, username, balance, profile_picture FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, uid VARCHAR(32) NOT NULL, username VARCHAR(255) NOT NULL, balance DOUBLE PRECISION NOT NULL, profile_picture VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO user (id, email, roles, password, uid, username, balance, profile_picture) SELECT id, email, roles, password, uid, username, balance, profile_picture FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON user (email)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649539B0606 ON user (uid)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__messenger_messages AS SELECT id, body, headers, queue_name, created_at, available_at, delivered_at FROM messenger_messages');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , available_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , delivered_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO messenger_messages (id, body, headers, queue_name, created_at, available_at, delivered_at) SELECT id, body, headers, queue_name, created_at, available_at, delivered_at FROM __temp__messenger_messages');
        $this->addSql('DROP TABLE __temp__messenger_messages');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__messenger_messages AS SELECT id, body, headers, queue_name, created_at, available_at, delivered_at FROM messenger_messages');
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('CREATE TABLE messenger_messages (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, body CLOB NOT NULL, headers CLOB NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , available_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , delivered_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO messenger_messages (id, body, headers, queue_name, created_at, available_at, delivered_at) SELECT id, body, headers, queue_name, created_at, available_at, delivered_at FROM __temp__messenger_messages');
        $this->addSql('DROP TABLE __temp__messenger_messages');
        $this->addSql('CREATE INDEX IDX_75EA56E0FB7336F0 ON messenger_messages (queue_name)');
        $this->addSql('CREATE INDEX IDX_75EA56E0E3BD61CE ON messenger_messages (available_at)');
        $this->addSql('CREATE INDEX IDX_75EA56E016BA31DB ON messenger_messages (delivered_at)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__user AS SELECT id, email, roles, password, uid, username, balance, profile_picture FROM user');
        $this->addSql('DROP TABLE user');
        $this->addSql('CREATE TABLE user (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles CLOB NOT NULL --(DC2Type:json)
        , password VARCHAR(255) NOT NULL, uid VARCHAR(32) NOT NULL, username VARCHAR(255) NOT NULL, balance DOUBLE PRECISION NOT NULL, profile_picture VARCHAR(255) NOT NULL)');
        $this->addSql('INSERT INTO user (id, email, roles, password, uid, username, balance, profile_picture) SELECT id, email, roles, password, uid, username, balance, profile_picture FROM __temp__user');
        $this->addSql('DROP TABLE __temp__user');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D649539B0606 ON user (uid)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON user (email)');
    }
}
