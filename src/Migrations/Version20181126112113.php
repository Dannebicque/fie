<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181126112113 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE diplome (id INT AUTO_INCREMENT NOT NULL, libelle VARCHAR(255) NOT NULL, sigle VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entreprise (id INT AUTO_INCREMENT NOT NULL, societe VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, cp VARCHAR(5) NOT NULL, ville VARCHAR(255) NOT NULL, presentation_entreprise TINYINT(1) NOT NULL, jobdating TINYINT(1) NOT NULL, potcloture TINYINT(1) NOT NULL, datedepot DATETIME NOT NULL, remarques LONGTEXT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entreprise_diplome (entreprise_id INT NOT NULL, diplome_id INT NOT NULL, INDEX IDX_53A8FEFDA4AEAFEA (entreprise_id), INDEX IDX_53A8FEFD26F859E2 (diplome_id), PRIMARY KEY(entreprise_id, diplome_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE representant (id INT AUTO_INCREMENT NOT NULL, entreprise_id INT DEFAULT NULL, nom VARCHAR(50) NOT NULL, prenom VARCHAR(50) NOT NULL, email VARCHAR(255) NOT NULL, telephone VARCHAR(10) DEFAULT NULL, fonction VARCHAR(255) DEFAULT NULL, civilite VARCHAR(3) NOT NULL, INDEX IDX_80D5DBC9A4AEAFEA (entreprise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre (id INT AUTO_INCREMENT NOT NULL, entreprise_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, decription LONGTEXT NOT NULL, profilrecherche LONGTEXT NOT NULL, INDEX IDX_AF86866FA4AEAFEA (entreprise_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE offre_diplome (offre_id INT NOT NULL, diplome_id INT NOT NULL, INDEX IDX_47973E6E4CC8505A (offre_id), INDEX IDX_47973E6E26F859E2 (diplome_id), PRIMARY KEY(offre_id, diplome_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE entreprise_diplome ADD CONSTRAINT FK_53A8FEFDA4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE entreprise_diplome ADD CONSTRAINT FK_53A8FEFD26F859E2 FOREIGN KEY (diplome_id) REFERENCES diplome (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE representant ADD CONSTRAINT FK_80D5DBC9A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866FA4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)');
        $this->addSql('ALTER TABLE offre_diplome ADD CONSTRAINT FK_47973E6E4CC8505A FOREIGN KEY (offre_id) REFERENCES offre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE offre_diplome ADD CONSTRAINT FK_47973E6E26F859E2 FOREIGN KEY (diplome_id) REFERENCES diplome (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE entreprise_diplome DROP FOREIGN KEY FK_53A8FEFD26F859E2');
        $this->addSql('ALTER TABLE offre_diplome DROP FOREIGN KEY FK_47973E6E26F859E2');
        $this->addSql('ALTER TABLE entreprise_diplome DROP FOREIGN KEY FK_53A8FEFDA4AEAFEA');
        $this->addSql('ALTER TABLE representant DROP FOREIGN KEY FK_80D5DBC9A4AEAFEA');
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866FA4AEAFEA');
        $this->addSql('ALTER TABLE offre_diplome DROP FOREIGN KEY FK_47973E6E4CC8505A');
        $this->addSql('DROP TABLE diplome');
        $this->addSql('DROP TABLE entreprise');
        $this->addSql('DROP TABLE entreprise_diplome');
        $this->addSql('DROP TABLE representant');
        $this->addSql('DROP TABLE offre');
        $this->addSql('DROP TABLE offre_diplome');
    }
}
