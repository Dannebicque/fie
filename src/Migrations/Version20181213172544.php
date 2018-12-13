<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181213172544 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE diplome ADD datesstages LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE offre ADD document_name VARCHAR(50) DEFAULT NULL, ADD updated DATETIME NOT NULL, CHANGE profilrecherche profilrecherche LONGTEXT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE diplome DROP datesstages');
        $this->addSql('ALTER TABLE offre DROP document_name, DROP updated, CHANGE profilrecherche profilrecherche LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
