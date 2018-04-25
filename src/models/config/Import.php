<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2018 NRE
 */


namespace nullref\documents\models\config;


use nullref\documents\components\BaseImporter;
use nullref\documents\helpers\Helper;
use nullref\documents\models\DocumentConfig;
use nullref\documents\traits\Import as ImportTrait;

class Import extends DocumentConfig
{
    use ImportTrait;

    /**
     * @var null|BaseImporter
     */
    protected $_importer = null;

    /**
     * @param $fieldName
     * @return mixed|null
     */
    public static function getTargetFieldReadable($fieldName)
    {
        $fields = self::getTargetFields();
        return array_key_exists($fieldName, $fields) ? $fields[$fieldName] : null;
    }

    /**
     * @return array
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 255],
            [['columns', 'options'], 'safe'],
            [['type'], 'integer'],
        ];
    }

    /**
     * @return array
     */
    public function getColumnsFormData()
    {
        return array_merge($this->getColumnsFormTemplate(), $this->getColumnsAssoc());
    }

    /**
     * @return array
     */
    public function getColumnsFormTemplate()
    {
        $columns = [];
        foreach ($this->getImporter()->getColumns() as $key => $label) {
            $columns[$key] = [
                'label' => $label,
                'target' => $key,
                'filter' => false,
                'source' => '',
            ];
        }
        return $columns;
    }

    /**
     * @return BaseImporter
     */
    public function getImporter()
    {
        if ($this->_importer === null) {
            $this->_importer = Helper::createImporter($this->options['importer']);
        }
        return $this->_importer;
    }

    /**
     * @return array
     */
    protected function getColumnsAssoc()
    {
        $columns = [];
        foreach ($this->columns ?: [] as $column) {
            $columns[$column['target']] = $column;
        }
        return $columns;
    }

}