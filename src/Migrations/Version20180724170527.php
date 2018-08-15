<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180724170527 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, content LONGTEXT NOT NULL, created_at DATETIME NOT NULL, published TINYINT(1) NOT NULL, INDEX IDX_9474526CF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bird (id INT AUTO_INCREMENT NOT NULL, reign VARCHAR(255) NOT NULL, phylum VARCHAR(255) NOT NULL, class VARCHAR(255) NOT NULL, bird_order VARCHAR(255) NOT NULL, family VARCHAR(255) NOT NULL, vernacularname VARCHAR(255) NOT NULL, capture VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, path VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, extension VARCHAR(255) NOT NULL, mime_type VARCHAR(255) NOT NULL, weight DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, lastname VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, sent_at DATETIME NOT NULL, subject VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE aves');
        $this->addSql('DROP TABLE species');
        $this->addSql('ALTER TABLE capture ADD bird_id INT NOT NULL, ADD user_id INT NOT NULL, ADD validated_by_id INT DEFAULT NULL, ADD content LONGTEXT NOT NULL, ADD latitude DOUBLE PRECISION NOT NULL, ADD longitude DOUBLE PRECISION NOT NULL, ADD address VARCHAR(255) NOT NULL, ADD complement VARCHAR(255) NOT NULL, ADD zipcode VARCHAR(255) NOT NULL, ADD city VARCHAR(255) NOT NULL, ADD published TINYINT(1) DEFAULT NULL, ADD draft TINYINT(1) DEFAULT NULL, ADD naturalist_comment LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE capture ADD CONSTRAINT FK_8BFEA6E5E813F9 FOREIGN KEY (bird_id) REFERENCES bird (id)');
        $this->addSql('ALTER TABLE capture ADD CONSTRAINT FK_8BFEA6E5A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE capture ADD CONSTRAINT FK_8BFEA6E5C69DE5E5 FOREIGN KEY (validated_by_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_8BFEA6E5E813F9 ON capture (bird_id)');
        $this->addSql('CREATE INDEX IDX_8BFEA6E5A76ED395 ON capture (user_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8BFEA6E5C69DE5E5 ON capture (validated_by_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE capture DROP FOREIGN KEY FK_8BFEA6E5E813F9');
        $this->addSql('CREATE TABLE aves (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE species (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE bird');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE message');
        $this->addSql('ALTER TABLE capture DROP FOREIGN KEY FK_8BFEA6E5A76ED395');
        $this->addSql('ALTER TABLE capture DROP FOREIGN KEY FK_8BFEA6E5C69DE5E5');
        $this->addSql('DROP INDEX IDX_8BFEA6E5E813F9 ON capture');
        $this->addSql('DROP INDEX IDX_8BFEA6E5A76ED395 ON capture');
        $this->addSql('DROP INDEX UNIQ_8BFEA6E5C69DE5E5 ON capture');
        $this->addSql('ALTER TABLE capture DROP bird_id, DROP user_id, DROP validated_by_id, DROP content, DROP latitude, DROP longitude, DROP address, DROP complement, DROP zipcode, DROP city, DROP published, DROP draft, DROP naturalist_comment');
    }
}
