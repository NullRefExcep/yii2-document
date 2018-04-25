<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2018 NRE
 */


namespace nullref\documents\jobs;


use nullref\documents\models\document\Export as ExportDocument;
use yii\helpers\Console;
use yii\queue\JobInterface;
use yii\queue\Queue;

class Export implements JobInterface
{
    public $id;

    /**
     * @param Queue $queue which pushed and is handling the job
     */
    public function execute($queue)
    {
        $document = ExportDocument::findOne($this->id);
        Console::output('Load document ID:' . $this->id);

        /** @var \nullref\documents\models\config\Export $config */
        $config = $document->config;
        Console::output('Get config');
        $exporter = $config->getExporter();
        Console::output('Create exporter: ' . get_class($exporter));
        Console::output('Export started');
        $exporter->export($document);
    }
}