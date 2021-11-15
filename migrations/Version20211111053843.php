<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211111053843 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE movie ADD rental_id INT NOT NULL');
        $this->addSql('ALTER TABLE movie ADD CONSTRAINT FK_1D5EF26FA7CF2329 FOREIGN KEY (rental_id) REFERENCES rental (id)');
        $this->addSql('CREATE INDEX IDX_1D5EF26FA7CF2329 ON movie (rental_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE movie DROP FOREIGN KEY FK_1D5EF26FA7CF2329');
        $this->addSql('DROP INDEX IDX_1D5EF26FA7CF2329 ON movie');
        $this->addSql('ALTER TABLE movie DROP rental_id');
    }
}
