<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;


/**
 * Auto-generated Migration: Please modify to your needs!
 */

final class Version20240520155237 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // Create the 'user' table
        $table = $schema->createTable('user');
        $table->addColumn('id', 'integer', ['autoincrement' => true]);
        $table->addColumn('data', 'string', ['length' => 255]);
        $table->setPrimaryKey(['id']);
    }

    public function down(Schema $schema): void
    {
        // Drop the 'user' table if it exists
        $schema->dropTable('user');
    }


}
