<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230623222507 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398DB77003');
        $this->addSql('ALTER TABLE hair_salon DROP FOREIGN KEY FK_17637AAFDB77003');
        $this->addSql('ALTER TABLE shopping_cart DROP FOREIGN KEY FK_72AAD4F6DB77003');
        $this->addSql('ALTER TABLE professional DROP FOREIGN KEY FK_B3B573AA4B09E92C');
        $this->addSql('DROP TABLE administrator');
        $this->addSql('DROP TABLE professional');
        $this->addSql('DROP INDEX IDX_17637AAFDB77003 ON hair_salon');
        $this->addSql('ALTER TABLE hair_salon DROP professional_id');
        $this->addSql('DROP INDEX IDX_F5299398DB77003 ON `order`');
        $this->addSql('ALTER TABLE `order` DROP professional_id');
        $this->addSql('DROP INDEX UNIQ_72AAD4F6DB77003 ON shopping_cart');
        $this->addSql('ALTER TABLE shopping_cart DROP professional_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE administrator (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(80) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, password VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, role VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE professional (id INT AUTO_INCREMENT NOT NULL, administrator_id INT DEFAULT NULL, name VARCHAR(80) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, postal_adress VARCHAR(180) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, email VARCHAR(100) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, password VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, phone VARCHAR(30) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, siret VARCHAR(80) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, role VARCHAR(50) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, hair_salon TINYINT(1) NOT NULL, INDEX IDX_B3B573AA4B09E92C (administrator_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE professional ADD CONSTRAINT FK_B3B573AA4B09E92C FOREIGN KEY (administrator_id) REFERENCES administrator (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE `order` ADD professional_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398DB77003 FOREIGN KEY (professional_id) REFERENCES professional (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_F5299398DB77003 ON `order` (professional_id)');
        $this->addSql('ALTER TABLE hair_salon ADD professional_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE hair_salon ADD CONSTRAINT FK_17637AAFDB77003 FOREIGN KEY (professional_id) REFERENCES professional (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_17637AAFDB77003 ON hair_salon (professional_id)');
        $this->addSql('ALTER TABLE shopping_cart ADD professional_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE shopping_cart ADD CONSTRAINT FK_72AAD4F6DB77003 FOREIGN KEY (professional_id) REFERENCES professional (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_72AAD4F6DB77003 ON shopping_cart (professional_id)');
    }
}
