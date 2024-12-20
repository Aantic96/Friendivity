<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241219223328 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE friend (id INT AUTO_INCREMENT NOT NULL, friends_since DATETIME NOT NULL, is_favorite TINYINT(1) NOT NULL, friend_one_id INT NOT NULL, friend_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_55EEAC612A81397A (friend_one_id), INDEX IDX_55EEAC616A5458E8 (friend_id), INDEX IDX_55EEAC61A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('CREATE TABLE friend_request (id INT AUTO_INCREMENT NOT NULL, status VARCHAR(20) NOT NULL, sender_id INT NOT NULL, recipient_id INT NOT NULL, INDEX IDX_F284D94F624B39D (sender_id), INDEX IDX_F284D94E92F8F78 (recipient_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE friend ADD CONSTRAINT FK_55EEAC612A81397A FOREIGN KEY (friend_one_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE friend ADD CONSTRAINT FK_55EEAC616A5458E8 FOREIGN KEY (friend_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE friend ADD CONSTRAINT FK_55EEAC61A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE friend_request ADD CONSTRAINT FK_F284D94F624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE friend_request ADD CONSTRAINT FK_F284D94E92F8F78 FOREIGN KEY (recipient_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE friend DROP FOREIGN KEY FK_55EEAC612A81397A');
        $this->addSql('ALTER TABLE friend DROP FOREIGN KEY FK_55EEAC616A5458E8');
        $this->addSql('ALTER TABLE friend DROP FOREIGN KEY FK_55EEAC61A76ED395');
        $this->addSql('ALTER TABLE friend_request DROP FOREIGN KEY FK_F284D94F624B39D');
        $this->addSql('ALTER TABLE friend_request DROP FOREIGN KEY FK_F284D94E92F8F78');
        $this->addSql('DROP TABLE friend');
        $this->addSql('DROP TABLE friend_request');
    }
}
