<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220331112740 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE chat_user');
        $this->addSql('ALTER TABLE chat ADD post_id INT NOT NULL, ADD buyer_id INT NOT NULL');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AA4B89032C FOREIGN KEY (post_id) REFERENCES post (id)');
        $this->addSql('ALTER TABLE chat ADD CONSTRAINT FK_659DF2AA6C755722 FOREIGN KEY (buyer_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_659DF2AA4B89032C ON chat (post_id)');
        $this->addSql('CREATE INDEX IDX_659DF2AA6C755722 ON chat (buyer_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE chat_user (chat_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_2B0F4B081A9A7125 (chat_id), INDEX IDX_2B0F4B08A76ED395 (user_id), PRIMARY KEY(chat_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE chat_user ADD CONSTRAINT FK_2B0F4B08A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chat_user ADD CONSTRAINT FK_2B0F4B081A9A7125 FOREIGN KEY (chat_id) REFERENCES chat (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AA4B89032C');
        $this->addSql('ALTER TABLE chat DROP FOREIGN KEY FK_659DF2AA6C755722');
        $this->addSql('DROP INDEX IDX_659DF2AA4B89032C ON chat');
        $this->addSql('DROP INDEX IDX_659DF2AA6C755722 ON chat');
        $this->addSql('ALTER TABLE chat DROP post_id, DROP buyer_id');
    }
}
