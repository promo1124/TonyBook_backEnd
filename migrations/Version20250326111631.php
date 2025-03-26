<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250326111631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495584CF0CF FOREIGN KEY (sejour_id) REFERENCES sejour (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)');
        $this->addSql('CREATE INDEX IDX_42C8495584CF0CF ON reservation (sejour_id)');
        $this->addSql('CREATE INDEX IDX_42C84955F347EFB ON reservation (produit_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495584CF0CF');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955F347EFB');
        $this->addSql('DROP INDEX IDX_42C8495584CF0CF ON reservation');
        $this->addSql('DROP INDEX IDX_42C84955F347EFB ON reservation');
    }
}
