<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230625213524 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recap_detail DROP FOREIGN KEY FK_5E61F14F65E9B0F');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP INDEX IDX_5E61F14F65E9B0F ON recap_detail');
        $this->addSql('ALTER TABLE recap_detail DROP order_product_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, date_commande DATE NOT NULL, total NUMERIC(10, 0) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE recap_detail ADD order_product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE recap_detail ADD CONSTRAINT FK_5E61F14F65E9B0F FOREIGN KEY (order_product_id) REFERENCES `order` (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_5E61F14F65E9B0F ON recap_detail (order_product_id)');
    }
}
