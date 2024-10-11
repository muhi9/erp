<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241011123756 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE employee (id INT AUTO_INCREMENT NOT NULL, posion_id INT DEFAULT NULL, workplace INT DEFAULT NULL, firstName VARCHAR(255) NOT NULL, middleName VARCHAR(255) NOT NULL, lastName VARCHAR(255) NOT NULL, identificationNumber VARCHAR(255) NOT NULL, active TINYINT(1) NOT NULL, INDEX IDX_5D9F75A1B4818DC6 (posion_id), INDEX IDX_5D9F75A1D0FB92EE (workplace), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE work_place (id INT AUTO_INCREMENT NOT NULL, company_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, invoice_prefix VARCHAR(255) NOT NULL, INDEX IDX_5CE628E2979B1AD6 (company_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A1B4818DC6 FOREIGN KEY (posion_id) REFERENCES base_noms (id)');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A1D0FB92EE FOREIGN KEY (workplace) REFERENCES work_place (id)');
        $this->addSql('ALTER TABLE work_place ADD CONSTRAINT FK_5CE628E2979B1AD6 FOREIGN KEY (company_id) REFERENCES user_personal_info (id)');
        $this->addSql('ALTER TABLE user_contact ADD workplace INT DEFAULT NULL, CHANGE personalInfo personalInfo INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_contact ADD CONSTRAINT FK_146FF832D0FB92EE FOREIGN KEY (workplace) REFERENCES work_place (id)');
        $this->addSql('CREATE INDEX IDX_146FF832D0FB92EE ON user_contact (workplace)');
        $this->addSql('ALTER TABLE user_address ADD workplace INT DEFAULT NULL, CHANGE personalInfo personalInfo INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_address ADD CONSTRAINT FK_5543718BD0FB92EE FOREIGN KEY (workplace) REFERENCES user_personal_info (id)');
        $this->addSql('CREATE INDEX IDX_5543718BD0FB92EE ON user_address (workplace)');
        $this->addSql('ALTER TABLE user_bank_account ADD workplace INT DEFAULT NULL, CHANGE personalInfo personalInfo INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user_bank_account ADD CONSTRAINT FK_D36E4208D0FB92EE FOREIGN KEY (workplace) REFERENCES user_personal_info (id)');
        $this->addSql('CREATE INDEX IDX_D36E4208D0FB92EE ON user_bank_account (workplace)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_contact DROP FOREIGN KEY FK_146FF832D0FB92EE');
        $this->addSql('ALTER TABLE employee DROP FOREIGN KEY FK_5D9F75A1D0FB92EE');
        $this->addSql('DROP TABLE employee');
        $this->addSql('DROP TABLE work_place');
        $this->addSql('ALTER TABLE user_address DROP FOREIGN KEY FK_5543718BD0FB92EE');
        $this->addSql('DROP INDEX IDX_5543718BD0FB92EE ON user_address');
        $this->addSql('ALTER TABLE user_address DROP workplace, CHANGE personalInfo personalInfo INT NOT NULL');
        $this->addSql('ALTER TABLE user_bank_account DROP FOREIGN KEY FK_D36E4208D0FB92EE');
        $this->addSql('DROP INDEX IDX_D36E4208D0FB92EE ON user_bank_account');
        $this->addSql('ALTER TABLE user_bank_account DROP workplace, CHANGE personalInfo personalInfo INT NOT NULL');
        $this->addSql('DROP INDEX IDX_146FF832D0FB92EE ON user_contact');
        $this->addSql('ALTER TABLE user_contact DROP workplace, CHANGE personalInfo personalInfo INT NOT NULL');
    }
}
