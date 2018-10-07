<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181002192358 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE entreprise ADD societe VARCHAR(255) NOT NULL, ADD adresse VARCHAR(255) NOT NULL, ADD cp VARCHAR(5) NOT NULL, ADD ville VARCHAR(255) NOT NULL, ADD prenom VARCHAR(50) NOT NULL, ADD fonction VARCHAR(255) NOT NULL, ADD presentation_entreprise TINYINT(1) NOT NULL, ADD jobdating TINYINT(1) NOT NULL, ADD potcloture TINYINT(1) NOT NULL, ADD datedepot DATETIME NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE entreprise DROP societe, DROP adresse, DROP cp, DROP ville, DROP prenom, DROP fonction, DROP presentation_entreprise, DROP jobdating, DROP potcloture, DROP datedepot');
    }
}
