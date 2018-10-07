<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181007110714 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE representant (id INT AUTO_INCREMENT NOT NULL, entreprise_id INT DEFAULT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, email VARCHAR(255) NOT NULL, telephone VARCHAR(10) DEFAULT NULL, fonction VARCHAR(255) DEFAULT NULL, INDEX IDX_80D5DBC9A4AEAFEA (entreprise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE representant ADD CONSTRAINT FK_80D5DBC9A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)');
        $this->addSql('ALTER TABLE entreprise DROP nom, DROP mail, DROP telephone, DROP prenom, DROP fonction');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE representant');
        $this->addSql('ALTER TABLE entreprise ADD nom VARCHAR(100) NOT NULL COLLATE utf8mb4_unicode_ci, ADD mail VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, ADD telephone VARCHAR(20) NOT NULL COLLATE utf8mb4_unicode_ci, ADD prenom VARCHAR(50) NOT NULL COLLATE utf8mb4_unicode_ci, ADD fonction VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
