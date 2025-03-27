<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250327131701 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495584CF0CF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sejour DROP FOREIGN KEY FK_96F52028F347EFB
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE sejour
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_42C8495584CF0CF ON reservation
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation DROP sejour_id
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE sejour (id INT AUTO_INCREMENT NOT NULL, produit_id INT DEFAULT NULL, status VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_96F52028F347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sejour ADD CONSTRAINT FK_96F52028F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation ADD sejour_id INT NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation ADD CONSTRAINT FK_42C8495584CF0CF FOREIGN KEY (sejour_id) REFERENCES sejour (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_42C8495584CF0CF ON reservation (sejour_id)
        SQL);
    }
}
