<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211010204512 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE contract (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', property_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', title VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', started_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', ended_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_E98F2859549213EC (property_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contract_party (id INT AUTO_INCREMENT NOT NULL, user_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', signed_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_CA94D2C4A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE feature (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE media (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', property_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', path VARCHAR(255) NOT NULL, size INT NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_6A2CA10C549213EC (property_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plan (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, fee INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE property (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', current_owner_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', title VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, type VARCHAR(255) NOT NULL, fee NUMERIC(10, 2) NOT NULL, currency VARCHAR(3) NOT NULL, term VARCHAR(255) NOT NULL, address VARCHAR(255) NOT NULL, sqm INT NOT NULL, latitude DOUBLE PRECISION NOT NULL, longitude DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_8BF21CDEE3441BD3 (current_owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE property_feature (property_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', feature_id INT NOT NULL, INDEX IDX_461A3F1E549213EC (property_id), INDEX IDX_461A3F1E60E4B879 (feature_id), PRIMARY KEY(property_id, feature_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE subscription (id INT AUTO_INCREMENT NOT NULL, user_id BINARY(16) DEFAULT NULL COMMENT \'(DC2Type:uuid)\', plan_id INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', finished_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_A3C664D3A76ED395 (user_id), INDEX IDX_A3C664D3E899029B (plan_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, token VARCHAR(255) DEFAULT NULL, email_verified_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', identity_verified_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, avatar LONGTEXT NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_contract (user_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', contract_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\', INDEX IDX_902CC59A76ED395 (user_id), INDEX IDX_902CC592576E0FD (contract_id), PRIMARY KEY(user_id, contract_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE contract ADD CONSTRAINT FK_E98F2859549213EC FOREIGN KEY (property_id) REFERENCES property (id)');
        $this->addSql('ALTER TABLE contract_party ADD CONSTRAINT FK_CA94D2C4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE media ADD CONSTRAINT FK_6A2CA10C549213EC FOREIGN KEY (property_id) REFERENCES property (id)');
        $this->addSql('ALTER TABLE property ADD CONSTRAINT FK_8BF21CDEE3441BD3 FOREIGN KEY (current_owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE property_feature ADD CONSTRAINT FK_461A3F1E549213EC FOREIGN KEY (property_id) REFERENCES property (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE property_feature ADD CONSTRAINT FK_461A3F1E60E4B879 FOREIGN KEY (feature_id) REFERENCES feature (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE subscription ADD CONSTRAINT FK_A3C664D3E899029B FOREIGN KEY (plan_id) REFERENCES plan (id)');
        $this->addSql('ALTER TABLE user_contract ADD CONSTRAINT FK_902CC59A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_contract ADD CONSTRAINT FK_902CC592576E0FD FOREIGN KEY (contract_id) REFERENCES contract (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_contract DROP FOREIGN KEY FK_902CC592576E0FD');
        $this->addSql('ALTER TABLE property_feature DROP FOREIGN KEY FK_461A3F1E60E4B879');
        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D3E899029B');
        $this->addSql('ALTER TABLE contract DROP FOREIGN KEY FK_E98F2859549213EC');
        $this->addSql('ALTER TABLE media DROP FOREIGN KEY FK_6A2CA10C549213EC');
        $this->addSql('ALTER TABLE property_feature DROP FOREIGN KEY FK_461A3F1E549213EC');
        $this->addSql('ALTER TABLE contract_party DROP FOREIGN KEY FK_CA94D2C4A76ED395');
        $this->addSql('ALTER TABLE property DROP FOREIGN KEY FK_8BF21CDEE3441BD3');
        $this->addSql('ALTER TABLE subscription DROP FOREIGN KEY FK_A3C664D3A76ED395');
        $this->addSql('ALTER TABLE user_contract DROP FOREIGN KEY FK_902CC59A76ED395');
        $this->addSql('DROP TABLE contract');
        $this->addSql('DROP TABLE contract_party');
        $this->addSql('DROP TABLE feature');
        $this->addSql('DROP TABLE media');
        $this->addSql('DROP TABLE plan');
        $this->addSql('DROP TABLE property');
        $this->addSql('DROP TABLE property_feature');
        $this->addSql('DROP TABLE subscription');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_contract');
    }
}
