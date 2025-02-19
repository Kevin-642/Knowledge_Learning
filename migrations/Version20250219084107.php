<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250219084107 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart (id_cart INT AUTO_INCREMENT NOT NULL, id_user INT NOT NULL, id_cursus INT DEFAULT NULL, id_lesson INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_BA388B76B3CA4B (id_user), INDEX IDX_BA388B72462152E (id_cursus), INDEX IDX_BA388B7DE43C11E (id_lesson), UNIQUE INDEX unique_cart_item (id_user, id_cursus, id_lesson), PRIMARY KEY(id_cart)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE certification (id_certification INT AUTO_INCREMENT NOT NULL, id_user INT NOT NULL, id_lesson INT NOT NULL, obtained_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_6C3C6D756B3CA4B (id_user), INDEX IDX_6C3C6D75DE43C11E (id_lesson), PRIMARY KEY(id_certification)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE cursus (id_cursus INT AUTO_INCREMENT NOT NULL, id_theme INT NOT NULL, name_cursus VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', images VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, INDEX IDX_255A0C379F0A638 (id_theme), PRIMARY KEY(id_cursus)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lesson (id_lesson INT AUTO_INCREMENT NOT NULL, id_cursus INT DEFAULT NULL, name_lesson VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, content LONGTEXT NOT NULL, video_url VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', description LONGTEXT DEFAULT NULL, certification_image VARCHAR(255) DEFAULT NULL, INDEX IDX_F87474F32462152E (id_cursus), PRIMARY KEY(id_lesson)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE purchase (id_purchase INT AUTO_INCREMENT NOT NULL, id_user INT NOT NULL, id_cursus INT DEFAULT NULL, id_lesson INT DEFAULT NULL, purchase_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_6117D13B6B3CA4B (id_user), INDEX IDX_6117D13B2462152E (id_cursus), INDEX IDX_6117D13BDE43C11E (id_lesson), PRIMARY KEY(id_purchase)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE theme (id_theme INT AUTO_INCREMENT NOT NULL, name_theme VARCHAR(255) NOT NULL, PRIMARY KEY(id_theme)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id_user INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id_user)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B76B3CA4B FOREIGN KEY (id_user) REFERENCES user (id_user)');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B72462152E FOREIGN KEY (id_cursus) REFERENCES cursus (id_cursus)');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7DE43C11E FOREIGN KEY (id_lesson) REFERENCES lesson (id_lesson)');
        $this->addSql('ALTER TABLE certification ADD CONSTRAINT FK_6C3C6D756B3CA4B FOREIGN KEY (id_user) REFERENCES user (id_user)');
        $this->addSql('ALTER TABLE certification ADD CONSTRAINT FK_6C3C6D75DE43C11E FOREIGN KEY (id_lesson) REFERENCES lesson (id_lesson)');
        $this->addSql('ALTER TABLE cursus ADD CONSTRAINT FK_255A0C379F0A638 FOREIGN KEY (id_theme) REFERENCES theme (id_theme)');
        $this->addSql('ALTER TABLE lesson ADD CONSTRAINT FK_F87474F32462152E FOREIGN KEY (id_cursus) REFERENCES cursus (id_cursus)');
        $this->addSql('ALTER TABLE purchase ADD CONSTRAINT FK_6117D13B6B3CA4B FOREIGN KEY (id_user) REFERENCES user (id_user)');
        $this->addSql('ALTER TABLE purchase ADD CONSTRAINT FK_6117D13B2462152E FOREIGN KEY (id_cursus) REFERENCES cursus (id_cursus)');
        $this->addSql('ALTER TABLE purchase ADD CONSTRAINT FK_6117D13BDE43C11E FOREIGN KEY (id_lesson) REFERENCES lesson (id_lesson)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id_user)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B76B3CA4B');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B72462152E');
        $this->addSql('ALTER TABLE cart DROP FOREIGN KEY FK_BA388B7DE43C11E');
        $this->addSql('ALTER TABLE certification DROP FOREIGN KEY FK_6C3C6D756B3CA4B');
        $this->addSql('ALTER TABLE certification DROP FOREIGN KEY FK_6C3C6D75DE43C11E');
        $this->addSql('ALTER TABLE cursus DROP FOREIGN KEY FK_255A0C379F0A638');
        $this->addSql('ALTER TABLE lesson DROP FOREIGN KEY FK_F87474F32462152E');
        $this->addSql('ALTER TABLE purchase DROP FOREIGN KEY FK_6117D13B6B3CA4B');
        $this->addSql('ALTER TABLE purchase DROP FOREIGN KEY FK_6117D13B2462152E');
        $this->addSql('ALTER TABLE purchase DROP FOREIGN KEY FK_6117D13BDE43C11E');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE certification');
        $this->addSql('DROP TABLE cursus');
        $this->addSql('DROP TABLE lesson');
        $this->addSql('DROP TABLE purchase');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE theme');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
