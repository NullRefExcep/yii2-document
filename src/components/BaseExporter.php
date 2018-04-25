<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2018 NRE
 */


namespace nullref\documents\components;


use nullref\documents\models\Document;

abstract class BaseExporter extends Worker
{
    /**
     * @param Document $document
     */
    public function export(Document $document)
    {
        $this->beforeExport($document);
        $this->exportInternal($document);
        $this->afterExport($document);
    }

    /**
     * @param Document $document
     */
    public function beforeExport(Document $document)
    {
        ini_set('memory_limit', '1024M');
        $document->status = Document::STATUS_LOADING;
        $document->save(false, ['status']);
    }

    /**
     * @param Document $document
     */
    abstract public function exportInternal(Document $document);

    /**
     * @param Document $document
     */
    public function afterExport(Document $document)
    {
        $document->status = Document::STATUS_DONE;
        $document->save(false, ['status']);
    }
}