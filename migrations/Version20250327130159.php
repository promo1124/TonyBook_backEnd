<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250327130159 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation ADD date_debut DATE NOT NULL, ADD date_fin DATE NOT NULL, ADD nb_jours INT NOT NULL, DROP client_name
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sejour DROP date_debut, DROP date_fin, DROP nb_jours
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation ADD client_name VARCHAR(255) NOT NULL, DROP date_debut, DROP date_fin, DROP nb_jours
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sejour ADD date_debut DATE NOT NULL, ADD date_fin DATE NOT NULL, ADD nb_jours INT NOT NULL
        SQL);
    }
}
