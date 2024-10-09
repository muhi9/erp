<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241008135034 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE make (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, name_bg VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE manufacture (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE model (id INT AUTO_INCREMENT NOT NULL, make_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, vehichle_type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_D79572D9CFBF73EB (make_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE oem (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, oem VARCHAR(255) NOT NULL, oem_normalize VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_9743D7084584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE product_model (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, model_id INT DEFAULT NULL, UNIQUE INDEX UNIQ_76C909854584665A (product_id), UNIQUE INDEX UNIQ_76C909857975B7E7 (model_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE products (id INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, manufacture_id INT DEFAULT NULL, measure INT NOT NULL, name VARCHAR(255) NOT NULL, images JSON NOT NULL COMMENT \'(DC2Type:json)\', price DOUBLE PRECISION NOT NULL, discount DOUBLE PRECISION NOT NULL, discount_from DATETIME NOT NULL, discount_to DATETIME NOT NULL, meta_description VARCHAR(255) DEFAULT NULL, meta_keywords VARCHAR(255) DEFAULT NULL, sku VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, weight DOUBLE PRECISION NOT NULL, note LONGTEXT NOT NULL, active TINYINT(1) DEFAULT NULL, quantity DOUBLE PRECISION NOT NULL, best_seller TINYINT(1) DEFAULT NULL, show_web TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_B3BA5A5A12469DE2 (category_id), UNIQUE INDEX UNIQ_B3BA5A5AEB4842B7 (manufacture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE referent (id INT AUTO_INCREMENT NOT NULL, product_id INT DEFAULT NULL, manufacture_id INT DEFAULT NULL, referent VARCHAR(255) NOT NULL, referent_normalize VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_FE9AAC6C4584665A (product_id), UNIQUE INDEX UNIQ_FE9AAC6CEB4842B7 (manufacture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE model ADD CONSTRAINT FK_D79572D9CFBF73EB FOREIGN KEY (make_id) REFERENCES make (id)');
        $this->addSql('ALTER TABLE oem ADD CONSTRAINT FK_9743D7084584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE product_model ADD CONSTRAINT FK_76C909854584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE product_model ADD CONSTRAINT FK_76C909857975B7E7 FOREIGN KEY (model_id) REFERENCES model (id)');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5A12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5AEB4842B7 FOREIGN KEY (manufacture_id) REFERENCES manufacture (id)');
        $this->addSql('ALTER TABLE referent ADD CONSTRAINT FK_FE9AAC6C4584665A FOREIGN KEY (product_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE referent ADD CONSTRAINT FK_FE9AAC6CEB4842B7 FOREIGN KEY (manufacture_id) REFERENCES manufacture (id)');
        $this->addSql('DROP TABLE manyfacture');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE manyfacture (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE model DROP FOREIGN KEY FK_D79572D9CFBF73EB');
        $this->addSql('ALTER TABLE oem DROP FOREIGN KEY FK_9743D7084584665A');
        $this->addSql('ALTER TABLE product_model DROP FOREIGN KEY FK_76C909854584665A');
        $this->addSql('ALTER TABLE product_model DROP FOREIGN KEY FK_76C909857975B7E7');
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5A12469DE2');
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5AEB4842B7');
        $this->addSql('ALTER TABLE referent DROP FOREIGN KEY FK_FE9AAC6C4584665A');
        $this->addSql('ALTER TABLE referent DROP FOREIGN KEY FK_FE9AAC6CEB4842B7');
        $this->addSql('DROP TABLE make');
        $this->addSql('DROP TABLE manufacture');
        $this->addSql('DROP TABLE model');
        $this->addSql('DROP TABLE oem');
        $this->addSql('DROP TABLE product_model');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE referent');
    }
}
