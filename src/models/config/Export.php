<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2018 NRE
 */


namespace nullref\documents\models\config;


use nullref\documents\components\BaseExporter;
use nullref\documents\helpers\Helper;
use nullref\documents\models\DocumentConfig;
use nullref\documents\traits\Export as ExportTrait;

class Export extends DocumentConfig
{
    use ExportTrait;

    /**
     * @var null|BaseExporter
     */
    protected $_exporter = null;

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
        foreach ($this->getExporter()->getColumns() as $key => $label) {
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
     * @return BaseExporter
     */
    public function getExporter()
    {
        if ($this->_exporter === null) {
            $this->_exporter = Helper::createExporter($this->options['exporter']);
        }
        return $this->_exporter;
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
}