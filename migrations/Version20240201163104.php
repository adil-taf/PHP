<?php

declare(strict_types=1);

namespace Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240201163104 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Database schema';
    }

    public function up(Schema $schema): void
    {
        $this->createUsersTable($schema);
        $this->createInvoicesTable($schema);
        $this->createInvoiceItemsTable($schema);
        $this->createEmailsTable($schema);
    }

    public function down(Schema $schema): void
    {
        $schema->dropTable('invoice_items');
        $schema->dropTable('invoices');
        $schema->dropTable('users');
        $schema->dropTable('emails');
    }

    private function createUsersTable(Schema $schema): void
    {
        $table = $schema->createTable('users');
        $table->addColumn('id', 'integer', ['autoincrement' => true, 'unsigned' => true]);
        $table->addColumn('email', 'string', ['length' => 255]);
        $table->addColumn('full_name', 'string', ['length' => 255]);
        $table->addColumn('is_active', 'boolean');
        $table->addColumn('created_at', 'datetime', ['notnull' => false, 'default' => null]);
        $table->addColumn('updated_at', 'datetime', ['notnull' => false, 'default' => null]);
        $table->setPrimaryKey(['id']);
    }

    private function createInvoicesTable(Schema $schema): void
    {
        $table = $schema->createTable('invoices');
        $table->addColumn('id', 'integer', ['autoincrement' => true, 'unsigned' => true]);
        $table->addColumn('amount', 'decimal', ['precision' => 10, 'scale' => 4]);
        $table->addColumn('user_id', 'integer', ['unsigned' => true]);
        $table->addColumn('invoice_number', 'integer', ['unsigned' => true]);
        $table->addColumn('status', 'integer', ['unsigned' => true]);
        $table->addColumn('created_at', 'datetime', ['notnull' => false, 'default' => null]);
        /*$table->addForeignKeyConstraint(
            'users',
            ['user_id'],
            ['id'],
            ['onUpdate' => 'CASCADE', 'onDelete' => 'SET NULL']
        );*/
        $table->setPrimaryKey(['id']);
    }

    private function createInvoiceItemsTable(Schema $schema): void
    {
        $table = $schema->createTable('invoice_items');
        $table->addColumn('id', 'integer', ['autoincrement' => true, 'unsigned' => true]);
        $table->addColumn('invoice_id', 'integer', ['unsigned' => true]);
        $table->addColumn('description', 'string', ['length' => 255]);
        $table->addColumn('quantity', 'integer');
        $table->addColumn('unit_price', 'decimal', ['precision' => 10, 'scale' => 4]);
        /*$table->addForeignKeyConstraint(
            'invoices',
            ['invoice_id'],
            ['id'],
            ['onUpdate' => 'CASCADE', 'onDelete' => 'SET NULL']
        );*/
        $table->setPrimaryKey(['id']);
    }

    private function createEmailsTable(Schema $schema): void
    {
        $table = $schema->createTable('emails');
        $table->addColumn('id', 'integer', ['autoincrement' => true, 'unsigned' => true]);
        $table->addColumn('subject', 'text');
        $table->addColumn('status', 'integer', ['unsigned' => true]);
        $table->addColumn('text_body', 'text');
        $table->addColumn('html_body', 'text');
        $table->addColumn('meta', 'json');
        $table->addColumn('created_at', 'datetime');
        $table->addColumn('sent_at', 'datetime');
        $table->setPrimaryKey(['id']);
    }
}
