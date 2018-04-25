<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2018 NRE
 */


namespace nullref\documents\jobs;


use nullref\documents\models\document\Import as ImportDocument;
use yii\helpers\Console;
use yii\queue\JobInterface;
use yii\queue\Queue;

class Import implements JobInterface
{
    public $id;

    /**
     * @param Queue $queue which pushed and is handling the job
     */
    public function execute($queue)
    {
        $document = ImportDocument::findOne($this->id);
        Console::output('Load document ID:' . $this->id);

        /** @var \nullref\documents\models\config\Import $config */
        $config = $document->config;
        Console::output('Get config');
        $importer = $config->getImporter();
        Console::output('Create importer: ' . get_class($importer));
        Console::output('Import started');
        $importer->import($document);
    }
}