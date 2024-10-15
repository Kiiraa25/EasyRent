<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241015155359 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE age (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE brand (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_1C52F9585E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE driving_license (id INT AUTO_INCREMENT NOT NULL, user_profile_id INT NOT NULL, issue_date DATE NOT NULL, expiry_date DATE NOT NULL, license_number VARCHAR(50) NOT NULL, front_image_path VARCHAR(255) NOT NULL, back_image_path VARCHAR(255) NOT NULL, country_of_issue VARCHAR(100) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, UNIQUE INDEX UNIQ_CC361A336B9DD454 (user_profile_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, rental_id INT NOT NULL, sender_id INT NOT NULL, content LONGTEXT NOT NULL, sent_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_B6BD307FA7CF2329 (rental_id), INDEX IDX_B6BD307FF624B39D (sender_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE model (id INT AUTO_INCREMENT NOT NULL, brand_id INT DEFAULT NULL, name VARCHAR(50) NOT NULL, vehicle_category VARCHAR(50) NOT NULL, INDEX IDX_D79572D944F5D008 (brand_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE payment_method (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rating (id INT AUTO_INCREMENT NOT NULL, rental_id INT NOT NULL, type VARCHAR(20) NOT NULL, rating INT NOT NULL, comment LONGTEXT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_D8892622A7CF2329 (rental_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE registration_certificate (id INT AUTO_INCREMENT NOT NULL, issue_date DATE NOT NULL, certificate_number VARCHAR(200) NOT NULL, front_image_path VARCHAR(255) NOT NULL, back_image_path VARCHAR(255) NOT NULL, country_of_issue VARCHAR(100) NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rental (id INT AUTO_INCREMENT NOT NULL, vehicle_id INT NOT NULL, renter_id INT NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, payment_method VARCHAR(40) NOT NULL, total_price INT NOT NULL, status VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', mileage_limit INT NOT NULL, cancellation_reason LONGTEXT DEFAULT NULL, cancelled_by VARCHAR(255) DEFAULT NULL, INDEX IDX_1619C27D545317D1 (vehicle_id), INDEX IDX_1619C27DE289A545 (renter_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rental_payment (id INT AUTO_INCREMENT NOT NULL, rental_id INT NOT NULL, amount INT NOT NULL, payment_date DATE NOT NULL, UNIQUE INDEX UNIQ_BECE5EE2A7CF2329 (rental_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sinister (id INT AUTO_INCREMENT NOT NULL, rental_id INT NOT NULL, description LONGTEXT NOT NULL, sinister_date DATETIME NOT NULL, report_date DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_73FC7B36A7CF2329 (rental_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE support_ticket (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, description LONGTEXT NOT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, INDEX IDX_1F5A4D53A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, profile_id INT DEFAULT NULL, email VARCHAR(180) NOT NULL, password VARCHAR(255) NOT NULL, roles JSON NOT NULL, is_active TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649CCFA12B8 (profile_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_profile (id INT AUTO_INCREMENT NOT NULL, last_name VARCHAR(50) NOT NULL, first_name VARCHAR(50) NOT NULL, address VARCHAR(255) DEFAULT NULL, postal_code VARCHAR(20) DEFAULT NULL, city VARCHAR(100) DEFAULT NULL, phone VARCHAR(20) DEFAULT NULL, birth_date DATE DEFAULT NULL, created_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, updated_at DATETIME DEFAULT CURRENT_TIMESTAMP NOT NULL, rating INT NOT NULL, picture VARCHAR(255) DEFAULT NULL, is_verified TINYINT(1) NOT NULL, country VARCHAR(100) DEFAULT NULL, description LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vehicle (id INT AUTO_INCREMENT NOT NULL, owner_id INT NOT NULL, registration_certificate_id INT NOT NULL, model_id INT NOT NULL, mileage INT NOT NULL, description LONGTEXT NOT NULL, color VARCHAR(20) NOT NULL, mileage_allowance INT NOT NULL, extra_mileage_rate INT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, fuel_type VARCHAR(20) NOT NULL, gearbox_type VARCHAR(20) NOT NULL, doors INT NOT NULL, seats INT NOT NULL, price_per_day NUMERIC(10, 2) NOT NULL, address VARCHAR(255) NOT NULL, postal_code INT NOT NULL, city VARCHAR(255) NOT NULL, status VARCHAR(255) NOT NULL, INDEX IDX_1B80E4867E3C61F9 (owner_id), UNIQUE INDEX UNIQ_1B80E486BEBCD1AF (registration_certificate_id), INDEX IDX_1B80E4867975B7E7 (model_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vehicle_condition (id INT AUTO_INCREMENT NOT NULL, rental_id INT NOT NULL, mileage INT NOT NULL, fuel_level INT NOT NULL, `condition` LONGTEXT NOT NULL, type VARCHAR(10) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_4A69CD11A7CF2329 (rental_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vehicle_photo (id INT AUTO_INCREMENT NOT NULL, vehicle_id INT DEFAULT NULL, condition_id INT DEFAULT NULL, url VARCHAR(255) NOT NULL, INDEX IDX_761804F4545317D1 (vehicle_id), INDEX IDX_761804F4887793B6 (condition_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE driving_license ADD CONSTRAINT FK_CC361A336B9DD454 FOREIGN KEY (user_profile_id) REFERENCES user_profile (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FA7CF2329 FOREIGN KEY (rental_id) REFERENCES rental (id)');
        $this->addSql('ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF624B39D FOREIGN KEY (sender_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE model ADD CONSTRAINT FK_D79572D944F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id)');
        $this->addSql('ALTER TABLE rating ADD CONSTRAINT FK_D8892622A7CF2329 FOREIGN KEY (rental_id) REFERENCES rental (id)');
        $this->addSql('ALTER TABLE rental ADD CONSTRAINT FK_1619C27D545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('ALTER TABLE rental ADD CONSTRAINT FK_1619C27DE289A545 FOREIGN KEY (renter_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE rental_payment ADD CONSTRAINT FK_BECE5EE2A7CF2329 FOREIGN KEY (rental_id) REFERENCES rental (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE sinister ADD CONSTRAINT FK_73FC7B36A7CF2329 FOREIGN KEY (rental_id) REFERENCES rental (id)');
        $this->addSql('ALTER TABLE support_ticket ADD CONSTRAINT FK_1F5A4D53A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649CCFA12B8 FOREIGN KEY (profile_id) REFERENCES user_profile (id)');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E4867E3C61F9 FOREIGN KEY (owner_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E486BEBCD1AF FOREIGN KEY (registration_certificate_id) REFERENCES registration_certificate (id)');
        $this->addSql('ALTER TABLE vehicle ADD CONSTRAINT FK_1B80E4867975B7E7 FOREIGN KEY (model_id) REFERENCES model (id)');
        $this->addSql('ALTER TABLE vehicle_condition ADD CONSTRAINT FK_4A69CD11A7CF2329 FOREIGN KEY (rental_id) REFERENCES rental (id)');
        $this->addSql('ALTER TABLE vehicle_photo ADD CONSTRAINT FK_761804F4545317D1 FOREIGN KEY (vehicle_id) REFERENCES vehicle (id)');
        $this->addSql('ALTER TABLE vehicle_photo ADD CONSTRAINT FK_761804F4887793B6 FOREIGN KEY (condition_id) REFERENCES vehicle_condition (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE driving_license DROP FOREIGN KEY FK_CC361A336B9DD454');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FA7CF2329');
        $this->addSql('ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF624B39D');
        $this->addSql('ALTER TABLE model DROP FOREIGN KEY FK_D79572D944F5D008');
        $this->addSql('ALTER TABLE rating DROP FOREIGN KEY FK_D8892622A7CF2329');
        $this->addSql('ALTER TABLE rental DROP FOREIGN KEY FK_1619C27D545317D1');
        $this->addSql('ALTER TABLE rental DROP FOREIGN KEY FK_1619C27DE289A545');
        $this->addSql('ALTER TABLE rental_payment DROP FOREIGN KEY FK_BECE5EE2A7CF2329');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE sinister DROP FOREIGN KEY FK_73FC7B36A7CF2329');
        $this->addSql('ALTER TABLE support_ticket DROP FOREIGN KEY FK_1F5A4D53A76ED395');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649CCFA12B8');
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E4867E3C61F9');
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E486BEBCD1AF');
        $this->addSql('ALTER TABLE vehicle DROP FOREIGN KEY FK_1B80E4867975B7E7');
        $this->addSql('ALTER TABLE vehicle_condition DROP FOREIGN KEY FK_4A69CD11A7CF2329');
        $this->addSql('ALTER TABLE vehicle_photo DROP FOREIGN KEY FK_761804F4545317D1');
        $this->addSql('ALTER TABLE vehicle_photo DROP FOREIGN KEY FK_761804F4887793B6');
        $this->addSql('DROP TABLE age');
        $this->addSql('DROP TABLE brand');
        $this->addSql('DROP TABLE driving_license');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE model');
        $this->addSql('DROP TABLE payment_method');
        $this->addSql('DROP TABLE rating');
        $this->addSql('DROP TABLE registration_certificate');
        $this->addSql('DROP TABLE rental');
        $this->addSql('DROP TABLE rental_payment');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE sinister');
        $this->addSql('DROP TABLE support_ticket');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_profile');
        $this->addSql('DROP TABLE vehicle');
        $this->addSql('DROP TABLE vehicle_condition');
        $this->addSql('DROP TABLE vehicle_photo');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
