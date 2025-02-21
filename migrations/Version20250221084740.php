<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250221084740 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE favorite (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_uid_id INTEGER NOT NULL, article_uid_id INTEGER NOT NULL, CONSTRAINT FK_68C58ED936194FA6 FOREIGN KEY (user_uid_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_68C58ED9398CC31B FOREIGN KEY (article_uid_id) REFERENCES article (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('CREATE INDEX IDX_68C58ED936194FA6 ON favorite (user_uid_id)');
        $this->addSql('CREATE INDEX IDX_68C58ED9398CC31B ON favorite (article_uid_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__article AS SELECT id, creator_id_id, name, description, price, publication_date, image, uid FROM article');
        $this->addSql('DROP TABLE article');
        $this->addSql('CREATE TABLE article (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, creator_id_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, publication_date DATETIME NOT NULL, image VARCHAR(255) NOT NULL, uid VARCHAR(255) NOT NULL, CONSTRAINT FK_23A0E66F05788E9 FOREIGN KEY (creator_id_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO article (id, creator_id_id, name, description, price, publication_date, image, uid) SELECT id, creator_id_id, name, description, price, publication_date, image, uid FROM __temp__article');
        $this->addSql('DROP TABLE __temp__article');
        $this->addSql('CREATE INDEX IDX_23A0E66F05788E9 ON article (creator_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE favorite');
        $this->addSql('CREATE TEMPORARY TABLE __temp__article AS SELECT id, creator_id_id, name, description, price, publication_date, image, uid FROM article');
        $this->addSql('DROP TABLE article');
        $this->addSql('CREATE TABLE article (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, creator_id_id INTEGER NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, publication_date DATETIME NOT NULL, image VARCHAR(255) NOT NULL, uid VARCHAR(255) DEFAULT \'""\' NOT NULL, CONSTRAINT FK_23A0E66F05788E9 FOREIGN KEY (creator_id_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO article (id, creator_id_id, name, description, price, publication_date, image, uid) SELECT id, creator_id_id, name, description, price, publication_date, image, uid FROM __temp__article');
        $this->addSql('DROP TABLE __temp__article');
        $this->addSql('CREATE INDEX IDX_23A0E66F05788E9 ON article (creator_id_id)');
    }
}
