<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240923080746 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE ext_translations (id INT AUTO_INCREMENT NOT NULL, locale VARCHAR(8) NOT NULL, object_class VARCHAR(255) NOT NULL, field VARCHAR(32) NOT NULL, foreign_key VARCHAR(64) NOT NULL, content LONGTEXT DEFAULT NULL, INDEX translations_lookup_idx (locale, object_class, foreign_key), UNIQUE INDEX lookup_unique_idx (locale, object_class, field, foreign_key), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB ROW_FORMAT = DYNAMIC');
        $this->addSql('CREATE TABLE ext_log_entries (id INT AUTO_INCREMENT NOT NULL, action VARCHAR(8) NOT NULL, logged_at DATETIME NOT NULL, object_id VARCHAR(64) DEFAULT NULL, object_class VARCHAR(255) NOT NULL, version INT NOT NULL, data LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', username VARCHAR(255) DEFAULT NULL, INDEX log_class_lookup_idx (object_class), INDEX log_date_lookup_idx (logged_at), INDEX log_user_lookup_idx (username), INDEX log_version_lookup_idx (object_id, object_class, version), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB ROW_FORMAT = DYNAMIC');
        $this->addSql('CREATE TABLE base_noms (id INT AUTO_INCREMENT NOT NULL, parent_id INT DEFAULT NULL, type VARCHAR(100) NOT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, name VARCHAR(255) NOT NULL, status TINYINT(1) NOT NULL, bnorder INT NOT NULL, bnom_key VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_360A28AD727ACA70 (parent_id), INDEX IDX_360A28ADDE12AB56 (created_by), INDEX IDX_360A28AD16FE72E1 (updated_by), INDEX type_idx (type), INDEX order_idx (bnorder), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE base_noms_links (base_noms_source INT NOT NULL, base_noms_target INT NOT NULL, INDEX IDX_40D25237F4CA12D9 (base_noms_source), INDEX IDX_40D25237ED2F4256 (base_noms_target), PRIMARY KEY(base_noms_source, base_noms_target)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE country (id INT AUTO_INCREMENT NOT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, name VARCHAR(255) NOT NULL, capital VARCHAR(255) DEFAULT NULL, tz VARCHAR(255) DEFAULT NULL, currency VARCHAR(255) DEFAULT NULL, phonecode VARCHAR(255) DEFAULT NULL, iso_2l VARCHAR(2) NOT NULL, iso_3l VARCHAR(3) NOT NULL, status TINYINT(1) NOT NULL, nativeName VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_5373C9665E237E06 (name), UNIQUE INDEX UNIQ_5373C9668D9A92F4 (iso_2l), UNIQUE INDEX UNIQ_5373C9669481A3B5 (iso_3l), INDEX IDX_5373C966DE12AB56 (created_by), INDEX IDX_5373C96616FE72E1 (updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE nom_type (name_key VARCHAR(100) NOT NULL, parent_name_key1 VARCHAR(100) DEFAULT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, name VARCHAR(100) NOT NULL, parent_name_key VARCHAR(255) DEFAULT NULL, status TINYINT(1) NOT NULL, descr LONGTEXT DEFAULT NULL, extra_field LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_7E0E9D4711480F96 (parent_name_key), INDEX IDX_7E0E9D479AA191C9 (parent_name_key1), INDEX IDX_7E0E9D47DE12AB56 (created_by), INDEX IDX_7E0E9D4716FE72E1 (updated_by), PRIMARY KEY(name_key)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE base_noms_extra (id INT AUTO_INCREMENT NOT NULL, basenom_id INT DEFAULT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, baseKey VARCHAR(255) NOT NULL, baseValue LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_DC6FFE4AFF0161A7 (basenom_id), INDEX IDX_DC6FFE4ADE12AB56 (created_by), INDEX IDX_DC6FFE4A16FE72E1 (updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE language (id INT AUTO_INCREMENT NOT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, name VARCHAR(100) NOT NULL, name_local VARCHAR(100) NOT NULL, ISO2 VARCHAR(2) NOT NULL, ISO3 VARCHAR(3) NOT NULL, encoding VARCHAR(15) NOT NULL, status TINYINT(1) NOT NULL, flag VARCHAR(10) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_D4DB71B5DE12AB56 (created_by), INDEX IDX_D4DB71B516FE72E1 (updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE settings (id INT AUTO_INCREMENT NOT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, name VARCHAR(255) NOT NULL, status TINYINT(1) NOT NULL, type VARCHAR(255) DEFAULT NULL, settings LONGTEXT DEFAULT NULL, is_deleted TINYINT(1) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_E545A0C5DE12AB56 (created_by), INDEX IDX_E545A0C516FE72E1 (updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE fos_user (id INT AUTO_INCREMENT NOT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, username VARCHAR(180) NOT NULL, username_canonical VARCHAR(180) NOT NULL, email VARCHAR(180) NOT NULL, email_canonical VARCHAR(180) NOT NULL, enabled TINYINT(1) NOT NULL, salt VARCHAR(255) DEFAULT NULL, password VARCHAR(255) NOT NULL, last_login DATETIME DEFAULT NULL, confirmation_token VARCHAR(180) DEFAULT NULL, password_requested_at DATETIME DEFAULT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_957A647992FC23A8 (username_canonical), UNIQUE INDEX UNIQ_957A6479A0D96FBF (email_canonical), UNIQUE INDEX UNIQ_957A6479C05FB297 (confirmation_token), INDEX IDX_957A6479DE12AB56 (created_by), INDEX IDX_957A647916FE72E1 (updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_personal_info (id INT AUTO_INCREMENT NOT NULL, parent_organisation_id INT DEFAULT NULL, user_id INT DEFAULT NULL, person_type_id INT DEFAULT NULL, nationality_id INT DEFAULT NULL, company_type_id INT DEFAULT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, message_provider LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\', dashboard_settings LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:json_array)\', disabled TINYINT(1) DEFAULT \'0\' NOT NULL, credit NUMERIC(25, 4) DEFAULT NULL, delayed_payment INT DEFAULT NULL, first_name VARCHAR(200) DEFAULT NULL, middle_name VARCHAR(200) DEFAULT NULL, last_name VARCHAR(200) DEFAULT NULL, nickname VARCHAR(200) DEFAULT NULL, languages LONGTEXT DEFAULT NULL COMMENT \'(DC2Type:array)\', company VARCHAR(200) DEFAULT NULL, company_name VARCHAR(200) DEFAULT NULL, company_id VARCHAR(20) DEFAULT NULL, company_vat VARCHAR(20) DEFAULT NULL, company_person VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_140D9B3A9A71179 (parent_organisation_id), UNIQUE INDEX UNIQ_140D9B3AA76ED395 (user_id), INDEX IDX_140D9B3A1C9DA55 (nationality_id), INDEX IDX_140D9B3AE51E9644 (company_type_id), INDEX IDX_140D9B3ADE12AB56 (created_by), INDEX IDX_140D9B3A16FE72E1 (updated_by), INDEX IDX_140D9B3AE7D23F1A (person_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_profile_role_types (profile_id INT NOT NULL, basenom_id INT NOT NULL, INDEX IDX_C5C3B0B9CCFA12B8 (profile_id), INDEX IDX_C5C3B0B9FF0161A7 (basenom_id), PRIMARY KEY(profile_id, basenom_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_images (personal_info_id INT NOT NULL, file_id INT NOT NULL, INDEX IDX_854DA557DEACC8D3 (personal_info_id), INDEX IDX_854DA55793CB796C (file_id), PRIMARY KEY(personal_info_id, file_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_contact (id INT AUTO_INCREMENT NOT NULL, contact_type_id INT NOT NULL, emergency_contact_bnom_phone_type_id INT DEFAULT NULL, emergency_contact_bnom_email_type_id INT DEFAULT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, info1 VARCHAR(255) NOT NULL, info2 VARCHAR(255) DEFAULT NULL, info3 VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, personalInfo INT NOT NULL, INDEX IDX_146FF8324259553D (personalInfo), INDEX IDX_146FF8325F63AD12 (contact_type_id), INDEX IDX_146FF83295E7A453 (emergency_contact_bnom_phone_type_id), INDEX IDX_146FF832B86540AE (emergency_contact_bnom_email_type_id), INDEX IDX_146FF832DE12AB56 (created_by), INDEX IDX_146FF83216FE72E1 (updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_address (id INT AUTO_INCREMENT NOT NULL, contact_type_id INT NOT NULL, country_id INT NOT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, address1 VARCHAR(255) NOT NULL, address1phonetic VARCHAR(255) NOT NULL, address2 VARCHAR(255) DEFAULT NULL, address2phonetic VARCHAR(255) DEFAULT NULL, place VARCHAR(255) NOT NULL, place_phonetic VARCHAR(255) NOT NULL, postcode VARCHAR(255) DEFAULT NULL, municipality VARCHAR(255) NOT NULL, municipality_phonetic VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, personalInfo INT NOT NULL, INDEX IDX_5543718B4259553D (personalInfo), INDEX IDX_5543718B5F63AD12 (contact_type_id), INDEX IDX_5543718BF92F3E70 (country_id), INDEX IDX_5543718BDE12AB56 (created_by), INDEX IDX_5543718B16FE72E1 (updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_bank_account (id INT AUTO_INCREMENT NOT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, bank VARCHAR(200) NOT NULL, iban VARCHAR(50) NOT NULL, bic VARCHAR(100) DEFAULT NULL, swift VARCHAR(100) DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, personalInfo INT NOT NULL, INDEX IDX_D36E42084259553D (personalInfo), INDEX IDX_D36E4208DE12AB56 (created_by), INDEX IDX_D36E420816FE72E1 (updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE file (id INT AUTO_INCREMENT NOT NULL, bnom_type1_id INT DEFAULT NULL, bnom_type2_id INT DEFAULT NULL, bnom_type3_id INT DEFAULT NULL, bnom_type4_id INT DEFAULT NULL, parent INT DEFAULT NULL, deleted_by INT DEFAULT NULL, created_by INT DEFAULT NULL, updated_by INT DEFAULT NULL, path VARCHAR(255) NOT NULL, disk_name VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, custom_name VARCHAR(255) DEFAULT NULL, entity_class VARCHAR(255) NOT NULL, entity_id INT NOT NULL, dummy_field VARCHAR(30) DEFAULT NULL, mime_type VARCHAR(255) NOT NULL, issued_on DATETIME DEFAULT NULL, valid_from DATETIME DEFAULT NULL, valid_to DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, deleted_at DATETIME DEFAULT NULL, INDEX IDX_8C9F361030EB8990 (bnom_type1_id), INDEX IDX_8C9F3610225E267E (bnom_type2_id), INDEX IDX_8C9F36109AE2411B (bnom_type3_id), INDEX IDX_8C9F361073579A2 (bnom_type4_id), INDEX IDX_8C9F36103D8E604F (parent), INDEX IDX_8C9F36101F6FA0AF (deleted_by), INDEX IDX_8C9F3610DE12AB56 (created_by), INDEX IDX_8C9F361016FE72E1 (updated_by), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE base_noms ADD CONSTRAINT FK_360A28AD727ACA70 FOREIGN KEY (parent_id) REFERENCES base_noms (id)');
        $this->addSql('ALTER TABLE base_noms ADD CONSTRAINT FK_360A28AD8CDE5729 FOREIGN KEY (type) REFERENCES nom_type (name_key)');
        $this->addSql('ALTER TABLE base_noms ADD CONSTRAINT FK_360A28ADDE12AB56 FOREIGN KEY (created_by) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE base_noms ADD CONSTRAINT FK_360A28AD16FE72E1 FOREIGN KEY (updated_by) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE base_noms_links ADD CONSTRAINT FK_40D25237F4CA12D9 FOREIGN KEY (base_noms_source) REFERENCES base_noms (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE base_noms_links ADD CONSTRAINT FK_40D25237ED2F4256 FOREIGN KEY (base_noms_target) REFERENCES base_noms (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE country ADD CONSTRAINT FK_5373C966DE12AB56 FOREIGN KEY (created_by) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE country ADD CONSTRAINT FK_5373C96616FE72E1 FOREIGN KEY (updated_by) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE nom_type ADD CONSTRAINT FK_7E0E9D479AA191C9 FOREIGN KEY (parent_name_key1) REFERENCES nom_type (name_key)');
        $this->addSql('ALTER TABLE nom_type ADD CONSTRAINT FK_7E0E9D47DE12AB56 FOREIGN KEY (created_by) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE nom_type ADD CONSTRAINT FK_7E0E9D4716FE72E1 FOREIGN KEY (updated_by) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE base_noms_extra ADD CONSTRAINT FK_DC6FFE4AFF0161A7 FOREIGN KEY (basenom_id) REFERENCES base_noms (id)');
        $this->addSql('ALTER TABLE base_noms_extra ADD CONSTRAINT FK_DC6FFE4ADE12AB56 FOREIGN KEY (created_by) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE base_noms_extra ADD CONSTRAINT FK_DC6FFE4A16FE72E1 FOREIGN KEY (updated_by) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE language ADD CONSTRAINT FK_D4DB71B5DE12AB56 FOREIGN KEY (created_by) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE language ADD CONSTRAINT FK_D4DB71B516FE72E1 FOREIGN KEY (updated_by) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE settings ADD CONSTRAINT FK_E545A0C5DE12AB56 FOREIGN KEY (created_by) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE settings ADD CONSTRAINT FK_E545A0C516FE72E1 FOREIGN KEY (updated_by) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE fos_user ADD CONSTRAINT FK_957A6479DE12AB56 FOREIGN KEY (created_by) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE fos_user ADD CONSTRAINT FK_957A647916FE72E1 FOREIGN KEY (updated_by) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE user_personal_info ADD CONSTRAINT FK_140D9B3A9A71179 FOREIGN KEY (parent_organisation_id) REFERENCES user_personal_info (id)');
        $this->addSql('ALTER TABLE user_personal_info ADD CONSTRAINT FK_140D9B3AA76ED395 FOREIGN KEY (user_id) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE user_personal_info ADD CONSTRAINT FK_140D9B3AE7D23F1A FOREIGN KEY (person_type_id) REFERENCES base_noms (id)');
        $this->addSql('ALTER TABLE user_personal_info ADD CONSTRAINT FK_140D9B3A1C9DA55 FOREIGN KEY (nationality_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE user_personal_info ADD CONSTRAINT FK_140D9B3AE51E9644 FOREIGN KEY (company_type_id) REFERENCES base_noms (id)');
        $this->addSql('ALTER TABLE user_personal_info ADD CONSTRAINT FK_140D9B3ADE12AB56 FOREIGN KEY (created_by) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE user_personal_info ADD CONSTRAINT FK_140D9B3A16FE72E1 FOREIGN KEY (updated_by) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE user_profile_role_types ADD CONSTRAINT FK_C5C3B0B9CCFA12B8 FOREIGN KEY (profile_id) REFERENCES user_personal_info (id)');
        $this->addSql('ALTER TABLE user_profile_role_types ADD CONSTRAINT FK_C5C3B0B9FF0161A7 FOREIGN KEY (basenom_id) REFERENCES base_noms (id)');
        $this->addSql('ALTER TABLE user_images ADD CONSTRAINT FK_854DA557DEACC8D3 FOREIGN KEY (personal_info_id) REFERENCES user_personal_info (id)');
        $this->addSql('ALTER TABLE user_images ADD CONSTRAINT FK_854DA55793CB796C FOREIGN KEY (file_id) REFERENCES file (id)');
        $this->addSql('ALTER TABLE user_contact ADD CONSTRAINT FK_146FF8324259553D FOREIGN KEY (personalInfo) REFERENCES user_personal_info (id)');
        $this->addSql('ALTER TABLE user_contact ADD CONSTRAINT FK_146FF8325F63AD12 FOREIGN KEY (contact_type_id) REFERENCES base_noms (id)');
        $this->addSql('ALTER TABLE user_contact ADD CONSTRAINT FK_146FF83295E7A453 FOREIGN KEY (emergency_contact_bnom_phone_type_id) REFERENCES base_noms (id)');
        $this->addSql('ALTER TABLE user_contact ADD CONSTRAINT FK_146FF832B86540AE FOREIGN KEY (emergency_contact_bnom_email_type_id) REFERENCES base_noms (id)');
        $this->addSql('ALTER TABLE user_contact ADD CONSTRAINT FK_146FF832DE12AB56 FOREIGN KEY (created_by) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE user_contact ADD CONSTRAINT FK_146FF83216FE72E1 FOREIGN KEY (updated_by) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE user_address ADD CONSTRAINT FK_5543718B4259553D FOREIGN KEY (personalInfo) REFERENCES user_personal_info (id)');
        $this->addSql('ALTER TABLE user_address ADD CONSTRAINT FK_5543718B5F63AD12 FOREIGN KEY (contact_type_id) REFERENCES base_noms (id)');
        $this->addSql('ALTER TABLE user_address ADD CONSTRAINT FK_5543718BF92F3E70 FOREIGN KEY (country_id) REFERENCES country (id)');
        $this->addSql('ALTER TABLE user_address ADD CONSTRAINT FK_5543718BDE12AB56 FOREIGN KEY (created_by) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE user_address ADD CONSTRAINT FK_5543718B16FE72E1 FOREIGN KEY (updated_by) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE user_bank_account ADD CONSTRAINT FK_D36E42084259553D FOREIGN KEY (personalInfo) REFERENCES user_personal_info (id)');
        $this->addSql('ALTER TABLE user_bank_account ADD CONSTRAINT FK_D36E4208DE12AB56 FOREIGN KEY (created_by) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE user_bank_account ADD CONSTRAINT FK_D36E420816FE72E1 FOREIGN KEY (updated_by) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F361030EB8990 FOREIGN KEY (bnom_type1_id) REFERENCES base_noms (id)');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F3610225E267E FOREIGN KEY (bnom_type2_id) REFERENCES base_noms (id)');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F36109AE2411B FOREIGN KEY (bnom_type3_id) REFERENCES base_noms (id)');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F361073579A2 FOREIGN KEY (bnom_type4_id) REFERENCES base_noms (id)');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F36103D8E604F FOREIGN KEY (parent) REFERENCES file (id)');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F36101F6FA0AF FOREIGN KEY (deleted_by) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F3610DE12AB56 FOREIGN KEY (created_by) REFERENCES fos_user (id)');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F361016FE72E1 FOREIGN KEY (updated_by) REFERENCES fos_user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE base_noms DROP FOREIGN KEY FK_360A28AD727ACA70');
        $this->addSql('ALTER TABLE base_noms_links DROP FOREIGN KEY FK_40D25237F4CA12D9');
        $this->addSql('ALTER TABLE base_noms_links DROP FOREIGN KEY FK_40D25237ED2F4256');
        $this->addSql('ALTER TABLE base_noms_extra DROP FOREIGN KEY FK_DC6FFE4AFF0161A7');
        $this->addSql('ALTER TABLE user_personal_info DROP FOREIGN KEY FK_140D9B3AE7D23F1A');
        $this->addSql('ALTER TABLE user_personal_info DROP FOREIGN KEY FK_140D9B3AE51E9644');
        $this->addSql('ALTER TABLE user_profile_role_types DROP FOREIGN KEY FK_C5C3B0B9FF0161A7');
        $this->addSql('ALTER TABLE user_contact DROP FOREIGN KEY FK_146FF8325F63AD12');
        $this->addSql('ALTER TABLE user_contact DROP FOREIGN KEY FK_146FF83295E7A453');
        $this->addSql('ALTER TABLE user_contact DROP FOREIGN KEY FK_146FF832B86540AE');
        $this->addSql('ALTER TABLE user_address DROP FOREIGN KEY FK_5543718B5F63AD12');
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F361030EB8990');
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F3610225E267E');
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F36109AE2411B');
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F361073579A2');
        $this->addSql('ALTER TABLE user_personal_info DROP FOREIGN KEY FK_140D9B3A1C9DA55');
        $this->addSql('ALTER TABLE user_address DROP FOREIGN KEY FK_5543718BF92F3E70');
        $this->addSql('ALTER TABLE base_noms DROP FOREIGN KEY FK_360A28AD8CDE5729');
        $this->addSql('ALTER TABLE nom_type DROP FOREIGN KEY FK_7E0E9D479AA191C9');
        $this->addSql('ALTER TABLE base_noms DROP FOREIGN KEY FK_360A28ADDE12AB56');
        $this->addSql('ALTER TABLE base_noms DROP FOREIGN KEY FK_360A28AD16FE72E1');
        $this->addSql('ALTER TABLE country DROP FOREIGN KEY FK_5373C966DE12AB56');
        $this->addSql('ALTER TABLE country DROP FOREIGN KEY FK_5373C96616FE72E1');
        $this->addSql('ALTER TABLE nom_type DROP FOREIGN KEY FK_7E0E9D47DE12AB56');
        $this->addSql('ALTER TABLE nom_type DROP FOREIGN KEY FK_7E0E9D4716FE72E1');
        $this->addSql('ALTER TABLE base_noms_extra DROP FOREIGN KEY FK_DC6FFE4ADE12AB56');
        $this->addSql('ALTER TABLE base_noms_extra DROP FOREIGN KEY FK_DC6FFE4A16FE72E1');
        $this->addSql('ALTER TABLE language DROP FOREIGN KEY FK_D4DB71B5DE12AB56');
        $this->addSql('ALTER TABLE language DROP FOREIGN KEY FK_D4DB71B516FE72E1');
        $this->addSql('ALTER TABLE settings DROP FOREIGN KEY FK_E545A0C5DE12AB56');
        $this->addSql('ALTER TABLE settings DROP FOREIGN KEY FK_E545A0C516FE72E1');
        $this->addSql('ALTER TABLE fos_user DROP FOREIGN KEY FK_957A6479DE12AB56');
        $this->addSql('ALTER TABLE fos_user DROP FOREIGN KEY FK_957A647916FE72E1');
        $this->addSql('ALTER TABLE user_personal_info DROP FOREIGN KEY FK_140D9B3AA76ED395');
        $this->addSql('ALTER TABLE user_personal_info DROP FOREIGN KEY FK_140D9B3ADE12AB56');
        $this->addSql('ALTER TABLE user_personal_info DROP FOREIGN KEY FK_140D9B3A16FE72E1');
        $this->addSql('ALTER TABLE user_contact DROP FOREIGN KEY FK_146FF832DE12AB56');
        $this->addSql('ALTER TABLE user_contact DROP FOREIGN KEY FK_146FF83216FE72E1');
        $this->addSql('ALTER TABLE user_address DROP FOREIGN KEY FK_5543718BDE12AB56');
        $this->addSql('ALTER TABLE user_address DROP FOREIGN KEY FK_5543718B16FE72E1');
        $this->addSql('ALTER TABLE user_bank_account DROP FOREIGN KEY FK_D36E4208DE12AB56');
        $this->addSql('ALTER TABLE user_bank_account DROP FOREIGN KEY FK_D36E420816FE72E1');
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F36101F6FA0AF');
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F3610DE12AB56');
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F361016FE72E1');
        $this->addSql('ALTER TABLE user_personal_info DROP FOREIGN KEY FK_140D9B3A9A71179');
        $this->addSql('ALTER TABLE user_profile_role_types DROP FOREIGN KEY FK_C5C3B0B9CCFA12B8');
        $this->addSql('ALTER TABLE user_images DROP FOREIGN KEY FK_854DA557DEACC8D3');
        $this->addSql('ALTER TABLE user_contact DROP FOREIGN KEY FK_146FF8324259553D');
        $this->addSql('ALTER TABLE user_address DROP FOREIGN KEY FK_5543718B4259553D');
        $this->addSql('ALTER TABLE user_bank_account DROP FOREIGN KEY FK_D36E42084259553D');
        $this->addSql('ALTER TABLE user_images DROP FOREIGN KEY FK_854DA55793CB796C');
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F36103D8E604F');
        $this->addSql('DROP TABLE ext_translations');
        $this->addSql('DROP TABLE ext_log_entries');
        $this->addSql('DROP TABLE base_noms');
        $this->addSql('DROP TABLE base_noms_links');
        $this->addSql('DROP TABLE country');
        $this->addSql('DROP TABLE nom_type');
        $this->addSql('DROP TABLE base_noms_extra');
        $this->addSql('DROP TABLE language');
        $this->addSql('DROP TABLE settings');
        $this->addSql('DROP TABLE fos_user');
        $this->addSql('DROP TABLE user_personal_info');
        $this->addSql('DROP TABLE user_profile_role_types');
        $this->addSql('DROP TABLE user_images');
        $this->addSql('DROP TABLE user_contact');
        $this->addSql('DROP TABLE user_address');
        $this->addSql('DROP TABLE user_bank_account');
        $this->addSql('DROP TABLE file');
    }
}
