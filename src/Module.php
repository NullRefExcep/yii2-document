<?php

namespace nullref\documents;

use nullref\core\interfaces\IAdminModule;
use nullref\core\interfaces\IHasMigrateNamespace;
use rmrevin\yii\fontawesome\FA;
use Yii;
use yii\base\Module as BaseModule;
use yii\queue\JobInterface;
use yii\queue\Queue;

/**
 * This module used to update product variant's quantities parsing it from Excel files
 */
class Module extends BaseModule implements IAdminModule, IHasMigrateNamespace
{
    const MODULE_ID = 'documents';

    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'nullref\documents\controllers';

    protected $_importers = [];

    protected $_exporters = [];

    public static function getAdminMenu()
    {
        return [
            'label' => Yii::t('documents', 'Documents'),
            'icon' => FA::_FILE,
            'items' => [
                'import' => [
                    'label' => Yii::t('documents', 'Import'),
                    'url' => ['/documents/admin/import'],
                    'icon' => FA::_DOWNLOAD,
                ],
                'export' => [
                    'label' => Yii::t('documents', 'Export'),
                    'url' => ['/documents/admin/export'],
                    'icon' => FA::_UPLOAD,
                ],
                'settings' => [
                    'label' => Yii::t('documents', 'Settings'),
                    'icon' => FA::_COGS,
                    'items' => [
                        'import' => [
                            'label' => Yii::t('documents', 'Import configs'),
                            'url' => ['/documents/admin/import-config'],
                            'icon' => FA::_COG,
                        ],
                        'export' => [
                            'label' => Yii::t('documents', 'Export configs'),
                            'url' => ['/documents/admin/export-config'],
                            'icon' => FA::_COG,
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @param $job JobInterface
     */
    public static function addJob($job)
    {
        /** @var Queue $queue */
        $queue = Yii::$app->get('queue');
        $queue->push($job);
    }

    /**
     * @return array
     */
    public function getExporters()
    {
        return $this->_exporters;
    }

    /**
     * @param array $exporters
     */
    public function setExporters(array $exporters)
    {
        $this->_exporters = $exporters;
    }

    /**
     * @return array
     */
    public function getImporters()
    {
        return $this->_importers;
    }

    /**
     * @param array $importers
     */
    public function setImporters(array $importers)
    {
        $this->_importers = $importers;
    }


    public function getMigrationNamespaces($defaults)
    {
        //$defaults [] = 'yii\queue\db\migrations';

        return $defaults;
    }
}
