<?php
/**
 * @author    Dmytro Karpovych
 * @copyright 2018 NRE
 */


namespace nullref\documents\helpers;


use nullref\documents\components\BaseExporter;
use nullref\documents\components\BaseImporter;
use Yii;
use yii\base\InvalidConfigException;

class Helper
{
    /**
     * @return array
     */
    public static function getImporterMap()
    {
        $result = [];
        $importers = self::getModule()->getImporters();
        foreach ($importers as $key => $importer) {
            /** @var BaseImporter $importerObject */
            $importerObject = Yii::createObject($importer);
            $result[$key] = $importerObject->getName();
        }
        return $result;
    }

    /**
     * @return null|\yii\base\Module|\nullref\documents\Module
     */
    public static function getModule()
    {
        return Yii::$app->getModule('documents');
    }

    /**
     * @param $key
     * @return BaseImporter
     * @throws InvalidConfigException
     */
    public static function createImporter($key)
    {
        $importers = self::getModule()->getImporters();
        $importer = Yii::createObject($importers[$key]);
        if ($importer instanceof BaseImporter) {
            return $importer;
        }
        throw new InvalidConfigException('Importer must extends from BaseImporter');
    }

    /**
     * @param $key
     * @return array|object
     * @throws InvalidConfigException
     */
    public static function createExporter($key)
    {
        $exporter = self::getModule()->getExporters();
        $exporter = Yii::createObject($exporter[$key]);
        if ($exporter instanceof BaseExporter) {
            return $exporter;
        }
        throw new InvalidConfigException('Exporter must extends from BaseExporter');
    }

    /**
     * @return array
     */
    public static function getExporterMap()
    {
        $result = [];
        $exporters = self::getModule()->getExporters();
        foreach ($exporters as $key => $exporter) {
            /** @var BaseImporter $importerObject */
            $importerObject = Yii::createObject($exporter);
            $result[$key] = $importerObject->getName();
        }
        return $result;
    }
}