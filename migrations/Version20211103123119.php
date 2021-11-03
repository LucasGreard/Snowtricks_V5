<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211103123119 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE activate activate VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user_new_pw DROP INDEX UNIQ_C7EA4048A76ED395, ADD INDEX IDX_C7EA4048A76ED395 (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user CHANGE activate activate VARCHAR(255) CHARACTER SET utf8 DEFAULT NULL COLLATE `utf8_general_ci`');
        $this->addSql('ALTER TABLE user_new_pw DROP INDEX IDX_C7EA4048A76ED395, ADD UNIQUE INDEX UNIQ_C7EA4048A76ED395 (user_id)');
    }
}
