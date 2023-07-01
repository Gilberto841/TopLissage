<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230625215052 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, professional_id INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', transporter_name VARCHAR(255) NOT NULL, transporteur_price DOUBLE PRECISION NOT NULL, delivery VARCHAR(255) NOT NULL, method VARCHAR(255) NOT NULL, is_paid TINYINT(1) NOT NULL, reference VARCHAR(255) NOT NULL, stripe_session_id VARCHAR(255) DEFAULT NULL, INDEX IDX_F5299398DB77003 (professional_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398DB77003 FOREIGN KEY (professional_id) REFERENCES professional (id)');
        $this->addSql('ALTER TABLE recap_detail ADD order_product_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE recap_detail ADD CONSTRAINT FK_5E61F14F65E9B0F FOREIGN KEY (order_product_id) REFERENCES `order` (id)');
        $this->addSql('CREATE INDEX IDX_5E61F14F65E9B0F ON recap_detail (order_product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE recap_detail DROP FOREIGN KEY FK_5E61F14F65E9B0F');
        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398DB77003');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('DROP INDEX IDX_5E61F14F65E9B0F ON recap_detail');
        $this->addSql('ALTER TABLE recap_detail DROP order_product_id');
    }
}
