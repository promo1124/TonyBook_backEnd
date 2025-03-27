<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250326211316 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, reservation_id INT DEFAULT NULL, sender_id INT DEFAULT NULL, reception_id INT DEFAULT NULL, contenu LONGTEXT NOT NULL, datetime DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', send_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_B6BD307FB83297E7 (reservation_id), INDEX IDX_B6BD307FF624B39D (sender_id), INDEX IDX_B6BD307F7C14DF52 (reception_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE sejour (id INT AUTO_INCREMENT NOT NULL, produit_id INT DEFAULT NULL, date_debut DATE NOT NULL, date_fin DATE NOT NULL, nb_jours INT NOT NULL, status VARCHAR(20) NOT NULL, INDEX IDX_96F52028F347EFB (produit_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message ADD CONSTRAINT FK_B6BD307FB83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES `user` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message ADD CONSTRAINT FK_B6BD307F7C14DF52 FOREIGN KEY (reception_id) REFERENCES `user` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sejour ADD CONSTRAINT FK_96F52028F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE produit ADD categorie_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE produit ADD CONSTRAINT FK_29A5EC27BCF5E72D FOREIGN KEY (categorie_id) REFERENCES categorie (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_29A5EC27BCF5E72D ON produit (categorie_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955CD11A2CF
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_42C84955CD11A2CF ON reservation
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation ADD sejour_id INT NOT NULL, ADD produit_id INT NOT NULL, CHANGE produits_id user_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation ADD CONSTRAINT FK_42C8495584CF0CF FOREIGN KEY (sejour_id) REFERENCES sejour (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation ADD CONSTRAINT FK_42C84955F347EFB FOREIGN KEY (produit_id) REFERENCES produit (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_42C84955A76ED395 ON reservation (user_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_42C8495584CF0CF ON reservation (sejour_id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_42C84955F347EFB ON reservation (produit_id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE user CHANGE password password VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON user (email)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC27BCF5E72D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495584CF0CF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FB83297E7
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF624B39D
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message DROP FOREIGN KEY FK_B6BD307F7C14DF52
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE sejour DROP FOREIGN KEY FK_96F52028F347EFB
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE categorie
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE message
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE sejour
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_29A5EC27BCF5E72D ON produit
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE produit DROP categorie_id
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955F347EFB
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_42C84955A76ED395 ON reservation
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_42C8495584CF0CF ON reservation
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_42C84955F347EFB ON reservation
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation DROP sejour_id, DROP produit_id, CHANGE user_id produits_id INT DEFAULT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE reservation ADD CONSTRAINT FK_42C84955CD11A2CF FOREIGN KEY (produits_id) REFERENCES produit (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_42C84955CD11A2CF ON reservation (produits_id)
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_IDENTIFIER_EMAIL ON `user`
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `user` CHANGE password password VARCHAR(20) NOT NULL
        SQL);
    }
}
