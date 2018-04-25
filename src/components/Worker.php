<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2018 NRE
 */


namespace nullref\documents\components;


use nullref\documents\models\Document;
use yii\base\Component;

/**
 * Class Worker
 *
 * Base class for document workers
 *
 * @package nullref\documents\components
 */
abstract class Worker extends Component
{
    const OPTION_TYPE_TEXT = 'text';
    const OPTION_TYPE_DROPDOWN = 'dropdown';
    const OPTION_TYPE_CHECKBOX = 'checkbox';
    const OPTION_TYPE_WIDGET = 'widget';
    const OPTION_TYPE_FILE = 'file';

    /**
     * @return string
     */
    abstract public function getName();

    /**
     * @return array
     */
    public function getOptions()
    {
        return [];
    }

    /**
     * @return array
     */
    public function getColumns()
    {
        return [];
    }


    /**
     * @return array
     */
    public function getDocumentOptions()
    {
        return [];
    }

    /**
     * @param $document Document
     * @param $key
     * @return mixed
     */
    public function getOptionValue($document, $key)
    {
        return $document->getOptionValue($key);
    }
}