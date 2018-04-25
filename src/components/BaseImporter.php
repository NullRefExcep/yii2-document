<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2018 NRE
 */


namespace nullref\documents\components;


use nullref\documents\models\Document;

abstract class BaseImporter extends Worker
{
    /**
     * @param Document $document
     * @return boolean
     */
    abstract public function import(Document $document);
}