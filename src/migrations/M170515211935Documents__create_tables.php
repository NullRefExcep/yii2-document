<?php

namespace nullref\documents\migrations;

use nullref\core\traits\MigrationTrait;
use yii\db\Migration;

class M170515211935Documents__create_tables extends Migration
{
    use MigrationTrait;

    public function up()
    {
        $this->createTable('{{%document}}', [
            'id' => $this->primaryKey(),
            'status' => $this->smallInteger(),
            'file_path' => $this->string(),
            'job_id' => $this->string(),
            'config_id' => $this->integer(),
            'type' => $this->smallInteger(),
            'options' => $this->text(),
            'error' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
        ], $this->getTableOptions());

        $this->createTable('{{%document_config}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(),
            'columns' => $this->text(),
            'created_at' => $this->integer(),
            'updated_at' => $this->integer(),
            'type' => $this->smallInteger(),
            'options' => $this->text(),
        ], $this->getTableOptions());

        $this->createTable('{{%document_item}}', [
            'id' => $this->primaryKey(),
            'document_id' => $this->integer(),
            'data' => $this->text(),
            'status' => $this->smallInteger(),
            'error' => $this->string(),
        ], $this->getTableOptions());


        $this->addForeignKey('fk-document-config_id',
            '{{%document}}', 'config_id',
            '{{%document_config}}', 'id'
        );

        $this->addForeignKey('fk-document_item-document_id',
            '{{%document_item}}', 'document_id',
            '{{%document}}', 'id',
            'CASCADE'
        );

    }

    public function down()
    {
        $this->dropForeignKey('fk-document_item-document_id', '{{%document_item}}');
        $this->dropForeignKey('fk-document-config_id', '{{%document}}');

        $this->dropTable('{{%document_item}}');
        $this->dropTable('{{%document_config}}');
        $this->dropTable('{{%document}}');
    }
}
