<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241219223503 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE friend DROP FOREIGN KEY FK_55EEAC612A81397A');
        $this->addSql('DROP INDEX IDX_55EEAC612A81397A ON friend');
        $this->addSql('ALTER TABLE friend DROP friend_one_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE friend ADD friend_one_id INT NOT NULL');
        $this->addSql('ALTER TABLE friend ADD CONSTRAINT FK_55EEAC612A81397A FOREIGN KEY (friend_one_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_55EEAC612A81397A ON friend (friend_one_id)');
    }
}
